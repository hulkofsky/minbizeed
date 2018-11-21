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

    $bank_balance=minbizeed_get_credits(10);

    /*Logs vars end*/

    if (!empty($increase_credits)) {
        if (is_numeric($increase_credits)) {
            if ($increase_credits >= 0) {

                $increaseBids=increaseBids($uid,$increase_credits,'CMS_TRANSFER');

                if($increaseBids){
                    $result['type'] = "success";
                    $result['html_success'] = "Credits successfully increased!";
                    $result['total'] = $increaseBids;
                }else{
                    $result['type'] = "error";
                    $result['html_error'] = "ERROR, No credits left in bank";
                }

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

                $decreaseBids=decreaseBids($uid,$decrease_credits,'CMS_TRANSFER');

                if ($decreaseBids) {

                    $result['type'] = "success";
                    $result['html_success'] = "Credits successfully decreased!";
                    $result['total'] = minbizeed_get_credits($uid);

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