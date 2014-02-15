<?php
if(!class_exists('Shortcodes_Head_JS_WP')){
  class Shortcodes_Head_JS_WP{

    public static function add_shortcodes(){
      if(!shortcode_exists('enqueue_style')){
        add_shortcode('enqueue_style', array('Shortcodes_Head_JS_WP', 'shortcode_enqueue_style'));
      }
      if(!shortcode_exists('enqueue_script')){
        add_shortcode('enqueue_script', array('Shortcodes_Head_JS_WP', 'shortcode_enqueue_script'));
      }
    }


    public static function shortcode_enqueue_style($atts){
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

    public static function shortcode_enqueue_script($atts){
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

  }
}
