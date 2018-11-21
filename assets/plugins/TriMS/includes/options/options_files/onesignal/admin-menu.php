<?php
/**
 * admin_menu.php
 *
 * @package  Notifications system
 * @author   Marc Bou sleiman
 */

function notifications_disp_spcl_cst_pic($pic)
{
    $path = plugins_url("images/$pic");
    return '<img src="' . $path . '" /> ';
}

function notifications_admin_main_menu_scr()
{
    $capability = 10;
    add_menu_page(__('Notifications'), __('Notifications', 'notificationssend'), $capability, "SP_menu_", 'send_notifications', "dashicons-rss", null, 0);
    add_submenu_page("SP_menu_", __('New Message', 'notifications'), __('New Message', 'notifications'), $capability, "send_notifications", 'send_notifications');
    add_submenu_page("SP_menu_", __('Scheduled', 'notifications'), __('Scheduled', 'notifications'), $capability, "all_notifications", 'all_notifications');
    add_submenu_page("SP_menu_", __('Sent', 'notifications'), __('Sent', 'notifications'), $capability, "sent_notifications", 'sent_notifications');
}

function sending_notifications()
{
    ?>
    <style type="text/css">
        .adm_cont a {
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            display: block;
            padding: 0 0 0 15px;
            margin: 10px 20px;
        }

        .adm_cont a:hover {
            text-decoration: underline;
        }
    </style>
    <div class="adm_cont">
        <h2>Please choose:</h2>
        <a href="<?php echo esc_url(home_url('/')) ?>wp-admin/admin.php?page=send_notifications"><?php echo notifications_disp_spcl_cst_pic('notification-logo.png'); ?>
            Send Notifications</a>
    </div>
    <?php
}

function TriMS_onesignal_files()
{
    wp_enqueue_style('TriMS-os-zebra-css', plugins_url('js/Zebra_Datepicker-master/public/css/default.css', __FILE__), array(), '1.0');
    wp_enqueue_style('TriMS-os-Intimidatetime-css', plugins_url('js/Intimidatetime-master/dist/Intimidatetime.min.css', __FILE__), array(), '1.0');
    wp_enqueue_script('TriMS-os-zebra-js', plugins_url('js/Zebra_Datepicker-master/public/javascript/zebra_datepicker.js', __FILE__), array('jquery'), '1.0', true);
    wp_enqueue_script('TriMS-os-Intimidatetime-js', plugins_url("js/Intimidatetime-master/dist/Intimidatetime.min.js", __FILE__), array('jquery'), '1.0', true);
}

add_action('admin_enqueue_scripts', 'TriMS_onesignal_files');

/*functions*/
include(plugin_dir_path(__FILE__) . "sections/send-nots.php");
include(plugin_dir_path(__FILE__) . "sections/all-nots.php");
include(plugin_dir_path(__FILE__) . "sections/sent-nots.php");
include(plugin_dir_path(__FILE__) . "ajax/sending-the-msg.php");
include(plugin_dir_path(__FILE__) . "ajax/canceling-the-msg.php");