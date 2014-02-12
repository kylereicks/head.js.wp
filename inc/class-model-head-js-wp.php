<?php
if(!class_exists('Model_Head_JS_WP')){
  class Model_Head_JS_WP{

    public $queue = array();

    public function __construct(){
      global $wp_scripts, $wp_styles;

      if(!empty($wp_scripts->queue)){
        $wp_scripts->all_deps($wp_scripts->queue);
      }

      if(!empty($wp_styles->queue)){
        $wp_styles->all_deps($wp_styles->queue);
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

    private function set_queue(){
      global $wp_scripts, $wp_styles;

      if(!empty($wp_styles->to_do)){
        foreach($wp_styles->to_do as $style){
          if(!in_array($style, $wp_styles->done)){
            $this->queue[] = array(
              'handle' => $style,
              'src' => preg_match('/^\/[^\/]/', $wp_styles->registered[$style]->src) ? $wp_styles->base_url . $wp_styles->registered[$style]->src : $wp_styles->registered[$style]->src
            );
          }
        }
      }

      if(!empty($wp_scripts->to_do)){
        foreach($wp_scripts->to_do as $script){
          if(!in_array($script, $wp_scripts->done)){
            $this->queue[] = array(
              'handle' => $script,
              'src' => preg_match('/^\/[^\/]/', $wp_scripts->registered[$script]->src) ? $wp_scripts->base_url . $wp_scripts->registered[$script]->src : $wp_scripts->registered[$script]->src
            );
          }
        }
      }

    }

  }
}
