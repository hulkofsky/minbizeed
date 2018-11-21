<?php
add_action("wp_ajax_update_personal_info", "update_personal_info");
function update_personal_info()
{
    if (!wp_verify_nonce($_REQUEST['update_personal_info_nonce'], "update_personal_info")) {
        exit("You think you are smart?");
    }

    $current_user = wp_get_current_user();
    $user_id = $current_user->ID;

    $orig_user_fname = $current_user->first_name;
    $orig_user_lname = $current_user->last_name;
    $orig_user_email = $current_user->user_email;
    $orig_user_phone = get_user_meta($user_id, '_phonenumber_', true);

    $fname = strip_tags($_REQUEST['fname']);
    $lname = strip_tags($_REQUEST['lname']);
    $email = strip_tags($_REQUEST['email']);
    $phone_number = strip_tags($_REQUEST['phone']);

    if (($fname == $orig_user_fname) && ($lname == $orig_user_lname) && ($email == $orig_user_email) && ($phone_number == $orig_user_phone)) {
        $result['type'] = "same_error";
    } else {
        if (is_email($email)) {

            $first_name_update = wp_update_user(array('ID' => $user_id, 'first_name' => $fname));
            $last_name_update = wp_update_user(array('ID' => $user_id, 'last_name' => $lname));
            $email_update = wp_update_user(array('ID' => $user_id, 'user_email' => $email));
            $phone_number_update = update_user_meta($user_id, '_phonenumber_', $phone_number);

            if ($first_name_update || $last_name_update || $email_update || $phone_number_update) {
                $result['type'] = "success";
            } else {
                $result['type'] = "error";
            }

        } else {
            $result['type'] = "email_error";
        }
    }

    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        $result = json_encode($result);
        echo $result;
    } else {
        header("Location: " . $_SERVER["HTTP_REFERER"]);
    }
    die();
}