<?php
/*get user ip behind proxy*/
function validate_ip($ip)
{
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
        return false;
    }
    return true;
}
function get_ip_address() {
    $ip_keys = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR');
    foreach ($ip_keys as $key) {
        if (array_key_exists($key, $_SERVER) === true) {
            foreach (explode(',', $_SERVER[$key]) as $ip) {
                // trim for safety measures
                $ip = trim($ip);
                // attempt to validate IP
                if (validate_ip($ip)) {
                    return $ip;
                }
            }
        }
    }
    return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : false;
}
require_once(TEMPLATEPATH . '/mail/mail_templates/user_registered.php');
require_once(TEMPLATEPATH . '/mail/mail_templates/bid_package_purchase.php');
require_once(TEMPLATEPATH . '/mail/mail_templates/auction_item_purchase.php');
require_once(TEMPLATEPATH . '/mail/mail_templates/user_won_auction.php');
require_once(TEMPLATEPATH . '/mail/mail_templates/user_lost_auction.php');
require_once(TEMPLATEPATH . '/mail/mail_templates/auction_not_fullfilled.php');
require_once(TEMPLATEPATH . '/mail/mail_templates/15mins_auction_reminder.php');
require_once(TEMPLATEPATH . '/mail/mail_templates/custom_auction_reminder.php');
