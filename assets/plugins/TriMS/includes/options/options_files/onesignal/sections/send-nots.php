<?php

/**
 * Notifications systems
 *
 * @package  Notifications
 * @author   Marc Bou Sleiman
 */
function send_notifications() {
    $nonce = wp_create_nonce("sending_the_msg");
    $link = admin_url('admin-ajax.php?action=sending_the_msg');
    ?>
    <div class="wrap">
        <div class="bread_crumbs">
            <a href="<?php echo esc_url(home_url('/')) ?>wp-admin/admin.php?page=send_notifications" class="active_bread">Send Notification</a>
            <a href="<?php echo esc_url(home_url('/')) ?>wp-admin/admin.php?page=all_notifications">Scheduled Notifications</a>
            <a href="<?php echo esc_url(home_url('/')) ?>wp-admin/admin.php?page=sent_notifications">Sent Notifications</a>
        </div>
        <div class="icon32" id="icon-options-general">
            <br/>
        </div>
        <div class="header">
            <div class="elt">
                <h2>New Notification Message</h2>
            </div>
            <div class="elt srch">
                <form method="get" action="#">
                    <input type="hidden" name="page" value="property_info"/>
                    <div class="datepicker-type-wrapper">
                        <span class="section_title">SCHEDULE:</span>
                        <span class="radio-wrapper">
                            <input class="radio_selector" type="radio" name="send-time" id="send-immediately" value="send-immediately">
                            <label for="send-immediately">Send immediately</label>
                        </span>
                        <span class="radio-wrapper">
                            <input class="radio_selector" type="radio" name="send-time" id="send-scheduled" value="send-scheduled">
                            <label for="send-scheduled">Schedule Message</label>
                        </span>
                    </div>
                    <div class="schedule_date_section">
                        <span class="section_title">DATE:</span>
                        <input type="text" name="timepicker" class="timepicker"/>
    <!--                        <input id="time_picker_field" type="text" size="35" value="<?php // echo $_GET['src_date'];                                    ?>"
                               name="src_date" placeholder="Enter username to search"/>-->
                    </div>
                    <span class="section_title">TITLE:</span>
                    <input type="text" name="notification-title" id="notification-title" />
                    <span class="section_title">MESSAGE:</span>
                    <textarea rows="4" cols="50" name="notification-message" id="notification-message"></textarea>
                    <span class="section_title">URL: <h6>(When user clicks on the notification, he will be redirected to this URL.)</h6></span>
                    <input type="text" placeholder="(Optional)" name="notification-url" id="notification-url" />
                    <input id="send_notification" data-link="<?php echo $link; ?>" data-nonce="<?php echo $nonce; ?>" type="submit" value="Submit" name="submit"/>
                    <img alt="os_loader" class="loader_image" src="<?php echo WP_PLUGIN_URL . '/TriMS/includes/options/options_files/onesignal/images/loader.gif'; ?>" />
                    <div class="ajax_result"></div>
                </form>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <style type="text/css">
        .bread_crumbs{
            text-align: center;
        }
        
        .bread_crumbs a {
            color: #C92432;
            display: inline-block;
            font-size: 16px;
            padding: 7px 25px;
            text-decoration: none;
        }

        .bread_crumbs a:nth-child(1),
        .bread_crumbs a:nth-child(2){
            border-right: 1px solid #C92432;
        }
        
        .bread_crumbs a.active_bread{
            text-decoration: underline;
            color: #929497;
            font-weight: bold;
        }
        .loader_image {
            margin: 0 0 3px 15px;
            vertical-align: bottom;
            display: none;
        }

        .schedule_date_section{
            display: none;
            position: relative;
        }

        .wrap {
            background-color: #fff;
            margin: 40px 20px 0 0;
            border: 1px solid #C92432;
        }
        .status{
            text-align:center;
        }
        .status .loader{
            display:none;
            margin:0 auto;
        }
        .status p{
            font-weight: bold;
            font-size:16px;
        }
        .loader {
            display: none;
        }

        .widefat tbody th {
            color: #000;
        }

        .widefat tbody th a {
            color: #000;
            font-weight: bold;
        }

        .widefat tbody tr td{
            color: #000;
        }

        .clear {
            clear: both;
        }

        .header .elt {
            display: block;
        }

        .header .elt h2 {
            background-color: #C92432;
            color: #fff;
            font-size: 20px;
            margin: 0;
            padding: 30px 0;
            text-align: center;
        }

        .header .elt.srch {
            display: block;
            text-align: left;
            padding: 30px;
        }

        .header .elt.srch form input[name="submit"]{
            background-color: #fff;
            color: #611341;
            font-weight: bold;
            margin: 25px 0 0 0;
            cursor: pointer;
            -webkit-transition: all 0.5s ease;
            -moz-transition: all 0.5s ease;
            -o-transition: all 0.5s ease;
            transition: all 0.5s ease;
        }

        .header .elt.srch form input[name="submit"]:hover{
            background-color: #C92432;
            color: #fff;
            -webkit-transition: all 0.5s ease;
            -moz-transition: all 0.5s ease;
            -o-transition: all 0.5s ease;
            transition: all 0.5s ease;
        }

        .header .elt.srch form input#notification-title,
        .header .elt.srch form input#notification-url,
        .header .elt.srch form textarea{
            width: 100%;
        }

        .header .elt.srch form input[name="submit"],
        .header .elt.srch form textarea,
        .header .elt.srch form input{
            border: 1px solid #929497;
            padding: 7px;
            font-weight: 600;
            border-radius: 0;
            background-color: #fff;
            color: #929497;
        }

        .header .elt.srch a {
            -webkit-box-shadow: 1.7px 1.7px 1px #787878;
            -moz-box-shadow: 1.7px 1.7px 1px #787878;
            box-shadow: 1.7px 1.7px 1px #787878;
            padding: 5px;
            text-decoration: none;
            border: 1px solid #611431;
            background-color: #fff;
            color: #611431;
        }
        .Zebra_DatePicker{
            top: 14% !important;
        }

        .datepicker-type-wrapper{
            margin: 0 0 20px 0;
        }

        .datepicker-type-wrapper .radio-wrapper{
            display: block;
            margin: 10px 30px;
        }

        .section_title{
            color: #929497;
            font-size: 17px;
            font-weight: bold;
            line-height: normal;
            margin: 10px 0;
            display: block;
        }

        .section_title h6{
            display: inline-block;
            margin: 0;
        }

        .datepicker-type-wrapper .radio-wrapper label{
            vertical-align: top;
            min-height: 18px;
            color: #505050;
            font-size: 16px;
        }

        .ajax_result h5{
            display: inline-block;
            font-size: 20px;
            font-weight: bold;
            margin: 20px 0 0;
        }

        .wppb-serial-notification{
            display: none;
        }
    </style>
    <script type="text/javascript">
        jQuery(document).ready(function () {
            //            jQuery('#time_picker_field').Zebra_DatePicker();
            jQuery(function ($) {
                $('.timepicker').intimidatetime({
                    format: 'yyyy-MM-dd hh:mm:00tt',
                    previewFormat: 'yyyy-MM-dd hh:mmtt'
                });
            });
            //if schedule clicked open date field
            jQuery('.radio_selector').on('click', function () {
                var selected_method = jQuery(this).val();
                if (selected_method == "send-scheduled") {
                    jQuery('.schedule_date_section').slideDown();
                } else {
                    jQuery('.schedule_date_section').slideUp();
                }
            });
            //submit button notification
            jQuery('#send_notification').on('click', function (e) {
                e.preventDefault();
                var selected_method = jQuery('input[name=send-time]:checked').val();
                var notify_title = jQuery('#notification-title').val();
                var notify_message = jQuery('#notification-message').val();
                var notify_time = jQuery('.timepicker').val();
                var notify_url = jQuery('#notification-url').val();
                var nonce = jQuery(this).attr("data-nonce");
                var ajax_url = jQuery(this).attr("data-link");
                var loader = jQuery('.loader_image');
                //                    alert(selected_method);
                if (selected_method && notify_title && notify_message) {
                    if (selected_method == 'send-immediately') {
                        if (notify_title && notify_message) {
                            loader.fadeIn();
                            jQuery.ajax({
                                type: "post",
                                dataType: "json",
                                url: ajax_url,
                                data: {action: "sending_the_msg", nonce: nonce, selected_method: selected_method,
                                    notify_title: notify_title, notify_message: notify_message, notify_time: notify_time, notify_url: notify_url},
                                success: function (response) {
                                    loader.fadeOut();
                                    if (response.type == "success") {
                                        jQuery('.ajax_result').html('<h5 style="color : green ">Message Sent. Refreshing...</h5>');
                                        function reload() {
                                            location.reload(true);
                                        }
                                        setTimeout(reload, 1000);
                                    } else {
                                        jQuery('.ajax_result').html('<h5 style="color : red ">There was an error! Please try again.</h5>');
                                    }
                                }
                            });
                        }
                    }
                    if (selected_method == 'send-scheduled') {
                        if (notify_title && notify_message && notify_time) {
                            loader.fadeIn();
                            jQuery.ajax({
                                type: "post",
                                dataType: "json",
                                url: ajax_url,
                                data: {action: "sending_the_msg", nonce: nonce, selected_method: selected_method,
                                    notify_title: notify_title, notify_message: notify_message, notify_time: notify_time, notify_url: notify_url},
                                success: function (response) {
                                    loader.fadeOut();
                                    if (response.type == "success") {
                                        jQuery('.ajax_result').html('<h5 style="color : green ">Message '+ response.msgstatus +'. Refreshing...</h5>');
                                        function reload() {
                                            location.reload(true);
                                        }
                                        setTimeout(reload, 1000);
                                    } else {
                                        jQuery('.ajax_result').html('<h5 style="color : red ">There was an error! Please try again.</h5>');
                                    }
                                }
                            });
                        } else {
                            jQuery('.ajax_result').html('<h5 style="color : red ">Please Select the schedule, fill the Date, Title and Message.</h5>');
                        }
                    }
                } else {
                    jQuery('.ajax_result').html('<h5 style="color : red ">Please Select the schedule, fill the Title and Message.</h5>');
                }
            });
        }
        );
    </script>
    <?php
}
