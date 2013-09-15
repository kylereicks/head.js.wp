<?php
/*
Plugin Name: Head.js.wp
Plugin URI: http://github.com/kylereicks/head.js.wp
Description: A plugin to load footer scripts with head.js
Author: Kyle Reicks
Version: 1.0
Author URI: http://github.com/kylereicks/
*/

define('HEAD_JS_WP_PATH', plugin_dir_path(__FILE__));
define('HEAD_JS_WP_URL', plugins_url('/', __FILE__));
define('HEAD_JS_WP_VERSION', '1.0.0');
define('HEAD_JS_VERSION', '0.99');

require_once(HEAD_JS_WP_PATH . 'inc/class-head-js-wp.php');

register_deactivation_hook(__FILE__, array('Head_js_wp', 'deactivate'));

Head_js_wp::get_instance();
