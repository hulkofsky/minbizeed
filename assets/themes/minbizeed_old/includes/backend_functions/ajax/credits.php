<?php
/**
 * Credits
 *
 * @package  MinBizeed
 * @company  Triangle Mena <http://trianglemena.com>
 * @developer  Maroun Melhem <http://maroun.me>
 *
 */
add_action("wp_ajax_credits_update", "credits_update");
function credits_update()
{
    if (!wp_verify_nonce($_REQUEST['nonce'], "credits_update")) {
        exit("You think you are smart?");
    }

    $uid = $_REQUEST['uid'];
    $increase_credits = $_REQUEST['increase_credits'];
    $decrease_credits = $_REQUEST['decrease_credits'];

    /*Logs vars start*/
    global $wpdb;
    date_default_timezone_set('Asia/Beirut');
    $date_time = date('m/d/Y h:i:s a', time());
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $user_ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $user_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $user_ip = $_SERVER['REMOTE_ADDR'];
    }

    global $current_user;
    get_currentuserinfo();
    $user_id = $current_user->ID;
    /*Logs vars end*/

    if (!empty($increase_credits)) {
        if (is_numeric($increase_credits)) {
            if ($increase_credits >= 0) {
                $cr = minbizeed_get_credits($uid);
                $calc = $cr + $increase_credits;
                update_user_meta($uid, 'user_credits', $calc);
                $result['type'] = "success";
                $result['html_success'] = "Credits successfully increased!";
                $result['total'] = minbizeed_get_credits($uid);

                /*Logs start*/
                $cr_after = minbizeed_get_credits($uid);
                $wpdb->insert($wpdb->prefix . 'bids_transfers', array(
                    'by_uid' => $user_id,
                    'to_uid' => $uid,
                    'credits_before' => $cr,
                    'amount' => '+'.$increase_credits,
                    'credits_after' => $cr_after,
                    'date' => $date_time,
                    'ip' => $user_ip,
                ));
                /*Logs end*/

            } else {
                $result['type'] = "error";
                $result['html_error'] = "ERROR, Enter a positive number";
            }
        } else {
            $result['type'] = "error";
            $result['html_error'] = "ERROR, Enter a number";
        }
    } else {
        if (is_numeric($decrease_credits)) {
            if ($decrease_credits >= 0) {
                $cr = minbizeed_get_credits($uid);
                $calc = $cr - $decrease_credits;
                if ($calc >= 0) {
                    update_user_meta($uid, 'user_credits', $calc);
                    $result['type'] = "success";
                    $result['html_success'] = "Credits successfully decreased!";
                    $result['total'] = minbizeed_get_credits($uid);

                    /*Logs start*/
                    $cr_after = minbizeed_get_credits($uid);
                    $wpdb->insert($wpdb->prefix . 'bids_transfers', array(
                        'by_uid' => $user_id,
                        'to_uid' => $uid,
                        'credits_before' => $cr,
                        'amount' => '-'.$decrease_credits,
                        'credits_after' => $cr_after,
                        'date' => $date_time,
                        'ip' => $user_ip,
                    ));
                    /*Logs end*/

                } else {
                    $result['type'] = "error";
                    $result['html_error'] = "ERROR, credits cannot be less than 0";
                }
            } else {
                $result['type'] = "error";
                $result['html_error'] = "ERROR, Enter a positive number";
            }
        } else {
            $result['type'] = "error";
            $result['html_error'] = "ERROR, Enter a number";
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