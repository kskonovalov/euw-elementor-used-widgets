<?php

/**
 * Plugin Name: Get active elementor widgets
 * Description: Shows the widgets are currently used by elementor
 * Version: 0.0.1
 * Author: Konstantin
 * Author URI: https://kskonovalov.me
 * Text Domain: gaew
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}
define( 'GAEW_URL', plugin_dir_url( __FILE__ ) );

add_action( 'admin_menu', 'gaew_get_page' );
function gaew_get_page() {
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
function gaew_main_func() {
  // output
  ?>
    <div class="wrap">
        <h2>Used / unused elementor widgets</h2>
      <?php
      $otherCategoryName = "other";

      // Get Registered widgets
      $registeredWidgetsData = \Elementor\Plugin::instance()->widgets_manager->get_widget_types_config( [] );
      //    VAR_DUMP($registeredWidgetsData);
      $registeredWidgets = [];
      foreach ( $registeredWidgetsData as $widgetID => $widgetFields ) {
        if ( isset( $widgetFields["categories"] ) && is_array( $widgetFields["categories"] ) ) {
          foreach ( $widgetFields["categories"] as $category ) {
            $registeredWidgets[ $category ][] = [
              "id"    => $widgetID,
              "title" => $widgetFields["title"]
            ];
          }
        } else {
          $registeredWidgets[ $otherCategoryName ][] = $widgetID;
        }
      }
      //      VAR_DUMP( $registeredWidgets );
      //    $registeredWidgets = array_keys($registeredWidgetsData);

      // get post meta
      // TODO: for all pages
      // параметры по умолчанию
      $pages = get_posts( array(
        'numberposts'      => - 1,
        'category'         => 0,
        'orderby'          => 'date',
        'order'            => 'DESC',
        'post_type'        => 'any',
        'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
      ) );

      $usedWidgets       = [];
      $usedWidgetsByPage = [];
      foreach ( $pages as $page ) {
        $pageID        = $page->ID;
        $pageLink      = get_the_permalink( $page );
        $pageTitle     = $page->post_title;
        $pageType      = $page->post_type;
        $elementorData = get_post_meta( $pageID, '_elementor_data', true );
        if ( ! empty( $elementorData ) ) {
          $elementorJson = json_decode( $elementorData, true );
          array_walk_recursive( $elementorJson, static function ( $value, $key ) use ( &$usedWidgets, &$usedWidgetsByPage, $pageID, $pageLink, $pageTitle, $pageType ) {
            if ( $key === 'widgetType' ) {
              $usedWidgets[]                          = $value;
              $usedWidgetsByPage[ $value ][ $pageID ] = [
                "id"    => $pageID,
                "type"  => $pageType,
                "link"  => $pageLink,
                "title" => $pageTitle
              ];
            }
          } );
        }
      }

      //      VAR_DUMP($registeredWidgets, $usedWidgets);
      $usedColor   = "#b35082";
      $unusedColor = "#71b350";
      $usedIcon    = "&check;";
      $unusedIcon  = "&cross;";
      echo '<table cellspacing="0" cellpadding="0" class="widefat fixed" style="width: 600px; max-width: 100%;">';
      foreach ( $registeredWidgets as $categoryName => $category ) {
        echo "<thead><tr><th colspan='2' class='manage-column'>{$categoryName}</th><th class='manage-column'>Page</th></tr></thead><tbody>";
        foreach ( $category as $widget ) {
          if ( empty( $widget["id"] ) ) {
            // TODO: check skipped?
            continue;
          }

          $title = "";
          if ( ! empty( $widget["title"] ) ) {
            $title = $widget["title"];
          } else if ( ! empty( $widget["id"] ) ) {
            $title = $widget["id"];
          }
          if ( in_array( $widget["id"], $usedWidgets, true ) ) {
            $pages       = [];
            $statusColor = $usedColor;
            $statusIcon  = $usedIcon;
            if ( isset( $usedWidgetsByPage[ $widget["id"] ] ) && is_array( $usedWidgetsByPage[ $widget["id"] ] ) ) {
              foreach ( $usedWidgetsByPage[ $widget["id"] ] as $usedInPages ) {
                $pages[] = "<a href='{$usedInPages["link"]}' title='{$usedInPages["title"]}' target='_blank'>{$usedInPages["id"]}</a>";
              }
            }
            $pages = implode( ", ", $pages );
          } else {
            $pages       = "";
            $statusColor = $unusedColor;
            $statusIcon  = $unusedIcon;
          }


          echo "<tr><td style='color: {$statusColor}; width: 30px;'>{$statusIcon}</td><td style='color: {$statusColor};'>{$title}</td><td>{$pages}</td></tr>";
        }
      }
      echo '</tbody></table>';

      //    VAR_DUMP($usedWidgets);
      //    $diff = array_diff($registeredWidgets, $usedWidgets);
      //    VAR_DUMP($diff);
      ?>
    </div>
  <?php
}
