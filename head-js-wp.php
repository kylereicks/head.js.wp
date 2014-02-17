<?php
/*
Plugin Name: Head.js.wp
Plugin URI: http://github.com/kylereicks/head.js.wp
Description: A plugin to load footer scripts with head.js
Author: Kyle Reicks
Version: 2.0.3
Author URI: http://github.com/kylereicks/
*/

define('HEAD_JS_WP_PATH', plugin_dir_path(__FILE__));
define('HEAD_JS_WP_URL', plugins_url('/', __FILE__));
define('HEAD_JS_WP_VERSION', '2.0.3');
define('HEAD_JS_VERSION', '1.0.3');

require_once(HEAD_JS_WP_PATH . 'inc/class-head-js-wp.php');

register_deactivation_hook(__FILE__, array('Head_js_wp', 'deactivate'));

Head_js_wp::get_instance();
