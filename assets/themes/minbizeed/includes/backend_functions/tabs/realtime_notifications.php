<?php
function minbizeed_realtime_notifications()
{
    ?>
    <div class="admin_stats container">
        <h3>Realtime Notifications</h3>
        <div class="form-group">
            <label>Title</label>
            <input id="realtime_not_title" name="realtime_not_title" class="form-control" type="text" value=""/>
        </div>

        <div class="form-group">
            <?php
            $r_not_nonce = wp_create_nonce("realtime_notifications");
            $r_not_link = admin_url('admin-ajax.php?action=realtime_notifications');
            ?>
            <a href="#"
               id="send_realtime_notification"
               data-nonce="<?php echo $r_not_nonce; ?>"
               data-ajax_url="<?php echo $r_not_link; ?>"
               class="btn btn-success">SEND</a>
        </div>

        <div class="form-group ajax_loader">
            <img src="<?php echo get_template_directory_uri(); ?>/images/ajax_loader.gif" alt="loader"/>
        </div>
        <div class="panel-body holder">

            <?php
            $rows_per_page = 10;

            if (isset($_GET['pj']))
                $pageno = $_GET['pj'];
            else
                $pageno = 1;

            global $wpdb;

            $s1 = "select id from " . $wpdb->prefix . "realtime_notifications order by id desc ";
            $s = "select * from " . $wpdb->prefix . "realtime_notifications order by id desc ";
            $limit = 'LIMIT ' . ($pageno - 1) * $rows_per_page . ',' . $rows_per_page;


            $r = $wpdb->get_results($s1);
            $nr = count($r);
            $lastpage = ceil($nr / $rows_per_page);

            $r = $wpdb->get_results($s . $limit);

            if ($nr > 0) {
                ?>
                <p class="ajax_return" style=""></p>
                <img class="loader" src="<?php echo get_bloginfo('template_url'); ?>/images/ajax_loader.gif"/>

                <table class="table table-bordered" id="table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Admin</th>
                        <th>Date/Time</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($r as $row) {
                        $user = get_userdata($row->by_uid);
                        date_default_timezone_set('Asia/Beirut');
                        $formated_date_time=date('m/d/Y h:i:s a', $row->date_time);
                        ?>
                        <tr>
                            <td>
                                <?php echo $row->id; ?>
                            </td>
                            <td>
                                <?php echo stripslashes($row->title); ?>
                            </td>
                            <td>
                                <?php echo $user->user_login; ?>
                            </td>
                            <td>
                                <?php echo $formated_date_time; ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>

                    </tbody>
                </table>
                <ul class="pagination">
                    <?php
                    for ($i = 1; $i <= $lastpage; $i++) {

                        ?>
                        <li>
                            <a href="<?php echo get_bloginfo('siteurl'); ?>/wp-admin/admin.php?page=realtime-notifications&pj=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
                <?php
            } else {
                _e('No notifications found', 'minbizeed');
            }
            ?>
        </div>
    </div>
    <style type="text/css">
        .clear {
            clear: both;
        }

        .header {
            margin: 20px 0;
        }

        .header .elt {
            display: inline-block;
        }

        .header .elt h2 {
            margin: 0 0 10px 0;
        }

        .header .elt.srch {
            float: right;
        }

        .header .elt.srch a {
            -webkit-box-shadow: 1.7px 1.7px 1px #787878;
            -moz-box-shadow: 1.7px 1.7px 1px #787878;
            box-shadow: 1.7px 1.7px 1px #787878;
            padding: 5px;
            text-decoration: none;
            border: 1px solid #000;
        }

        .loader {
            display: none;
            margin: 20px auto;
            width: 100%;
            max-width: 20px;
        }

        .ajax_return {
            text-align: center;
            margin: 20px 0;
            display: none;
            font-size: 16px;
        }

        .updated_yes {
            background-color: green;
            padding: 2px;
            color: white;
            -webkit-transition: all 0.5s ease;
            -moz-transition: all 0.5s ease;
            -o-transition: all 0.5s ease;
            transition: all 0.5s ease;
        }

        .updated_no {
            background-color: red;
            padding: 2px;
            color: white;
            -webkit-transition: all 0.5s ease;
            -moz-transition: all 0.5s ease;
            -o-transition: all 0.5s ease;
            transition: all 0.5s ease;
        }

        .widefat input {
            padding: 3px !important;
            font-size: 14px !important;
        }

        .intable_group {
            margin: 0 0 5px;
        }

        .intable_label {
            min-width: 140px;
        }
    </style>
    <script type='text/javascript' src='<?php echo get_template_directory_uri(); ?>/libraries/socket_io/socket-io.js'></script>
    <script type="text/javascript">   

    var NOTIFICATION = function () { };
    NOTIFICATION.prototype = {    
        /**
         * Initialization Methods
         */
        init: function () {
            this.initSockets();        
        },
        initSockets: function () {
            var self = this;
            jQuery(document).ready(function () {
                /*Live server connection start*/
                self.socket = io.connect('<?php echo site_url(); ?>:2000', {
                    'reconnect': true,
                    'secure': true,
                    'reconnection delay': 100
                }); // socket.io connection
                /*Live server connection end*/           

                /////////////////////

                jQuery('#send_realtime_notification').click(function () {
                var loader = jQuery('.ajax_loader');
                var nonce = jQuery(this).attr('data-nonce');
                var ajax_url = jQuery(this).attr('data-ajax_url');
                var title = jQuery('#realtime_not_title');

                if (title.val()) {
                    loader.css('display', 'block').hide().fadeIn();
                    jQuery.ajax({
                        type: "post",
                        dataType: "json",
                        url: ajax_url,
                        data: {
                            action: "realtime_notifications",
                            nonce: nonce,
                            title: title.val()
                        },
                        success: function (response) {
                            if (response.type == "success") {
                                
                                loader.slideUp();
                                jQuery('.ajax_return').css({"color": "green"});
                                jQuery('.ajax_return').html(response.html_success);
                                jQuery('.ajax_return').slideDown();
                                var return_row = "<tr><td>" + response.id + "</td><td>" + response.title + "</td><td>" + response.by_uid + "</td><td>" + response.date_time + "</td></tr>";
                                jQuery('#table tbody').prepend(return_row);
                                setTimeout(
                                    function () {
                                        jQuery('.ajax_return').slideUp();
                                    }, 3000);

                                self.socket.emit('SEND_ADMIN_NOTIFICATION', {message: response.title,time:response.time});
                                title.val('');

                            } else {
                                loader.slideUp();
                                jQuery('.ajax_return').css({"color": "red"});
                                if (response.type == "error_field") {
                                    jQuery('.ajax_return').html(response.html_error);
                                } else {
                                    jQuery('.ajax_return').html(response.html_error_field);
                                }
                                jQuery('.ajax_return').slideDown();
                            }
                        }
                    });
                } else {
                    alert('Please add title');
                }
            });

                ////////////////////



            });        
        }
    };
    //(new NOTIFICATION).init();
    var App = new NOTIFICATION();
    App.init();
    </script>    
    <?php
}