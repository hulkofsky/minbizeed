<?php

/*
 *  ajax call for sending the notifications
 * 
 * @package  Notifications
 * @author   Marc Bou Sleiman
 */
add_action('wp_ajax_sending_the_msg', 'prefix_ajax_sending_the_msg');
add_action('wp_ajax_nopriv_sending_the_msg', 'prefix_ajax_sending_the_msg');

function prefix_ajax_sending_the_msg() {
    if (!wp_verify_nonce($_REQUEST['nonce'], "sending_the_msg")) {
        exit("You think you are smart?");
    }
    $selected_method = strip_tags($_REQUEST['selected_method']);
    $notify_title = $_REQUEST['notify_title'];
    $notify_message = $_REQUEST['notify_message'];
    $notify_time = $_REQUEST['notify_time'];

    if ($selected_method && $notify_title && $notify_message) {
        $result['html'] = "";

        function sendMessage() {
            $OneSignalWPSetting = get_option('OneSignalWPSetting');
            $OneSignalWPSetting_app_id = $OneSignalWPSetting['app_id'];
            $OneSignalWPSetting_rest_api_key = $OneSignalWPSetting['app_rest_api_key'];
            $selected_method = strip_tags($_REQUEST['selected_method']);
            $notify_title = $_REQUEST['notify_title'];
            $notify_message = $_REQUEST['notify_message'];
            $notify_time = $_REQUEST['notify_time'];
            $notify_url = $_REQUEST['notify_url'];

            $date = new DateTime($notify_time);

            $date_timestamp = $date->getTimestamp();
            $final_date = $date_timestamp - 10800;
            $final_readable_date = date('Y-m-d h:i:00a', $final_date);
            $content = array(
                "en" => $notify_message
            );
            $title = array(
                "en" => $notify_title
            );
            if ($selected_method == "send-scheduled") {
                $fields = array(
                    'app_id' => $OneSignalWPSetting_app_id,
                    'included_segments' => array('All'),
                    'send_after' => $final_readable_date,
                    'data' => array("Message" => "Sending"),
                    'url' => $notify_url,
                    'contents' => $content,
                    'headings' => $title
                );
            } else {
                $fields = array(
                    'app_id' => $OneSignalWPSetting_app_id,
                    'included_segments' => array('All'),
                    'data' => array("Message" => "Sending"),
                    'url' => $notify_url,
                    'contents' => $content,
                    'headings' => $title
                );
            }

            $fields = json_encode($fields);
//            print("\nJSON sent:\n");
//            print($fields);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
                'Authorization: Basic '.$OneSignalWPSetting_rest_api_key));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

            $response = curl_exec($ch);
            curl_close($ch);

            return $response;
        }

        $response = sendMessage();
        $return["allresponses"] = $response;
        $return = json_encode($return);


//        echo $return;
        if ($selected_method == "send-scheduled") {
            $result['msgstatus'] = "Scheduled";
        }else{
            $result['msgstatus'] = "Sent";
        }
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
