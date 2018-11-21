<?php
/**
 * Orders
 *
 * @package  MinBizeed
 * @company  Triangle Mena <http://trianglemena.com>
 * @developer  Maroun Melhem <http://maroun.me>
 *
 */
add_action("wp_ajax_mark_paid_action", "mark_paid_action");
function mark_paid_action()
{
    if (!wp_verify_nonce($_REQUEST['ajax_nonce_1'], "mark_paid_action")) {
        exit("You think you are smart?");
    }

    $post_id_1 = $_REQUEST['post_id_1'];

    date_default_timezone_set('Asia/Beirut');
    $date_time = date('m/d/Y h:i:s a', time());

    $update_1_1 = update_post_meta($post_id_1, 'winner_paid', 1);
    $update_1_2 = update_post_meta($post_id_1, 'paid_on', $date_time);

    if ($update_1_1 && $update_1_2) {

        $result['type'] = "success";

    } else {

        $result['type'] = "error";

    }
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        $result = json_encode($result);
        echo $result;
    } else {
        header("Location: " . $_SERVER["HTTP_REFERER"]);
    }
    die();
}

add_action("wp_ajax_mark_shipped_action", "mark_shipped_action");
function mark_shipped_action()
{
    if (!wp_verify_nonce($_REQUEST['ajax_nonce_2'], "mark_shipped_action")) {
        exit("You think you are smart?");
    }

    $post_id_2 = $_REQUEST['post_id_2'];

    date_default_timezone_set('Asia/Beirut');
    $date_time_2 = date('m/d/Y h:i:s a', time());

    $update_2_1 = update_post_meta($post_id_2, 'shipped', 1);
    $update_2_2 = update_post_meta($post_id_2, 'shipped_on', $date_time_2);

    if ($update_2_1 && $update_2_2) {

        $result['type'] = "success";

    } else {

        $result['type'] = "error";

    }
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        $result = json_encode($result);
        echo $result;
    } else {
        header("Location: " . $_SERVER["HTTP_REFERER"]);
    }
    die();
}
