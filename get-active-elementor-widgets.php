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
    <p>
      I hope it works
    </p>
  </div>
    <?php
}
