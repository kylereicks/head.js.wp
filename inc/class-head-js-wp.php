<?php
if(!class_exists('Head_JS_WP')){
  class Head_JS_WP{

    private $model;

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
      self::clear_options();
    }

    private static function clear_options(){
      global $wpdb;
      $options = $wpdb->get_col('SELECT option_name FROM ' . $wpdb->options . ' WHERE option_name LIKE \'%head_js_wp%\'');
      foreach($options as $option){
        delete_option($option);
      }
    }

    private function __construct(){
      add_action('init', array($this, 'add_update_hook'));
      add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
      add_action('wp_print_footer_scripts', array($this, 'print_footer_scripts'), 9);

      include_once(HEAD_JS_WP_PATH . 'inc/class-shortcodes-head-js-wp.php');
      Shortcodes_Head_JS_WP::add_shortcodes();
    }

    public function add_update_hook(){
      if(get_option('head_js_wp_version') !== HEAD_JS_WP_VERSION){
        update_option('head_js_wp_update_timestamp', time());
        update_option('head_js_wp_version', HEAD_JS_WP_VERSION);
        do_action('head_js_wp_updated');
      }
    }

    public function enqueue_scripts(){
      wp_register_script('head-load', HEAD_JS_WP_URL . 'js/libs/head.load.min.js', array(), HEAD_JS_VERSION, true);
    }

    public function print_footer_scripts(){
      include_once(HEAD_JS_WP_PATH . 'inc/class-model-head-js-wp.php');

      $this->model = new Model_Head_JS_WP();

      if(!empty($this->model->queue)){
        include_once(HEAD_JS_WP_PATH . 'inc/class-view-head-js-wp.php');

        View_Head_JS_WP::do_items($this->model);
      }
    }
  }
}
