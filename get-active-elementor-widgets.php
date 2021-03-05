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
//    $wm = new Widgets_Manager();

    // Register widget
    VAR_DUMP(\Elementor\Plugin::instance()->widgets_manager->ajax_get_widget_types_controls_config([]));

//    add_action( 'elementor/widgets/widgets_registered', function( $widgets_manager ) {
//        $res = $widgets_manager->get_widget_types();
//        die(VAR_DUMP($res));
//    }, 999 );
    ?>
  </div>
    <?php
}
