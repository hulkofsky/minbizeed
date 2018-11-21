<?php
/*
 * Template: Buy Bids
 * Template Name: V20 Buy Bids
 */

get_header();
?>
    <div class="buy_bids_page">
        <div class="page_identifier">
            <h2>BUY BIDS</h2>
        </div>
        <?php

        if($_GET['msg']=="sig_error"){
            ?>
            <p class="wppb-error no_margins">
                Something went wrong before sending the payment to the bank, please try purchasing the package again.
            </p>
            <?php
        }

        global $wpdb;
        $s = "select * from " . $wpdb->prefix . "penny_packages order by cost asc";
        $r = $wpdb->get_results($s);
        if ($r) {
            ?>
            <div class="all_bids_wrapper">
                <div class="container">
                    <?php
                    foreach ($r as $row) {
                        ?>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 each_bid_wrapper">
                            <div class="each_bid_inner_wrap">
                                <h3><?php echo $row->package_name; ?></h3>
                                <h2><?php echo $row->bids; ?> bids</h2>
                                <span><?php echo minbizeed_get_show_price($row->cost); ?></span>
                                <?php
                                if (is_user_logged_in()) {
                                    $pkg_id = $row->id;
                                    ?>
                                    <a class="buy_bids_now"
                                       href="<?php echo '/my-account/purchase-bids/?bid_id=' . $pkg_id; ?>">BUY NOW</a>
                                    <?php
                                } else {
                                    ?>
                                    <a class="buy_bids_now" href="<?php echo '/?msg=login_req&redirect_url=/buy-bids'; ?>">BUY NOW</a>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <?php
        }
        ?>
        <div class="page_overlay"></div>
    </div>
<?php
get_footer();

