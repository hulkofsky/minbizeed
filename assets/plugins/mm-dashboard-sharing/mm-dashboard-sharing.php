<?php
/*
  Plugin Name: MM Dashboard Sharing
  Plugin URI: http://maroun.me/plugins/mm-dashboard-sharing
  Description: Simple way to add the ability to share posts directly from the dashboard panel.
  Version: 2.11
  Author: Maroun Melhem
  Author URI: http://maroun.me
 */

if (!defined('ABSPATH')) {
    exit;
}

/*Includes start*/

/*Main functions start*/
include( plugin_dir_path( __FILE__ ) . 'includes/main/main-functions.php');
/*Main functions end*/

/*Options functions start*/
include( plugin_dir_path( __FILE__ ) . 'includes/options/options-functions.php');
/*Options functions end*/

/*SRC functions start*/
include( plugin_dir_path( __FILE__ ) . 'includes/src/src-functions.php');
/*SRC functions end*/

/*Includes end*/
