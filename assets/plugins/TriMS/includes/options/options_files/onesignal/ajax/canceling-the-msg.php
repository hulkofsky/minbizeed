<?php

/*
 *  ajax call for canceling the notifications
 * 
 * @package  Notifications
 * @author   Marc Bou Sleiman
 */
add_action('wp_ajax_canceling_the_msg', 'prefix_ajax_canceling_the_msg');
add_action('wp_ajax_nopriv_canceling_the_msg', 'prefix_ajax_canceling_the_msg');

function prefix_ajax_canceling_the_msg() {
    if (!wp_verify_nonce($_REQUEST['nonce'], "canceling_the_msg")) {
        exit("You think you are smart?");
    }
    $notify_id = strip_tags($_REQUEST['notify_id']);

    if ($notify_id) {
        $result['html'] = "";

        function cancelMessage() {
            $OneSignalWPSetting = get_option('OneSignalWPSetting');
            $OneSignalWPSetting_app_id = $OneSignalWPSetting['app_id'];
            $OneSignalWPSetting_rest_api_key = $OneSignalWPSetting['app_rest_api_key'];
            $notify_id = strip_tags($_REQUEST['notify_id']);
            $ch = curl_init();
            $httpHeader = array(
                'Authorization: Basic ' . $OneSignalWPSetting_rest_api_key
            );
            $url = "https://onesignal.com/api/v1/notifications/" . $notify_id . "?app_id=" . $OneSignalWPSetting_app_id;

            $options = array(
                CURLOPT_URL => $url,
                CURLOPT_HTTPHEADER => $httpHeader,
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_CUSTOMREQUEST => "DELETE",
                CURLOPT_SSL_VERIFYPEER => FALSE
            );
            curl_setopt_array($ch, $options);
            $response = curl_exec($ch);
            curl_close($ch);

            return $response;
        }

        $response = cancelMessage();
        $return["success"] = $response;
        $return = json_encode($return);

//        print("\n\nJSON received:\n");
//        print($return);
//        print("\n");
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
