<?php
/*
Plugin Name: Head.js.wp
Plugin URI: http://github.com/kylereicks/head.js.wp
Description: A plugin to load footer scripts with head.js
Author: Kyle Reicks
Version: 0.0
Author URI: http://github.com/kylereicks/
*/

if(!class_exists('Head_js_wp')){
  class Head_js_wp{

    function __construct(){
      add_action('wp_enqueue_scripts', array($this, 'add_header_js_to_head'));
      remove_action('wp_print_footer_scripts', '_wp_footer_scripts');
      add_action('wp_print_footer_scripts', array($this, 'print_scripts_with_header_js'));
    }

    function add_header_js_to_head(){

      if(!wp_script_is('head-js')){
        wp_register_script('head-js', plugins_url('/js/libs/head.load.js', __FILE__));
      }

    }

    function print_scripts_with_header_js(){
      global $wp_scripts;

      print_late_styles();

      $wp_scripts->all_deps($wp_scripts->queue);

      if(!empty($wp_scripts->to_do)){
        $wp_scripts->do_item('head-js');

        foreach($wp_scripts->to_do as $handle){
          $wp_scripts->print_extra_script($handle);
        }
        
        echo '<script>' . "\n" . 'head.js({';
        foreach($wp_scripts->to_do as $script){
          $ending = (count($wp_scripts->to_do) === 1) ? ' });' : " },\n{";
          $version = ($wp_scripts->registered[$script]->ver) ? '?ver=' . $wp_scripts->registered[$script]->ver : "";
          $base_url = (preg_match('/^\//', $wp_scripts->registered[$script]->src)) ? $wp_scripts->base_url : '';
          echo str_replace('-', '_', $script) . " : '" . $base_url . $wp_scripts->registered[$script]->src . $version . "'" . $ending;
          $wp_scripts->done[] = $script;
          $wp_scripts->to_do = array_merge(array_diff($wp_scripts->to_do, array($script)));
        }
        echo '</script>' . "\n";
      }

      return true;
    }
  }/* End class Head_js_wp */
  $head_js_wp = new Head_js_wp();
}
/* End file head-js-wp.php */
