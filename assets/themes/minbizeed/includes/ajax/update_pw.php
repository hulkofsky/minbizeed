<?php
add_action("wp_ajax_update_pw", "update_pw");
function update_pw()
{
    if (!wp_verify_nonce($_REQUEST['update_pw_nonce'], "update_pw")) {
        exit("You think you are smart?");
    }

    $current_user = wp_get_current_user();
    $user_id = $current_user->ID;
    $user_pw = $current_user->user_pass;

    $org_pw = strip_tags($_REQUEST['pw_org']);
    $pw_1 = strip_tags($_REQUEST['pw_1']);
    $pw_2 = strip_tags($_REQUEST['pw_2']);

    if ($org_pw && $pw_1 && $pw_2) {
        if (wp_check_password($org_pw, $user_pw, $user_id)) {

            if ($pw_1 != $pw_2) {

                $result['type'] = "match_error";
            } else {

                if(strlen($pw_2)<6){

                    $result['type'] = "length_error";

                }else{
                    wp_set_password($pw_2, $user_id);

                    $result['type'] = "success";
                }

            }

        } else {
            $result['type'] = "access_error";
        }
    } else {
        $result['type'] = "enter_error";
    }

    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        $result = json_encode($result);
        echo $result;
    } else {
        header("Location: " . $_SERVER["HTTP_REFERER"]);
    }
    die();
}