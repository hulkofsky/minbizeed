<?php
/**
 * @package TriMS
 * @version 1.0
 */
/*
  Plugin Name: TriMS
  Plugin URI: http://trianglemena.com
  Description: TriMS is a complete makeover of the Wordpress Dashboard; By <a href="http://trianglemena.com" target="_blank">Triangle Mena</a> Devs.
  Version: 1.10
  Author: Maroun Melhem
  Author URI: http://maroun.me
 */

/*Includes start*/

/*Backend styles start*/
include(plugin_dir_path(__FILE__) . 'includes/backend-styles/backend-styles.php');
/*Backend styles end*/

/*Backend assets start*/
include(plugin_dir_path(__FILE__) . 'includes/backend-assets/backend-assets.php');
/*Backend assets end*/

/*Plugin options start*/
include(plugin_dir_path(__FILE__) . 'includes/options/options-functions.php');
/*Plugin options end*/

/*Dashboard widgets start*/
include(plugin_dir_path(__FILE__) . 'includes/dashboard-widgets/dashboard-widgets.php');
/*Dashboard widgets end*/

/*Dashboard widgets start*/
include(plugin_dir_path(__FILE__) . 'includes/shortcodes/videos.php');
/*Dashboard widgets end*/

/*Backend assets start*/
//include(plugin_dir_path(__FILE__) . 'includes/roles/roles.php');
/*Backend assets end*/

/*Includes end*/

/*Adding page to submenu start*/
//function users_menu_editor_register_options_page()
//{
//    add_menu_page("Users Menu Editor", "Users Menu Editor", "manage_options", "/admin.php?page=menu_editor#role:editor", "", "dashicons-admin-network", null, 99);
//}
//add_action("admin_menu", "users_menu_editor_register_options_page");
/*Adding page to submenu end*/