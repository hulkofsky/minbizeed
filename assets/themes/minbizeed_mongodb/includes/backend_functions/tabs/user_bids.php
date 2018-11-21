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

            <div class="panel-body holder">

                <div class="padding10">
                    <?php
                    //$user_did_bid = "select * from " . $wpdb->prefix . "penny_bids where uid = '$sel_user'";
                    //$user_did_bid_r = $wpdb->get_results($user_did_bid);

                    // MongoDB Changes
                    $filter = ['uid'=>$sel_user];
                    $options = [];        
                    $query=new MongoDB\Driver\Query($filter, $options);
                    $res = $GLOBALS['mongodb_manager']->executeQuery('minbizeed.pennybids', $query);
                    $res=$res->toArray();
                    // MongoDB Changes

                    if ($user_did_bid_r) {
                        ?>
                        <h4>Auctions statistics</h4>
                        <?php
                        /*Total won auctions*/
                        //$all_won_auctions = "select * from " . $wpdb->prefix . "penny_bids where uid = '$sel_user' AND winner=1";
                        //$all_won_auctions_r = $wpdb->get_results($all_won_auctions);
                        //$all_won_auctions_r_count = count($all_won_auctions_r);

                        // MongoDB Changes
                        $filter = ['uid'=>$sel_user,'winner'=>1];
                        $options = [];        
                        $query=new MongoDB\Driver\Query($filter, $options);
                        $res = $GLOBALS['mongodb_manager']->executeQuery('minbizeed.pennybids', $query);
                        $all_won_auctions_r_count=count($res->toArray());
                        // MongoDB Changes

                        if ($all_won_auctions_r_count) {
                            ?>
                            <h4>User <b style="color:#EFA007"><?php echo $user->user_login; ?></b> Won <b
                                        style="color:#EFA007"><?php echo $all_won_auctions_r_count; ?></b> auctions on
                                Min Bi Zeed
                                so far</h4>
                            <?php
                        }
                        ?>
                        <br>
                        <h4>Bids statistics</h4>
                        <?php
                        /*Total Bids on website*/
                        //$all_bids = "select * from " . $wpdb->prefix . "penny_bids where uid = '$sel_user'";
                        //$all_bids_r = $wpdb->get_results($all_bids);
                        //$all_bids_r_count = count($all_bids_r);

                        // MongoDB Changes
                        $filter = ['uid'=>$sel_user];
                        $options = [];        
                        $query=new MongoDB\Driver\Query($filter, $options);
                        $res = $GLOBALS['mongodb_manager']->executeQuery('minbizeed.pennybids', $query);
                        $all_bids_r=$res->toArray();
                        $all_bids_r_count = count($all_bids_r);
                        // MongoDB Changes

                        if ($all_bids_r) {
                            ?>
                            <h4>User <b style="color:#EFA007"><?php echo $user->user_login; ?></b> Made <b
                                        style="color:#EFA007"><?php echo $all_bids_r_count; ?></b> Clicks on Min Bi Zeed
                                so
                                far
                            </h4>
                            <?php
                        }

                        /*remaining bids*/
                        $remaining_bids = get_user_meta($sel_user, 'user_credits', true);
                        if ($remaining_bids) {
                            ?>
                            <h5>User <b style="color:#EFA007"><?php echo $user->user_login; ?></b> Still has <b
                                        style="color:#EFA007"><?php echo $remaining_bids; ?></b> Bids in his account
                            </h5>
                            <?php
                        }

                        ?>
                        <br>
                        <h4>Payments statistics</h4>
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
                            <h5>User <b style="color:#EFA007"><?php echo $user->user_login; ?></b> Paid <b
                                        style="color:#EFA007"><?php echo $total_trans_paid; ?></b> $ in Transactions on
                                Min Bi Zeed
                                so far</h5>
                            <?php
                        }

                        ?>
                        <br>
                        <h4>Actions Log</h4>
                        <?php
                        wp_reset_query();
                        $args = array(
                            "posts_per_page" => -1,
                            "post_type" => "auction",
                        );
                        $loop = new WP_Query($args);
                        if ($loop->have_posts()) :
                            ?>

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
                                while ($loop->have_posts()) : $loop->the_post();
                                    $pid = get_the_ID();
                                    $time_increase = get_post_meta($pid, 'time_increase', true);
                                    $winner_id = get_post_meta($pid, 'winner', true);
                                    if ($winner_id == $sel_user) {
                                        $is_winner = 1;
                                    } else {
                                        $is_winner = 0;
                                    }

                                    global $wpdb;
                                    //$stat1 = "SELECT * from " . $wpdb->prefix . "penny_bids where pid='$pid' AND uid=$sel_user";
                                    //$stat1_r = $wpdb->get_results($stat1);
                                    //$total_bids_res_count = count($stat1_r);

                                    // MongoDB Changes
                                    $filter = ['pid'=>$pid,'uid'=>$sel_user];
                                    $options = [];        
                                    $query=new MongoDB\Driver\Query($filter, $options);
                                    $res = $GLOBALS['mongodb_manager']->executeQuery('minbizeed.pennybids', $query);
                                    $stat1_r=$res->toArray();
                                    $total_bids_res_count = count($stat1_r);
                                    // MongoDB Changes

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
                                        echo '<td style="color:#fff;"><a href="/wp-admin/post.php?post=' . $pid . '&action=edit" target="_blank">' . get_the_title() . '</a></td>';
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
                            <?php
                        else:
                            ?>
                            <p class="no_results">No bidding actions yet</p>
                            <?php
                        endif;
                    } else {
                        ?>
                        <p class="no_results">No bidding actions yet</p>
                        <?php
                    }
                    ?>
                </div>

            </div>
        </div>
        <?php
    } else {
        ?>
        <div class="admin_stats container">
            <h3>All Users Bids</h3>

            <div class="panel-body holder">
                <p class="no_results">Please select a user <a class="btn btn-primary"
                                                               href="/wp-admin/admin.php?page=user_balances">here</a>
                </p>
            </div>
        </div>

        <?php
    }
    ?>

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
}