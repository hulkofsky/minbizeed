<?php
/**
 * SRC Functions
 *
 * @package  mm-dashboard-sharing
 * @developer  Maroun Melhem <http://maroun.me>
 * @version 2.11
 *
 */

/*Including SRC files start*/
function mm_dashboard_sharing_src_files()
{
    wp_enqueue_style('mm_dashboard_sharing_src_css', plugins_url('css/styles.css', __FILE__), array(), '1.0');
    wp_enqueue_style('mm_dashboard_sharing_src_switchery_css', plugins_url('libraries/switchery/switchery.min.css', __FILE__), array(), '1.0');
    wp_enqueue_script('mm_dashboard_sharing_src_switchery_js', plugins_url("libraries/switchery/switchery.min.js", __FILE__), array('jquery'), '1.0',true);
    wp_enqueue_script('mm_dashboard_sharing_src_js', plugins_url("js/scripts.js", __FILE__), array('jquery'), '1.0',true);
    $plugin_obj = array(
        'plugin_url' => WP_PLUGIN_URL . '/mm-dashboard-sharing'
    );
    wp_localize_script('mm_dashboard_sharing_src_js', 'plugin_obj', $plugin_obj);
}

add_action('admin_enqueue_scripts', 'mm_dashboard_sharing_src_files');
/*Including SRC files end*/
