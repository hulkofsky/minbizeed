<?php
function minbizeed_orders_main_screen()
{


    ?>
    <div class="admin_stats container">
        <h3>
            Orders
        </h3>
        <div class="ajax_return_msg"></div>
        <ul class="nav nav-tabs items_menu">
            <li class="active">
                <a href="#tabs1" data-toggle="tab"> Not Paid Orders </a>
            </li>
            <li>
                <a href="#tabs2" data-toggle="tab"> Paid & Not Shipped Orders </a>
            </li>
            <li>
                <a href="#tabs3" data-toggle="tab"> Paid & Shipped Orders </a>
            </li>
        </ul>

        <div class="tab-content">
            <?php
            $ppp = 1;
            $paged1 = isset($_GET['paged1']) ? (int)$_GET['paged1'] : 1;
            $paged2 = isset($_GET['paged2']) ? (int)$_GET['paged2'] : 1;
            $paged3 = isset($_GET['paged3']) ? (int)$_GET['paged3'] : 1;
            ?>
            <div id="tabs1" class="tab-pane fade in active">

                <?php

                $closed_1 = array(
                    'key' => 'closed',
                    'value' => '1',
                    'compare' => '='
                );

                $winner_1 = array(
                    'key' => 'winner',
                    'value' => '0',
                    'compare' => '!='
                );

                $paid_1 = array(
                    'key' => 'winner_paid',
                    'value' => '0',
                    'compare' => '='
                );

                $args_1 = array(
                    'paged' => $paged1,
                    'posts_per_page' => $ppp,
                    'post_type' => 'auction',
                    'order' => 'DESC',
                    'orderby' => 'meta_value_num',
                    'meta_key' => 'closed_date',
                    'meta_query' => array($closed_1, $winner_1, $paid_1)
                );

                $query_1 = new WP_Query($args_1);
                $i = 0;
                if ($query_1->have_posts()):
                    ?>
                    <p class="ref_updt paid">
                        <a href="#" class="reload">Refresh</a> to update when done editing
                    </p>
                    <table class="table table-bordered" id="table">
                        <thead>
                        <tr>
                            <th>Auction Title</th>
                            <th>Winner</th>
                            <th>Won For</th>
                            <th>Closed On</th>
                            <th>Options</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        while ($query_1->have_posts()):
                            $query_1->the_post();
                            $post_id = get_the_ID();
                            $winner = get_user_by('id', get_post_meta($post_id, 'winner')[0]);
                            $bid = minbizeed_get_show_price(get_post_meta($post_id, 'current_bid')[0]);
                            $date_choosen = date_i18n('d-m-Y H:i:s', get_post_meta($post_id, 'closed_date')[0]);
                            ?>
                            <tr>
                                <td>
                                    <a href="<?php echo get_permalink(get_the_ID()); ?>" target="_blank">
                                        <?php the_title(); ?>
                                    </a>
                                </td>
                                <td><?php echo $winner->user_login; ?></td>
                                <td>$<?php echo $bid; ?></td>
                                <td><?php echo $date_choosen; ?></td>
                                <td class="ajax_mark_paid">
                                    <?php
                                    $ajax_action_1 = "mark_paid_action";
                                    $ajax_nonce_1 = wp_create_nonce("mark_paid_action");
                                    $ajax_link_1 = admin_url('admin-ajax.php?action=mark_paid_action');
                                    ?>
                                    <a href="#"
                                       data-nonce_1="<?php echo $ajax_nonce_1; ?>"
                                       data-link_1="<?php echo $ajax_link_1; ?>"
                                       data-action_1="<?php echo $ajax_action_1; ?>"
                                       data-post_id_1="<?php echo $post_id; ?>">
                                        Mark as Paid
                                    </a>
                                    <img class="ajax_loader"
                                         src="<?php echo get_template_directory_uri(); ?>/images/ajax_loader.gif"
                                         alt="Loader"/>
                                </td>
                            </tr>
                            <?php
                            $i++;
                        endwhile;
                        ?>
                    </table>
                    <div class="clear"></div>
                    <div class="navigation">
                        <?php
                        $pag_args1 = array(
                            'format' => '?paged1=%#%' . '#tabs1',
                            'current' => $paged1,
                            'total' => $query_1->max_num_pages,
                            'add_args' => array('paged2' => $paged2, 'paged3' => $paged3),
                            'next_text' => 'Next',
                        );
                        echo paginate_links($pag_args1);
                        ?>
                    </div>
                    <?php
                else:
                    ?>
                    <p class="no_content">No items yet</p>
                    <?php
                endif;
                wp_reset_query();
                ?>
            </div>

            <div id="tabs2" class="tab-pane fade in">
                <?php

                $closed_2 = array(
                    'key' => 'closed',
                    'value' => '1',
                    'compare' => '='
                );

                $winner_2 = array(
                    'key' => 'winner',
                    'value' => '0',
                    'compare' => '!='
                );

                $paid_2 = array(
                    'key' => 'winner_paid',
                    'value' => '1',
                    'compare' => '='
                );

                $shipped_2 = array(
                    'key' => 'shipped',
                    'value' => '0',
                    'compare' => '='
                );

                $args_2 = array(
                    'paged' => $paged2,
                    'posts_per_page' => $ppp,
                    'post_type' => 'auction',
                    'order' => 'DESC',
                    'orderby' => 'meta_value_num',
                    'meta_key' => 'closed_date',
                    'meta_query' => array($closed_2, $winner_2, $paid_2, $shipped_2)
                );

                $query_2 = new WP_Query($args_2);
                if ($query_2->have_posts()):
                    ?>
                    <p class="ref_updt shipped">
                        <a href="#" class="reload">Refresh</a> to update when done
                        editing
                    </p>
                    <table class="table table-bordered" id="table">
                        <thead>
                        <tr>
                            <th>Auction Title</th>
                            <th>Closed On</th>
                            <th>Winner</th>
                            <th>Won For</th>
                            <th>Paid on</th>
                            <th>Options</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        while ($query_2->have_posts()):
                            $query_2->the_post();
                            $post_id = get_the_ID();
                            $winner = get_user_by('id', get_post_meta($post_id, 'winner')[0]);
                            $bid = minbizeed_get_show_price(get_post_meta($post_id, 'current_bid')[0]);
                            $date_choosen = date_i18n('d-m-Y H:i:s', get_post_meta($post_id, 'closed_date')[0]);
                            $paid_on = get_post_meta($post_id, 'paid_on')[0];
                            ?>
                            <tr>
                                <td>
                                    <a href="<?php echo get_permalink(get_the_ID()); ?>" target="_blank">
                                        <?php the_title(); ?>
                                    </a>
                                </td>
                                <td><?php echo $date_choosen; ?></td>
                                <td><?php echo $winner->user_login; ?></td>
                                <td>$<?php echo $bid; ?></td>
                                <td><?php echo $paid_on; ?></td>
                                <td class="ajax_mark_shipped">
                                    <?php
                                    $ajax_action_2 = "mark_shipped_action";
                                    $ajax_nonce_2 = wp_create_nonce("mark_shipped_action");
                                    $ajax_link_2 = admin_url('admin-ajax.php?action=mark_shipped_action');
                                    ?>
                                    <a href="#"
                                       data-nonce_2="<?php echo $ajax_nonce_2; ?>"
                                       data-link_2="<?php echo $ajax_link_2; ?>"
                                       data-action_2="<?php echo $ajax_action_2; ?>"
                                       data-post_id_2="<?php echo $post_id; ?>">
                                        Mark as Shipped
                                    </a>
                                    <img class="ajax_loader"
                                         src="<?php echo get_template_directory_uri(); ?>/images/ajax_loader.gif"
                                         alt="Loader"/>
                                </td>
                            </tr>
                            <?php
                            $i++;
                        endwhile;
                        ?>
                    </table>
                    <div class="clear"></div>
                    <div class="navigation">
                        <?php
                        $pag_args2 = array(
                            'format' => '?paged2=%#%' . '#tabs2',
                            'current' => $paged2,
                            'total' => $query_2->max_num_pages,
                            'add_args' => array('paged1' => $paged1, 'paged3' => $paged3),
                            'next_text' => 'Next',
                        );
                        echo paginate_links($pag_args2);
                        ?>
                    </div>
                    <?php
                else:
                    ?>
                    <p class="no_content">No items yet</p>
                    <?php
                endif;
                wp_reset_query();
                ?>
            </div>

            <div id="tabs3" class="tab-pane fade in">
                <?php

                $closed_3 = array(
                    'key' => 'closed',
                    'value' => '1',
                    'compare' => '='
                );

                $winner_3 = array(
                    'key' => 'winner',
                    'value' => '0',
                    'compare' => '!='
                );

                $paid_3 = array(
                    'key' => 'winner_paid',
                    'value' => '1',
                    'compare' => '='
                );

                $shipped_3 = array(
                    'key' => 'shipped',
                    'value' => '1',
                    'compare' => '='
                );

                $args_3 = array(
                    'paged' => $paged3,
                    'posts_per_page' => $ppp,
                    'post_type' => 'auction',
                    'order' => 'DESC',
                    'orderby' => 'meta_value_num',
                    'meta_key' => 'closed_date',
                    'meta_query' => array($closed_3, $winner_3, $paid_3, $shipped_3)
                );

                $query_3 = new WP_Query($args_3);
                if ($query_3->have_posts()):
                    ?>
                    <table class="table table-bordered" id="table">
                        <thead>
                        <tr>
                            <th>Auction Title</th>
                            <th>Closed On</th>
                            <th>Winner</th>
                            <th>Won For</th>
                            <th>Paid On</th>
                            <th>Shipped On</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        while ($query_3->have_posts()):
                            $query_3->the_post();
                            $post_id = get_the_ID();
                            $winner = get_user_by('id', get_post_meta($post_id, 'winner')[0]);
                            $bid = minbizeed_get_show_price(get_post_meta($post_id, 'current_bid')[0]);
                            $date_choosen = date_i18n('d-m-Y H:i:s', get_post_meta($post_id, 'closed_date')[0]);
                            $shipped_on = get_post_meta($post_id, 'shipped_on')[0];
                            $paid_on = get_post_meta($post_id, 'paid_on')[0];
                            ?>
                            <tr>
                                <td>
                                    <a href="<?php echo get_permalink(get_the_ID()); ?>" target="_blank">
                                        <?php the_title(); ?>
                                    </a>
                                </td>
                                <td><?php echo $date_choosen; ?></td>
                                <td><?php echo $winner->user_login; ?></td>
                                <td>$<?php echo $bid; ?></td>
                                <td><?php echo $paid_on; ?></td>
                                <td><?php echo $shipped_on; ?></td>
                            </tr>
                            <?php
                            $i++;
                        endwhile;
                        ?>
                    </table>
                    <div class="clear"></div>
                    <div class="navigation">
                        <?php
                        $pag_args3 = array(
                            'format' => '?paged3=%#%' . '#tabs3',
                            'current' => $paged3,
                            'total' => $query_3->max_num_pages,
                            'add_args' => array('paged1' => $paged1, 'paged2' => $paged2),
                            'next_text' => 'Next',
                        );
                        echo paginate_links($pag_args3);
                        ?>
                    </div>
                    <?php
                else:
                    ?>
                    <p class="no_content">No items yet</p>
                    <?php
                endif;
                wp_reset_query();
                ?>
            </div>
        </div>
    </div>
    <style type="text/css">

        .admin_stats .ajax_loader {
            margin: 15px 0 0 10px;
            display: none;
            width: 100%;
            max-width: 21px;
        }

        .nav {
            text-align: center;
            margin: 20px 0;
        }

        .nav li {
            display: inline-block;
            float: none;
        }

        .nav li.active {
            border-top: 1px solid #ccc;
            border-right: 1px solid #ccc;
            border-left: 1px solid #ccc;
        }

        .nav li.active a {
            color: #000;
        }

        .nav li a {
            background-color: #fff;
            color: rgba(51, 51, 51, 0.5);
        }

        .ajax_return_msg {
            color: #fff;
            display: none;
            padding: 10px 0;
            text-align: center;
            width: 100%;
            margin: 0 0 30px 0;
        }

        .ajax_return_msg.has_error {
            background-color: #E23A3D;
        }

        .ajax_return_msg.has_success {
            background-color: #C758C5;
        }

        .ajax_return_msg a {
            color: #fff;
            text-decoration: underline;
        }

        .no_link_btn {
            cursor: default;
            pointer-events: none;
        }

        .no_content {
            margin: 20px 0;
            text-align: center;
            font-size: 16px;
        }

        .ref_updt {
            color: rgba(0, 0, 0, 0.4);
            font-style: italic;
            font-size: 12px;
            margin: 20px 0;
            text-align: center;
            display: none;
        }

    </style>

    <script type="text/javascript">
        jQuery(document).ready(function () {

            jQuery(function () {
                var hash = window.location.hash;
                hash && jQuery('ul.nav a[href="' + hash + '"]').tab('show');

                jQuery('.nav-tabs a').click(function (e) {
                    jQuery(this).tab('show');
                    var scrollmem = jQuery('body').scrollTop() || jQuery('html').scrollTop();
                    window.location.hash = this.hash;
                    jQuery('html,body').scrollTop(scrollmem);
                })
            });

            jQuery('.ajax_mark_paid a').click(function (e) {
                e.preventDefault();

                if (window.confirm("Payment should be done through the payment gateway, are you sure you want to manually mark this item as paid?\nThis can't be undone!")) {

                    var ajax_link_1 = jQuery(this).attr('data-link_1');
                    var ajax_nonce_1 = jQuery(this).attr('data-nonce_1');
                    var ajax_action_1 = jQuery(this).attr('data-action_1');
                    var post_id_1 = jQuery(this).attr('data-post_id_1');

                    var loader = jQuery(this).parent().find('.ajax_loader');

                    var return_msg = jQuery('.ajax_return_msg');

                    var btn = jQuery(this);

                    btn.slideUp();

                    loader.slideDown().css('display', 'inline-block');

                    jQuery.ajax({
                        type: "post",
                        dataType: "json",
                        url: ajax_link_1,
                        data: {
                            ajax_nonce_1: ajax_nonce_1,
                            ajax_action_1: ajax_action_1,
                            post_id_1: post_id_1
                        },
                        success: function (response) {
                            if (response.type == "success") {

                                loader.slideUp();

                                return_msg.addClass('has_success');
                                return_msg.html('Item marked as paid!');
                                return_msg.slideDown();

                                btn.addClass('no_link_btn');

                                btn.html('Paid');
                                btn.slideDown();

                                setTimeout(function () {
                                    return_msg.slideUp();
                                    jQuery('.ref_updt.paid').slideDown();
                                }, 3000);

                            } else {
                                loader.slideUp();
                                return_msg.addClass('has_error');
                                return_msg.html('An error occurred, please refresh and try again');
                                return_msg.slideDown();

                                btn.addClass('no_link_btn');

                                btn.html('ERROR');
                                btn.slideDown();

                                setTimeout(function () {
                                    return_msg.slideUp();
                                }, 3000);
                            }

                        },
                        error: function () {
                            loader.slideUp();
                            return_msg.addClass('has_error');
                            return_msg.html('An error occurred, please refresh and try again');
                            return_msg.slideDown();

                            btn.addClass('no_link_btn');

                            btn.html('ERROR');
                            btn.slideDown();

                            setTimeout(function () {
                                return_msg.slideUp();
                            }, 3000);
                        }
                    });
                }
            });

            jQuery('.ajax_mark_shipped a').click(function (e) {
                e.preventDefault();

                if (window.confirm("Are you sure?\nThis can't be undone!")) {

                    var ajax_link_2 = jQuery(this).attr('data-link_2');
                    var ajax_nonce_2 = jQuery(this).attr('data-nonce_2');
                    var ajax_action_2 = jQuery(this).attr('data-action_2');
                    var post_id_2 = jQuery(this).attr('data-post_id_2');

                    var loader = jQuery(this).parent().find('.ajax_loader');

                    var return_msg = jQuery('.ajax_return_msg');

                    var btn = jQuery(this);

                    btn.slideUp();

                    loader.slideDown().css('display', 'inline-block');

                    jQuery.ajax({
                        type: "post",
                        dataType: "json",
                        url: ajax_link_2,
                        data: {
                            ajax_nonce_2: ajax_nonce_2,
                            ajax_action_2: ajax_action_2,
                            post_id_2: post_id_2
                        },
                        success: function (response) {
                            if (response.type == "success") {

                                loader.slideUp();

                                return_msg.addClass('has_success');
                                return_msg.html('Item marked as shipped!');
                                return_msg.slideDown();

                                btn.addClass('no_link_btn');

                                btn.html('Shipped');
                                btn.slideDown();

                                setTimeout(function () {
                                    return_msg.slideUp();
                                    jQuery('.ref_updt.shipped').slideDown();
                                }, 3000);

                            } else {
                                loader.slideUp();
                                return_msg.addClass('has_error');
                                return_msg.html('An error occurred, please refresh and try again');
                                return_msg.slideDown();

                                btn.addClass('no_link_btn');

                                btn.html('ERROR');
                                btn.slideDown();

                                setTimeout(function () {
                                    return_msg.slideUp();
                                }, 3000);
                            }

                        },
                        error: function () {
                            loader.slideUp();
                            return_msg.addClass('has_error');
                            return_msg.html('An error occurred, please refresh and try again');
                            return_msg.slideDown();

                            btn.addClass('no_link_btn');

                            btn.html('ERROR');
                            btn.slideDown();

                            setTimeout(function () {
                                return_msg.slideUp();
                            }, 3000);
                        }
                    });
                }
            });

            jQuery(document).on('click', '.reload', function (e) {
                e.preventDefault();
                if (jQuery(this).parent().hasClass('paid')) {
                    window.location.replace("/wp-admin/admin.php?page=orders#tabs2");
                    location.reload();
                } else if (jQuery(this).parent().hasClass('shipped')) {
                    window.location.replace("/wp-admin/admin.php?page=orders#tabs3");
                    location.reload();
                } else {
                    alert('An error occurred, please refresh and try again');
                }
            });

        });
    </script>

    <?php
//    if (isset($_GET['mark_shipped'])) {
//        global $wpdb;
//        $s = "update " . $wpdb->prefix . "penny_bids set shipped='1', shipped_on='" . current_time('timestamp', 0) . "' where id='" . $_GET['mark_shipped'] . "'";
//        $wpdb->query($s);
//
//        $s1 = "select * from " . $wpdb->prefix . "penny_bids where id='" . $_GET['mark_shipped'] . "' ";
//        $r1 = $wpdb->get_results($s1);
//        $row1 = $r1[0];
//
//        update_post_meta($row1->pid, 'shipped', 1);
//        echo '<div class="saved_thing">The item was marked as shipped.</div>';
//    }
}