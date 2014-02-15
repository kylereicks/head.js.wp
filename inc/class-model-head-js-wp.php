<?php
if(!class_exists('Model_Head_JS_WP')){
  class Model_Head_JS_WP{

    public $queue = array();
    public $in_head = array();

    public function __construct(){
      global $wp_scripts, $wp_styles;

      if(!empty($wp_scripts->queue)){
        $wp_scripts->all_deps($wp_scripts->queue);
      }

      if(!empty($wp_styles->queue)){
        $wp_styles->all_deps($wp_styles->queue);
      }

      if(!empty($wp_styles->done)){
        $this->in_head = array_merge($this->in_head, $wp_styles->done);
      }

      if(!empty($wp_scripts->done)){
        $this->in_head = array_merge($this->in_head, $wp_scripts->done);
      }
      $this->set_queue();
    }

    public function done($handle){
      global $wp_scripts, $wp_styles;

      if(false !== $key = array_search($handle, $wp_scripts->to_do)){
        $wp_scripts->done[] = $handle;
        unset($wp_scripts->to_do[$key]);
      }elseif(false !== $key = array_search($handle, $wp_styles->to_do)){
        $wp_styles->done[] = $handle;
        unset($wp_styles->to_do[$key]);
      }

    }

    public function no_deps($handle){
      global $wp_scripts, $wp_styles;

      if(array_key_exists($handle, $wp_styles->registered)){
        if(empty($wp_styles->registered[$handle]->deps)){
          return true;
        }

        foreach($wp_styles->registered[$handle]->deps as $dep){
          if(!in_array($dep, $this->in_head)){
            return false;
          }
        }

        return true;

      }elseif(array_key_exists($handle, $wp_scripts->registered)){
        if(empty($wp_scripts->registered[$handle]->deps)){
          return true;
        }

        foreach($wp_scripts->registered[$handle]->deps as $dep){
          if(!in_array($dep, $this->in_head)){
            return false;
          }
        }

        return true;

      }
    }

    private function set_queue(){
      global $wp_scripts, $wp_styles;

      if(!empty($wp_styles->to_do)){
        foreach($wp_styles->to_do as $style){
          if(!in_array($style, $wp_styles->done)){
            $unloaded_deps = array();
            foreach($wp_styles->registered[$style]->deps as $dep){
              if(!in_array($dep, $this->in_head)){
                $unloaded_deps[] = $dep;
              }
            }
            $this->queue[] = array(
              'handle' => $style,
              'src' => preg_match('/^\/[^\/]/', $wp_styles->registered[$style]->src) ? $wp_styles->base_url . $wp_styles->registered[$style]->src : $wp_styles->registered[$style]->src,
              'deps' => $unloaded_deps
            );
          }
        }
      }

      if(!empty($wp_scripts->to_do)){
        foreach($wp_scripts->to_do as $script){
          if(!in_array($script, $wp_scripts->done)){
            $unloaded_deps = array();
            foreach($wp_scripts->registered[$script]->deps as $dep){
              if(!in_array($dep, $this->in_head)){
                $unloaded_deps[] = $dep;
              }
            }
            $this->queue[] = array(
              'handle' => $script,
              'src' => preg_match('/^\/[^\/]/', $wp_scripts->registered[$script]->src) ? $wp_scripts->base_url . $wp_scripts->registered[$script]->src : $wp_scripts->registered[$script]->src,
              'deps' => $unloaded_deps
            );
          }
        }
      }

    }

  }
}
