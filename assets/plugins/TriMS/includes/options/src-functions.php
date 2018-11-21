<?php
/**
 * SRC Functions
 *
 * @package  mm-dashboard-sharing
 * @developer  Maroun Melhem <http://maroun.me>
 * @version 2.0
 *
 */

/*Onesignal plugin files start*/
$trims_disable_onesignal = get_option('disable_onesignal');

$OneSignalWPSetting = get_option('OneSignalWPSetting');
$OneSignalWPSetting_app_id = $OneSignalWPSetting['app_id'];
$OneSignalWPSetting_rest_api_key = $OneSignalWPSetting['app_rest_api_key'];

if (!$trims_disable_onesignal) {
    if ($OneSignalWPSetting_app_id && $OneSignalWPSetting_rest_api_key) {
        add_action('admin_menu', 'notifications_admin_main_menu_scr');
        include(plugin_dir_path(__FILE__) . 'options_files/onesignal/admin-menu.php');
    }
}
/*Onesignal plugin files end*/


/*Mourning theme start*/
$trims_mourning_theme = get_option('enable_trims_mourning_theme');
if ($trims_mourning_theme) {
    add_filter('body_class', 'trims_mourning_theme_class');
    function trims_mourning_theme_class($classes)
    {
        $classes[] = 'black_n_white';
        return $classes;
    }

    function TriTheme_mourning_styles()
    {
        wp_enqueue_style('trims_mourning_options_src_css', plugins_url('src/css/mourning.css', __FILE__), array(), '1.0');
    }

    add_action('wp_enqueue_scripts', 'TriTheme_mourning_styles');
}
/*Mourning theme end*/

/*Arabic theme start*/
$trims_arabic_theme = get_option('enable_arabic_theme');
if ($trims_arabic_theme) {

    function TriMS_arabic_theme_files()
    {
        wp_enqueue_style('arabic_theme-css', plugins_url('options_files/arabic_theme/css/arabic_theme.css', __FILE__), array(), '1.0');
        wp_enqueue_script('arabic_theme-js', plugins_url("options_files/arabic_theme/js/arabic_theme.js", __FILE__), array('jquery'), '1.0', true);
    }
    add_action('admin_enqueue_scripts', 'TriMS_arabic_theme_files');

}
/*Arabic theme end*/

/*Including SRC files start*/
function trims_options_src_files()
{
    wp_enqueue_style('trims_options_src_css', plugins_url('src/css/styles.css', __FILE__), array(), '1.0');
    wp_enqueue_style('trims_options_src_switchery_css', plugins_url('src/libraries/switchery/switchery.min.css', __FILE__), array(), '1.0');
    wp_enqueue_script('trims_options_src_switchery_js', plugins_url("src/libraries/switchery/switchery.min.js", __FILE__), array('jquery'), '1.0', true);
    wp_enqueue_script('trims_options_src_js', plugins_url("src/js/scripts.js", __FILE__), array('jquery'), '1.0', true);

    $trims_chat_disable = get_option('disable_trims_chat');
    $trims_chat_dev = get_option('trims_chat_dev');

    if (!$trims_chat_disable) {
        if ($trims_chat_dev) {
            wp_enqueue_script('TriMS_zendesk_chat-js', plugins_url("options_files/zendesk_chat/" . $trims_chat_dev . "/zendesk_chat.js", __FILE__), array('jquery'), '1.0', true);
        }
    }

//    $current_user = wp_get_current_user();
//    $user_id = $current_user->id;
//    if ($user_id != 1 && current_user_can('editor')) {
//        wp_enqueue_script('TriMS-menu_editor_customizer-js', plugins_url("options_files/menu_editor/menu_editor_customizer.js", __FILE__), array('jquery'), '1.0');
//    }
}

add_action('admin_enqueue_scripts', 'trims_options_src_files');
/*Including SRC files end*/