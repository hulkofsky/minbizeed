<footer>
    <?php
    wp_footer();
    ?>
    <div class="footer">
        <div class="footer_buy_part">
            <h3 class="outer_buy_bids_button">
                <a class="buy_bids_init" href="#">Buy Bids</a>
            </h3>
            <img class="bottom_arrow" alt="top_logo"
                 src="<?php echo get_template_directory_uri(); ?>/images/arrows/bottom_arrow.png">
            <div class="bids_wrapper">
                <h3 class="inner_buy_bids_button">
                    <a class="buy_bids_init" href="#">Buy Bids</a>
                </h3>
                <img class="top_arrow" alt="top_logo"
                     src="<?php echo get_template_directory_uri(); ?>/images/arrows/top_arrow.png">
                <?php
                global $wpdb;
                $s = "select * from " . $wpdb->prefix . "penny_packages order by cost asc";
                $r = $wpdb->get_results($s);
                if ($r) {
                    $i = 0;
                    ?>
                    <div class="container">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 biddings_wraps">
                            <div class='not_slider_hidden'>
                                <?php
                                foreach ($r as $row) {
                                    $i++;
                                    switch ($i):
                                        case 1:
                                            $img_link = get_template_directory_uri() . '/images/bids/bids_amount1.png';
                                            break;
                                        case 2:
                                            $img_link = get_template_directory_uri() . '/images/bids/bids_amount2.png';
                                            break;
                                        case 3:
                                            $img_link = get_template_directory_uri() . '/images/bids/bids_amount3.png';
                                            break;
                                        default:
                                            $img_link = get_template_directory_uri() . '/images/bids/bids_amount4.png';
                                    endswitch;
                                    ?>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 single_bid_wrap">
                                        <div class="single_bid_wrapper">
                                            <h3 class="bid_title"><?php echo $row->package_name; ?></h3>
                                            <h3 class="bid_amount"><?php echo $row->bids; ?> Bids</h3>
                                            <div class="bid_image_holder">
                                                <img class="bid_image" alt="bid_image"
                                                     src="<?php echo $img_link; ?>">
                                            </div>
                                        </div>
                                        <?php
                                        if (is_user_logged_in()) {
                                            $pkg_id = $row->id;
                                            ?>
                                            <a class="bid_price" href="/purchase-bids?bid=<?php echo $pkg_id; ?>">
                                                <?php echo minbizeed_get_show_price($row->cost); ?>
                                            </a>
                                            <?php
                                        } else {
                                            ?>
                                            <a class="bid_price" href="<?php echo '/login?redirect_url=/#buy_bids'; ?>">
                                                <?php echo minbizeed_get_show_price($row->cost); ?>
                                            </a>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
        <div class="footer_menu_part dynamic_menu">
            <ul>
                <li class="active">
                    <a href="/">Live Auctions</a>
                </li>
                <li data-menu="/closed-auctions/">
                    <a href="/closed-auctions">Closed Auctions</a>
                </li>
                <li data-menu="/winners/">
                    <a href="/winners">Winners</a>
                </li>
                <li data-menu="/how-it-works/">
                    <a href="/how-it-works">How it works</a>
                </li>
                <li data-menu="/contact/">
                    <a href="/contact">Contact us</a>
                </li>
                <li data-menu="/login/">
                    <a href="/login">Sign Up/In</a>
                </li>
            </ul>
        </div>
        <div class="footer_bottom_part">
            <div class="container">
                <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 left_part">
                    <a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                    <span class="pointless">&#9679;</span>
                    <a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 middle_part">
                    <h5>© <?php echo date('Y'); ?> MinBiZeed.com - All rights reserved</h5>
                    <span class="pointless">&#9679;</span>
                    <h5><a href="/privacy-policy">Privacy policy</a></h5>
                    <span class="pointless">&#9679;</span>
                    <h5><a href="/terms-of-use">Terms of use</a></h5>
                </div>
                <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 right_part">
                    <h5>Design <a target="_blank" href="http://pixelinvention.com/"></a></h5>
                </div>
            </div>
        </div>
    </div>
    <div class="popups">
        <p class="popup_msg">
            <span class="action_name"></span>
        </p>
        <p class="popup_msg_2"></p>
    </div>
</footer>
</body>
</html>
