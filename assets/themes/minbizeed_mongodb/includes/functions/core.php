<?php
/**
 * Core functions
 *
 * @package  MinBizeed
 * @company  Triangle Mena <http://trianglemena.com>
 * @developer  Maroun Melhem <http://maroun.me>
 *
 */
add_action('template_redirect', 'minbizeed_template_redirect');
function minbizeed_template_redirect()
{

    global $wp;
    global $wp_query, $wp_rewrite, $post;
    $a_action = $wp_query->query_vars['a_action'];

    if ($a_action == "buy_now") {
        include 'lib/buy_now.php';
        exit;
    }


    if ($a_action == "purchase_bid") {
        include 'lib/gateways/purchase_bid_paypal.php';
        exit;
    }

    if ($a_action == "pay_for_auction") {
        include 'lib/gateways/pay_paypal_auction.php';
        exit;
    }
}

function minbizeed_generate_thumb($img_url, $width, $height, $cut = true)
{


    require_once(ABSPATH . '/wp-admin/includes/image.php');
    $uploads = wp_upload_dir();
    $basedir = $uploads['basedir'] . '/';
    $exp = explode('/', $img_url);

    $nr = count($exp);
    $pic = $exp[$nr - 1];
    $year = $exp[$nr - 3];
    $month = $exp[$nr - 2];

    if ($uploads['basedir'] == $uploads['path']) {
        $img_url = $basedir . '/' . $pic;
        $ba = $basedir . '/';
        $iii = $uploads['url'];
    } else {
        $img_url = $basedir . $year . '/' . $month . '/' . $pic;
        $ba = $basedir . $year . '/' . $month . '/';
        $iii = $uploads['baseurl'] . "/" . $year . "/" . $month;
    }
    list($width1, $height1, $type1, $attr1) = getimagesize($img_url);

//return $height;
    $a = false;
    if ($width == -1) {
        $a = true;
    }


    if ($width > $width1)
        $width = $width1 - 1;
    if ($height > $height1)
        $height = $height1 - 1;

    if ($a == true) {
        $prop = $width1 / $height1;
        $width = round($prop * $height);
    }

    $width = $width - 1;
    $height = $height - 1;


    $xxo = "-" . $width . "x" . $height;
    $exp = explode(".", $pic);
    $new_name = $exp[0] . $xxo . "." . $exp[1];

    $tgh = str_replace("//", "/", $ba . $new_name);

    if (file_exists($tgh))
        return $iii . "/" . $new_name;


    $thumb = image_resize($img_url, $width, $height, $cut);

//    if (is_wp_error($thumb))
//        return "is-wp-error";

    $exp = explode($basedir, $thumb);
    return $uploads['baseurl'] . "/" . $exp[1];
}

function minbizeed_insert_pages($page_ids, $page_title, $page_tag, $parent_pg = 0)
{
    $opt = get_option($page_ids);
    if (!minbizeed_check_if_page_existed($opt)) {

        $post = array(
            'post_title' => $page_title,
            'post_content' => $page_tag,
            'post_status' => 'publish',
            'post_type' => 'page',
            'post_author' => 1,
            'ping_status' => 'closed',
            'post_parent' => $parent_pg);

        $post_id = wp_insert_post($post);

        update_post_meta($post_id, '_wp_page_template', 'penny-special-page-template.php');
        update_option($page_ids, $post_id);
    }
}

function minbizeed_check_if_page_existed($pid)
{
    global $wpdb;
    $s = "select * from " . $wpdb->prefix . "posts where post_type='page' AND post_status='publish' AND ID='$pid'";
    $r = $wpdb->get_results($s);

    if (count($r) > 0)
        return true;
    return false;
}

function minbizeed_is_home()
{
    global $current_user, $wp_query;
    $a_action = $wp_query->query_vars['a_action'];

    if (!empty($a_action))
        return false;
    if (is_home())
        return true;
    return false;
}

function get_gravatar_url( $email ) {
    $hash = md5(strtolower(trim ($email)));
    return 'http://gravatar.com/avatar/' . $hash.'?s=400';
}

