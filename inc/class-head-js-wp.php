<?php
if(!class_exists('Head_js_wp')){
  class Head_js_wp{

    private $deferred_resources = array();

    // Setup singleton pattern
    public static function get_instance(){
      static $instance;

      if(null === $instance){
        $instance = new self();
      }

      return $instance;
    }

    private function __clone(){
      return null;
    }

    private function __wakeup(){
      return null;
    }

    public static function deactivate(){
      return null;
    }

    private static function async_load($document, $tag, $url, $type = false, $rel = false, $media = false, $callback = false){
      $output = '(function(doc, tag, url, type, rel, media){';
      $output .= 'var resource = doc.createElement(tag);';
      $output .= !empty($type) ? 'resource.type = type;' : '';
      $output .= !empty($rel) ? 'resource.rel = rel;' : '';
      $output .= !empty($media) ? 'resource.media = media;' : '';
      $output .= 'script' === $tag ? 'resource.src = url;' : 'resource.href = url;';
      $output .= !empty($callback) ? 'resource.addEventListener(\'load\', function(){' . $callback . '();}, false);' : '';
      $output .= 'doc.getElementsByTagName(\'head\')[0].appendChild(resource);';
      $output .= '}(' . $document . ', \'' . $tag . '\', \'' . $url . '\', \'' . $type . '\', \'' . $rel . '\', \'' . $media . '\'));';
      return $output;
    }

    private function deferred_resource_loader(){
      $output = '';
      $output .= 'var loadDeferredResources = function(){';
      $output .= 'var resources = [';
      foreach($this->deferred_resources as $resource){
        $output .= '{';
        foreach($resource as $attr => $value){
          $output .= $attr . ':\'' . $value . '\',';
        }
        $output = rtrim($output, ',');
        $output .= '},';

      }
      $output = rtrim($output, ',');
      $output .= '];';
      $output .= 'for(var i = 0, ri = resources.length; i < ri; i++){';
      $output .= 'var resource = document.createElement(resources[i].tag);';
      $output .= 'if(resources[i].type){resource.type = resources[i].type;}';
      $output .= 'if(resources[i].rel){resource.rel = resources[i].rel;}';
      $output .= 'if(resources[i].media){resource.media = resources[i].media;}';
      $output .= 'if(\'script\' === resources[i].tag){resource.src = resources[i].url;}else{resource.href = resources[i].url;}';
      $output .= 'if(resources[i].callback){resource.addEventListener(\'load\', window[resources[i].callback], false);}';
      $output .= 'document.getElementsByTagName(\'head\')[0].appendChild(resource);';
      $output .= '}';
      $output .= '};';
      $output .= 'if(window.addEventListener){';
      $output .= 'window.addEventListener("load", loadDeferredResources, false);';
      $output .= '}else if(window.attachEvent){';
      $output .= 'window.attachEvent("onload", loadDeferredResources);';
      $output .= '}else{';
      $output .= 'window.onload = loadDeferredResources;';
      $output .= '}' . "\n";
      return $output;
    }

    private function __construct(){
      if(!is_admin()){
        // Enqueue scripts
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

        // Replace _wp_footer_scripts
        remove_action('wp_print_footer_scripts', '_wp_footer_scripts');
        add_action('wp_print_footer_scripts', array($this, 'print_footer_scripts'));

        // Add helper shortcodes
        if(!shortcode_exists('enqueue_style')){
          add_shortcode('enqueue_style', array($this, 'shortcode_enqueue_style'));
        }
        if(!shortcode_exists('enqueue_script')){
          add_shortcode('enqueue_script', array($this, 'shortcode_enqueue_script'));
        }
      }
    }

    public function enqueue_scripts(){
      if(!wp_script_is('head-loader') || !wp_script_is('head-js') || !wp_script_is('head')){
        wp_register_script('head-loader', HEAD_JS_WP_URL . '/js/libs/head.load.min.js', array(), HEAD_JS_VERSION, true);
      }
    }

    public function print_footer_scripts(){
      global $wp_scripts, $wp_styles;

      if(!empty($wp_scripts->queue)){
        $wp_scripts->all_deps($wp_scripts->queue);
      }

      if(!empty($wp_styles->queue)){
        $wp_styles->all_deps($wp_styles->queue);
      }

      if(!empty($wp_scripts->to_do) || !empty($wp_styles->to_do)){

        foreach($wp_scripts->to_do as $handle){
          $wp_scripts->print_extra_script($handle);
        }

        $script_queues = $this->queue_scripts($wp_scripts);

        echo '<script>' . "\n";
        $this->queue_late_styles($wp_styles) . "\n";
        $this->deferred_resources[] = array(
          'tag' => 'script',
          'url' => $wp_scripts->registered['head-loader']->src,
          'type' => 'text/javascript',
          'callback' => 'init_head_load'
        );
        echo $this->deferred_resource_loader();
        echo 'var init_head_load = function(){';
        echo 'head';
        foreach($script_queues as $queue){
          echo "\n" . '.js({';
          foreach($queue as $number => $script){
            $ending = (count($queue) === $number + 1) ? '})' : "},\n{";
            $version = ($wp_scripts->registered[$script]->ver) ? '?ver=' . $wp_scripts->registered[$script]->ver : "";
            $base_url = (preg_match('/^\//', $wp_scripts->registered[$script]->src)) ? $wp_scripts->base_url : '';
            echo str_replace('-', '_', $script) . " : '" . $base_url . $wp_scripts->registered[$script]->src . $version . "'" . $ending;
            $wp_scripts->done[] = $script;
            $wp_scripts->to_do = array_merge(array_diff($wp_scripts->to_do, array($script)));
          }
        }
        echo ';' . "\n" . '};</script>' . "\n";
        return true;
      }else{
        return false;
      }
    }

    public function shortcode_enqueue_style($atts){
      extract(shortcode_atts(array(
        'handle' => '',
        'src' => '',
        'deps' => '',
        'ver' => false,
        'media' => 'all'
      ), $atts));

      if(!empty($handle) && empty($src)){
        wp_enqueue_style($handle);
      }elseif(!empty($handle) && !empty($src)){
        wp_enqueue_style($handle, $src, array($deps), $ver, $media);
      }
    }

    public function shortcode_enqueue_script($atts){
      extract(shortcode_atts(array(
        'handle' => '',
        'src' => '',
        'deps' => '',
        'ver' => false,
        'in_footer' => false
      ), $atts));

      if(!empty($handle) && empty($src)){
        wp_enqueue_script($handle);
      }elseif(!empty($handle) && !empty($src)){
        wp_enqueue_script($handle, $src, array($deps), $ver, $media);
      }
    }

    private function queue_late_styles($wp_styles){
      if(empty($wp_styles->to_do)){
        return false;
      }
      foreach($wp_styles->to_do as $style){
        $this->deferred_resources[] = array(
          'tag' => 'link',
          'url' => $wp_styles->registered[$style]->src,
          'type' => 'text/css',
          'rel' => 'stylesheet',
          'media' => $wp_styles->registered[$style]->args
        );
      }

      return true;
    }

    private function queue_scripts($wp_scripts){
      $script_queues = array();

      foreach($wp_scripts->to_do as $script){
        if(!in_array($script, $wp_scripts->done)){
          if(!in_array($script, $wp_scripts->done) && empty($wp_scripts->registered[$script]->deps) || $this->deps_done($script, $wp_scripts)){
            $script_queues[] = array(
              $script
            );
          }else{
            foreach($script_queues as $number => $queue){
              foreach($queue as $queue_script){
                if(in_array($queue_script, $wp_scripts->registered[$script]->deps) && !in_array($script, $script_queues[$number])){
                  $script_queues[$number][] = $script;
                }
              }
            }
          }
        }
      }

      foreach($script_queues as  $number => $queue){
        foreach($script_queues as $number_check => $queue_check){
          if($number > $number_check){
            $intersect = array_intersect($script_queues[$number], $script_queues[$number_check]);
            if(!empty($intersect)){
              $script_queues[$number] = array_diff($script_queues[$number], $intersect);
              $script_queues[$number] = array_merge($script_queues[$number], $script_queues[$number_check]);
              unset($script_queues[$number_check]);
            }
          }
        }
      }
      return $script_queues;
    }

    private function deps_done($script, $wp_scripts){
      foreach($wp_scripts->registered[$script]->deps as $dep){
        if(!in_array($dep, $wp_scripts->done)){
          return false;
        }
      }
      return true;
    }
  }
}
