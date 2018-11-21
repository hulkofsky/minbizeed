<?php
add_action("wp_ajax_update_country", "update_country");
function update_country()
{
    if (!wp_verify_nonce($_REQUEST['update_country_nonce'], "update_country")) {
        exit("You think you are smart?");
    }

    $current_user = wp_get_current_user();
    $user_id = $current_user->ID;

    $country = strip_tags($_REQUEST['update_country_c']);
    $city = strip_tags($_REQUEST['update_city_c']);
    $state = strip_tags($_REQUEST['update_state_c']);
    $address = strip_tags(nl2br($_REQUEST['update_address_c']), '<br />');

    $country_update = update_user_meta($user_id, '_country_', $country);
    $country_city = update_user_meta($user_id, '_city_', $city);
    $country_state = update_user_meta($user_id, '_state_', $state);
    $country_address = update_user_meta($user_id, '_address_', $address);

    if ($country_update || $country_city || $country_state || $country_address) {
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