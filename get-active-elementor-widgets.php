<?php

/**
 * Plugin Name: Get active elementor widgets
 * Description: Shows the widgets are currently used by elementor
 * Version: 0.1
 * Author: Konstantin
 * Author URI: https://kskonovalov.me
 * Text Domain: gaew
 */

if ( ! defined( 'ABSPATH' ) ) exit;
define('GAEW_URL', plugin_dir_url(__FILE__));

add_action('admin_menu', 'gaew_get_page');
function gaew_get_page()
{
    add_submenu_page(
        'tools.php',
        'Get active elementor widgets',
        'Elementor widgets',
        'manage_options',
        'get-active-elementor-widgets',
        'gaew_main_func'
    );
}

//settings page
function gaew_main_func()
{
    // output
    ?>
  <div class="wrap">
    <h2>Hi there</h2>

    <?php
    // Get Registered widgets
    $registered = \Elementor\Plugin::instance()->widgets_manager->ajax_get_widget_types_controls_config([]);
    VAR_DUMP(array_keys($registered));

    // get post meta
    $postID = 15368;
    $elementorData = get_post_meta($postID, '_elementor_data', true);
    if(!empty($elementorData)) {
      $elementorJson = json_decode($elementorData, true);

      $widgets = [];
      array_walk_recursive($elementorJson, function ($value, $key) use (&$widgets) {
          if ($key === 'widgetType') {
              $widgets[] = $value;
          }
      });

      VAR_DUMP($widgets);
    }
    ?>
  </div>
    <?php
}
