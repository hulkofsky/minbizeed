<?php
add_action("wp_ajax_update_shpg_info", "update_shpg_info");
add_action("wp_ajax_nopriv_update_shpg_info", "update_shpg_info");
function update_shpg_info()
{
    if (!wp_verify_nonce($_REQUEST['nonce'], "update_shpg_info")) {
        exit("You think you are smart?");
    }

    $city = $_REQUEST['city'];
    $state = $_REQUEST['state'];
    $address = $_REQUEST['address'];
    $uid = $_REQUEST['user_id'];

    $city = strip_tags($city);
    $state = strip_tags($state);
    $address = strip_tags(nl2br($address), '<br />');

    /*php actions goes here*/

    $update_city = update_user_meta($uid, 'city', $city);
    $update_state = update_user_meta($uid, 'state', $state);
    $update_address = update_user_meta($uid, 'ship_inf', $address);

    if ($update_city || $update_state || $update_address) {
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