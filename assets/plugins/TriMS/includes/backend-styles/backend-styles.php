<?php
/**
 * Backend Styles
 *
 * @package  TriMS
 * @company  Triangle Mena <http://trianglemena.com>
 * @developer  Maroun Melhem <http://maroun.me>
 * @version 1.0
 *
 */
include(plugin_dir_path(__FILE__) . 'custom/css/custom-styles.php');
include(plugin_dir_path(__FILE__) . 'custom/css/login-styles.php');

function TriMS_files()
{
    wp_enqueue_style('fa-css', plugins_url('css/fa/css/font-awesome.min.css', __FILE__), array(), '1.0');
    wp_enqueue_style('TriMS-css', plugins_url('css/styles.css', __FILE__), array(), '1.21');
    wp_enqueue_script('simpleWeather-js', plugins_url("js/jquery.simpleWeather.min.js", __FILE__), array('jquery'), '1.0', true);
    wp_enqueue_script('TriMS-js', plugins_url("js/scripts.js", __FILE__), array('jquery'), '1.14', true);
    $website_url = array(
        'template_images_url' => WP_PLUGIN_URL . '/TriMS/includes/backend-styles/custom/images/',
        'template_files_url' => WP_PLUGIN_URL . '/TriMS/includes/backend-styles/custom/files/',
        'site_url' => get_site_url(),

    );
    wp_localize_script('TriMS-js', 'website_url', $website_url);
}

add_action('admin_enqueue_scripts', 'TriMS_files');
add_action('login_enqueue_scripts', 'TriMS_files');

function TriMS_add_editor_styles()
{
    add_editor_style(plugins_url('css/editor-style.css', __FILE__));
}

add_action('after_setup_theme', 'TriMS_add_editor_styles');


function TriMS_admin_footer_text_output($text)
{
    $text = '<i id="wp_notice">Built with <i class="fa fa-heart"></i> using <a href="http://php.net" target="_blank" title="php">php</a> && <a href="http://wordpress.org" target="_blank" title="Open Sourced WordPress CMS"><i class="fa fa-wordpress" aria-hidden="true"></i></a> - by <a href="http://trianglemena.com/" target="_blank">Triangle Mena</a></i>';
    return $text;
}

add_filter('admin_footer_text', 'TriMS_admin_footer_text_output');


function TriMS_post_state($post_states)
{
    if (!empty($post_states)) {
        $state_count = count($post_states);
        $i = 0;
        foreach ($post_states as $state) {
            ++$i;
            ($i == $state_count) ? $sep = '' : $sep = '';
            echo "<span class='post-state'>$state$sep</span>";
        }
    }
}

add_filter('display_post_states', 'TriMS_post_state');