<?php
/*
 * Template: Bids history Template
 * Template Name: V20 Bids history Template
 */

if (!is_user_logged_in()):
    wp_redirect('/?msg=login&redirect_url=/my-account');
    exit;
else:
    get_header();
    global $current_user;
    get_currentuserinfo();
    $user_id = $current_user->ID;
    ?>
    <div class="account_settings_page bids_history_page">
        <div class="page_identifier">
            <h2>MY ACCOUNT</h2>
            <ul>
                <li><a href="/my-account">ACCOUNT SETTINGS</a></li>
                <li class="active"><a href="/my-account/bids-history">BIDS HISTORY</a></li>
                <li><a href="/my-account/trophy-room/">BIDS WON</a></li>
                <li><?php echo do_shortcode('[wppb-logout text="" redirect_url="/" link_text="LOGOUT"]') ?></li>
            </ul>
        </div>
        <div class="form_wrapper">
            <div class='container'>

                <div class="bids_history_wrapper">
                    <div class="container">
                        <div class="bids_top_section">
                            <div class="purchased_bids_wrapper">
                                <?php
                                global $wpdb;
                                $bids_transactions = "select * from " . $wpdb->prefix . "penny_payment_transactions where uid='$user_id'";
                                $res_bids_transactions = $wpdb->get_results($bids_transactions);

                                $total_paid_amount = 0;

                                foreach ($res_bids_transactions as $res_bids_transaction) {
                                    $bid_pckg = str_replace('Bid Package= ', '', $res_bids_transaction->tp);
                                    $bid_packages = "select bids from " . $wpdb->prefix . "penny_packages where id='$bid_pckg'";
                                    $res_bid_packages = $wpdb->get_results($bid_packages);
                                    foreach ($res_bid_packages as $res_bid_package) {
                                        $total_paid_amount = $total_paid_amount + $res_bid_package->bids;
                                    }
                                }
                                ?>
                                <h2><?php echo $total_paid_amount; ?></h2>
                                <span>PURCHASED <br>BIDS</span>
                                <div class="clear"></div>
                            </div>
                            <div class="available_bids_wrapper">
                                <h2><?php echo get_user_meta($user_id, 'user_credits', true) ?></h2>
                                <span>AVAILABLE <br>BIDS</span>
                                <div class="clear"></div>
                            </div>
                            <div class="buy_bids_wrapper">
                                <a href="/buy-bids" class="buy_bids_now">BUY BIDS</a>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
                <?php
                global $wpdb;
                $user_bids = "select * from " . $wpdb->prefix . "penny_bids where uid='$user_id' order by id desc LIMIT 20";
                $res_user_bids = $wpdb->get_results($user_bids);

                $user_purchased_bids = "select * from " . $wpdb->prefix . "penny_payment_transactions where uid='$user_id' LIMIT 5";
                $res_user_purchased_bids = $wpdb->get_results($user_purchased_bids);
                ?>
                <div class="user_history_wrapper">
                    <div class="container">

                        <?php
                        if ($res_user_bids) {
                            foreach ($res_user_bids as $res_user_bid) {
                                $pid = $res_user_bid->pid;
                                ?>
                                <div class="each_user_history">
                                    <div class="bids_history_number">
                                        <h4>-1</h4>
                                        <span>Bids</span>
                                    </div>
                                    <div class="bids_history_reason">
                                        <h4>For the auction: "<?php echo get_the_title($pid); ?>"</h4>
                                    </div>
                                    <div class="history_date_wrapper">
                                        <h4><?php echo date('d.m.Y, g:i A', $res_user_bid->date_made); ?></h4>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
                <?php

                ?>

            </div>
        </div>
        <div class="page_overlay"></div>
    </div>
    <div class="clear"></div>
    <?php
    get_footer();
endif;

