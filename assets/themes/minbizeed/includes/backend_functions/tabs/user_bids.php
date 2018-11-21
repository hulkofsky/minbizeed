<?php
function minbizeed_user_bids()
{
    global $wpdb;
    $sel_user = strip_tags($_GET['u']);
    $user = get_userdata($sel_user);
    if ($sel_user) {
        ?>
        <div class="admin_stats container">
            <h3>All Bid actions for: <?php echo $user->user_login; ?></h3>
            <?php
            $user_did_bid = "select * from " . $wpdb->prefix . "penny_bids where uid = '$sel_user'";
            $user_did_bid_r = $wpdb->get_results($user_did_bid);
            if ($user_did_bid_r) {
                ?>
                <div class="panel-body">
                    <table class="table table-bordered" id="table">
                        <thead>
                        <tr>
                            <th>Auctions statistics</th>
                            <th>Bids statistics</th>
                            <th>Remaining bids</th>
                            <th>Total payments</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <?php
                                /*Total won auctions*/
                                $all_won_auctions = "select * from " . $wpdb->prefix . "penny_bids where uid = '$sel_user' AND winner=1";
                                $all_won_auctions_r = $wpdb->get_results($all_won_auctions);
                                $all_won_auctions_r_count = count($all_won_auctions_r);
                                if ($all_won_auctions_r_count) {
                                    ?>
                                    <p>User <b style="color:#E82D6C"><?php echo $user->user_login; ?></b> Won <b
                                                style="color:#E82D6C"><?php echo $all_won_auctions_r_count; ?></b>
                                        auctions on MinBiZeed so far
                                    </p>
                                    <?php
                                } else {
                                    echo "-";
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                /*Total Bids on website*/
                                $all_bids = "select * from " . $wpdb->prefix . "penny_bids where uid = '$sel_user'";
                                $all_bids_r = $wpdb->get_results($all_bids);
                                $all_bids_r_count = count($all_bids_r);


                                if ($all_bids_r) {
                                    ?>
                                    <p>User <b style="color:#E82D6C"><?php echo $user->user_login; ?></b>
                                        Made <b style="color:#E82D6C"><?php echo $all_bids_r_count; ?></b>
                                        Clicks on MinBiZeed so far
                                    </p>
                                    <?php
                                } else {
                                    echo "-";
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                $remaining_bids = get_user_meta($sel_user, 'user_credits', true);
                                if ($remaining_bids) {
                                    ?>
                                    <p>
                                        User <b style="color:#E82D6C"><?php echo $user->user_login; ?></b>
                                        Still has <b style="color:#E82D6C"><?php echo $remaining_bids; ?></b>
                                        Bids in his account
                                    </p>
                                    <?php
                                } else {
                                    echo "-";
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                /*Total transactions bids*/
                                $all_trans = "select * from " . $wpdb->prefix . "penny_payment_transactions where uid = '$sel_user' AND status=0";
                                $all_trans_r = $wpdb->get_results($all_trans);
                                $total_trans_paid = 0;
                                foreach ($all_trans_r as $all_tran_r) {
                                    $total_trans_paid = $total_trans_paid + $all_tran_r->amount;
                                }
                                if ($total_trans_paid) {
                                    ?>
                                    <p>User <b style="color:#E82D6C"><?php echo $user->user_login; ?></b> Paid <b
                                                style="color:#E82D6C"><?php echo $total_trans_paid; ?></b> $ in
                                        Transactions on
                                        Min Bi Zeed
                                        so far</p>
                                    <?php
                                } else {
                                    echo "-";
                                }
                                ?>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <?php
            } else {
                ?>
                <p class="no_results">No bidding actions yet</p>
                <?php
            }
            ?>


            <?php
            $ppp = 3;
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            $offset = ($ppp * $paged) - $ppp;
            $args = array(
//                            'paged' => $paged,
//                            'offset' => $offset,
//                            'posts_per_page' => $ppp,
//                            'post_type' => 'auction',
//                            'order' => 'ASC',
//                            'cat' => -2,
//                            'meta_query' => array(
//                                array(
//                                    'key' => 'closed',
//                                    'value' => '1',
//                                    'compare' => '='
//                                )
//                            )
            );
            $wp_query = new WP_Query($args);
            if ($wp_query->have_posts()):
                ?>
                <br>
                <h4>Actions Log</h4>
                <table class="table table-bordered" id="table">
                    <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Username</th>
                        <th>Auction Name</th>
                        <th>Total Clicks</th>
                        <th>Total Bids</th>
                        <th>Spent Amount</th>
                        <th>Winner?</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    while ($wp_query->have_posts()) : $wp_query->the_post();
                        $pid = get_the_ID();
                        $time_increase = get_post_meta($pid, 'time_increase', true);
                        $winner_id = get_post_meta($pid, 'winner', true);
                        if ($winner_id == $sel_user) {
                            $is_winner = 1;
                        } else {
                            $is_winner = 0;
                        }

                        global $wpdb;
                        $stat1 = "SELECT * from " . $wpdb->prefix . "penny_bids where pid='$pid' AND uid=$sel_user";
                        $stat1_r = $wpdb->get_results($stat1);
                        $total_bids_res_count = count($stat1_r);


                        if ($total_bids_res_count) {
                            $total_bids = $total_bids_res_count * $time_increase;
                            $spent_amount = $total_bids * 0.416 . " $";

                            ?>
                            <?php
                            if ($is_winner) {
                                $style = 'background-color:green;';
                                $won = "YES";
                            } else {
                                $style = 'background-color:red;';
                                $won = "NO";
                            }

                            echo '<tr style="' . $style . '">';
                            echo '<td style="color:#fff;">' . $user->ID . '</td>';
                            echo '<td style="color:#fff;">' . $user->user_login . '</td>';
                            echo '<td style="color:#fff;"><a style="color:#fff !important;" href="/wp-admin/post.php?post=' . $pid . '&action=edit" target="_blank">' . get_the_title() . '</a></td>';
                            echo '<td style="color:#fff;">' . $total_bids_res_count . '</td>';
                            echo '<td style="color:#fff;">' . $total_bids . '</td>';
                            echo '<td style="color:#fff;">' . $spent_amount . '</td>';
                            echo '<td style="color:#fff;">' . $won . '</td>';
                            echo '</tr>';

                        }
                    endwhile;
                    ?>
                    </tbody>
                </table>
                <div class="clear"></div>
                <div class="bs_pag">
                    <?php
                    $pages = $wp_query->max_num_pages;
                    bootstrap_pagination($paged, $pages);
                    ?>
                </div>
                <?php
            else:
                ?>
<!--                <p class="no_results">No bidding actions yet</p>-->
                <?php
            endif;
            ?>
        </div>
        <style type="text/css">
            .header {
                margin: 20px 0;
            }

            .header .elt {
                display: inline-block;
            }

            .header .elt h2 {
                margin: 0 0 10px 0;
            }

            .widefat a {
                color: #fff;
                text-decoration: underline;
            }

            .no_results {
                text-align: center;
            }
        </style>
        <?php
    } else {
        ?>
        <div class="admin_stats container">
            <h3>Users Logs</h3>
            <a style="display: block;width: 100%;margin: 0 auto;max-width: 175px;" class="btn btn-primary" href="/wp-admin/admin.php?page=user_balances">Select user here</a>
        </div>
        <?php
    }
}