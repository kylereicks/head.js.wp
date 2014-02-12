<?php
if(!class_exists('View_Head_JS_WP')){
  class View_Head_JS_WP{

    public static function do_items($model){
      global $wp_scripts;

      foreach($model->queue as $handle => $src){
        $wp_scripts->print_extra_script($handle);
      }

      $wp_scripts->do_item('head-load');

      self::render_template('inline-script', array('model' => $model));
    }

    private static function render_template($template, $template_data = array()){
      $template_path = apply_filters('head_js_wp_template_path', HEAD_JS_WP_PATH . 'inc/templates/');
      $template_file_path = apply_filters('head_js_wp_' . $template . '_template_file_path', $template_path . $template . '-template.php', $template, $template_path);
      $template_data = apply_filters('head_js_wp_' . $template . '_template_data', $template_data);
      if(!empty($template_data)){
        extract($template_data);
      }
      ob_start();
      include($template_file_path);
      echo apply_filters('head_js_wp_' . $template . '_template', ob_get_clean());
    }
 
  }
}
