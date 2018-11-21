<?php
/**
 * Add videos shortcodes
 *
 * @package  Rotana
 * @company  Triangle Mena <http://trianglemena.com>
 * @developer  Maroun Melhem <http://maroun.me>
 *
 */


//VOD shortcode function
function display_vod_video($vod_atts)
{
    extract(shortcode_atts(array(
        'id' => 1,
        'autoplay' => 0,
    ), $vod_atts));
    $id_trimmed = str_replace(' ', '', $id);
    $mobile_mute = 1;

    if ($autoplay) {
        $autoplay_val = 0;
    } else {
        $autoplay_val = 0;
    }

    $vod_video_iframe = '';
    $vod_video_iframe .= '<div class="vod_platform_video added_from_shortcode">';
    $vod_video_iframe .= '<iframe scrolling="no" src="http://vod-platform.net/Embed/';
    $vod_video_iframe .= $id_trimmed;
    $vod_video_iframe .= '?mute=';
    $vod_video_iframe .= $mobile_mute;
    $vod_video_iframe .= '&autoplay=';
    $vod_video_iframe .= $autoplay_val;
    $vod_video_iframe .= '" class="muted_video" frameborder="0" id="JWpFrame" allowfullscreen>';
    $vod_video_iframe .= '</iframe>';
    $vod_video_iframe .= '<style type="text/css">';
    $vod_video_iframe .= '.vod_platform_video.added_from_shortcode{';
    $vod_video_iframe .= 'position: relative;';
    $vod_video_iframe .= 'padding-bottom: 56%;';
    $vod_video_iframe .= 'padding-top: 20%;';
    $vod_video_iframe .= 'height: 0;';
    $vod_video_iframe .= '}';
    $vod_video_iframe .= '.vod_platform_video.added_from_shortcode iframe,.vod_platform_video.added_from_shortcode object,.vod_platform_video.added_from_shortcode embed{';
    $vod_video_iframe .= 'position: absolute;';
    $vod_video_iframe .= 'top: 0;';
    $vod_video_iframe .= 'left: 0;';
    $vod_video_iframe .= 'width: 100%;';
    $vod_video_iframe .= 'height: 100%;';
    $vod_video_iframe .= '}';
    $vod_video_iframe .= '</style>';
    $vod_video_iframe .= '</div>';
    return $vod_video_iframe;
}

//youtube shortcode function
function display_youtube_video($youtube_atts)
{
    extract(shortcode_atts(array(
        'id' => 1,
    ), $youtube_atts));
    $id_trimmed = str_replace(' ', '', $id);
    $youtube_video_iframe = '<div class="youtube_video added_from_shortcode">';
    $youtube_video_iframe .= '<iframe width="560" height="315" src="https://www.youtube.com/embed/';
    $youtube_video_iframe .= $id_trimmed;
    $youtube_video_iframe .= '?autoplay=1';
    $youtube_video_iframe .= '  frameborder="0" allowfullscreen>';
    $youtube_video_iframe .= '</iframe>';
    $youtube_video_iframe .= '<style type="text/css">';
    $youtube_video_iframe .= '.youtube_video.added_from_shortcode{';
    $youtube_video_iframe .= 'position: relative;';
    $youtube_video_iframe .= 'padding-bottom: 56%;';
    $youtube_video_iframe .= 'padding-top: 20%;';
    $youtube_video_iframe .= 'height: 0;';
    $youtube_video_iframe .= '}';
    $youtube_video_iframe .= '.youtube_video.added_from_shortcode iframe,.youtube_video.added_from_shortcode object,.youtube_video.added_from_shortcode embed{';
    $youtube_video_iframe .= 'position: absolute;';
    $youtube_video_iframe .= 'top: 0;';
    $youtube_video_iframe .= 'left: 0;';
    $youtube_video_iframe .= 'width: 100%;';
    $youtube_video_iframe .= 'height: 100%;';
    $youtube_video_iframe .= '}';
    $youtube_video_iframe .= '</style>';
    $youtube_video_iframe .= '</div>';
    return $youtube_video_iframe;
}

function register_shortcodes()
{
    add_shortcode('vod_video', 'display_vod_video');
    add_shortcode('youtube_video', 'display_youtube_video');
}

add_action('init', 'register_shortcodes');


function register_button($buttons)
{
    array_push($buttons, "|", "vod_video");
    array_push($buttons, "|", "youtube_video");
    return $buttons;
}

function add_plugin($plugin_array)
{
    $plugin_array['vod_video'] = plugins_url('js/vod_video.js', __FILE__);
    $plugin_array['youtube_video'] = plugins_url('js/youtube_video.js', __FILE__);
    return $plugin_array;
}

function vod_video_button()
{

    if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
        return;
    }

    if (get_user_option('rich_editing') == 'true') {
        add_filter('mce_external_plugins', 'add_plugin');
        add_filter('mce_buttons', 'register_button');
    }
}

add_action('init', 'vod_video_button');

function youtube_video_button()
{

    if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
        return;
    }

    if (get_user_option('rich_editing') == 'true') {
        add_filter('mce_external_plugins', 'add_plugin');
        add_filter('mce_buttons', 'register_button');
    }
}

add_action('init', 'youtube_video_button');