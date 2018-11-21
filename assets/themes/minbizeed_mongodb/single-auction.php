<?php
get_header();
global $current_user;
get_currentuserinfo();
$uid = $current_user->ID;
global $wpdb;
if (have_posts()):
    while (have_posts()) : the_post();

        $ending = get_post_meta(get_the_ID(), "ending", true);

        $pid = get_the_ID();


        $time_increase = get_post_meta(get_the_ID(), 'time_increase', true);

        $price_increase = get_post_meta(get_the_ID(), 'price_increase', true);

        $retail_price = minbizeed_get_show_price(get_post_meta($pid, 'retail_price', true), 2);
        $price_to_reach = minbizeed_get_show_price(get_post_meta($pid, 'minimum_price', true), 2);
        $current_price = minbizeed_get_show_price(get_post_meta($pid, 'current_bid', true), 2);

        $closed = get_post_meta(get_the_ID(), 'closed', true);
        $bidding_ended = 0;

        $ending = get_post_meta(get_the_ID(), 'ending', true);

        $sec = get_post_meta(get_the_ID(), 'ending', true) - current_time('timestamp', 0);

        if ($sec < 0) {
            $bidding_ended = 1;
        }

        $hgb = minbizeed_get_highest_bid_owner_obj($pid);

        $highest_bidder = minbizeed_get_highest_bid_owner($pid);

        $user = get_userdata($highest_bidder);
        $arr = [];
        if ($highest_bidder == false) {
            $highest_bidder = "0";
        } else {
            $highest_bidder = $user->user_login;
        }

        $brand = get_field('brand_name');

        global $current_user;
        get_currentuserinfo();
        $user_login = $current_user->user_login;
        $user_id = $current_user->ID;

        $user_credits = get_user_meta($user_id, 'user_credits', true);

        global $wpdb;
        ///$bids = "select * from " . $wpdb->prefix . "penny_bids where pid='$pid' and uid='$user_id' order by id DESC limit 1";
        //$res = $wpdb->get_results($bids);
        // MongoDB Changes
        
        $filter = ['pid'=>$pid,'uid'=>$user_id];
        $options = ['limit'=>1];        
        $query=new MongoDB\Driver\Query($filter, $options);
        $res = $GLOBALS['mongodb_manager']->executeQuery('minbizeed.pennybids', $query);
        $res=$res->toArray();
        // MongoDB Changes       
        ?>

        <div class="single_product_page" id="<?php echo get_the_id(); ?>">
            <div class="bid" data-id="<?php echo get_the_id(); ?>">
                <div class="container">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 page_identifier">
                        <h1>LIVE AUCTIONS<span>All Bids on items that do not reach the minimum <br> price goal, will be refunded to the user</span>
                        </h1>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 page_pagination">
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3 previous_section">
                            <?php
                            $prev_post = get_previous_post();
                            if (!empty($prev_post)):
                                ?>
                                <a href="<?php echo $prev_post->guid ?>" target="_blank">
                                    <span class="left_dir_arrow">&#9698;</span>
                                    <h4><?php echo $prev_post->post_title ?></h4>
                                </a>
                                <?php
                            endif;
                            ?>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6 category_section">
                            <h3>
                                <?php echo $brand; ?>
                            </h3>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3 next_section">
                            <?php
                            $next_post = get_next_post();
                            if (!empty($next_post)):
                                ?>
                                <a href="<?php echo $next_post->guid ?>" target="_blank">
                                    <h4><?php echo $next_post->post_title ?></h4>
                                    <span class="right_dir_arrow">&#9698;</span>
                                </a>
                                <?php
                            endif;
                            ?>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 product_title">
                        <?php
                        if (strlen(get_the_title()) > 30) {
                            ?>
                            <h1 title="<?php the_title(); ?>">
                                <?php echo mb_substr(the_title($before = '', $after = '', FALSE), 0, 30) . '...'; ?>
                            </h1>
                            <?php
                        } else {
                            ?>
                            <h1>
                                <?php
                                the_title();
                                ?>
                            </h1>
                            <?php
                        }
                        ?>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 product_preview">
                        <div class="large_image_holder">
                            <a href="#" class="search_icon"><i class="fa fa-search-plus" aria-hidden="true"></i></a>
                            <img class="large_image" alt="<?php the_title(); ?>"
                                 src="<?php echo get_the_post_thumbnail_url($pid, 'auction_image_single'); ?>"/>
                            <img class="bids_img" alt="bids"
                                 src="<?php echo get_template_directory_uri(); ?>/images/bids/bids_2.png">
                        </div>
                        <?php
                        if (have_rows('auction_gallery')):
                            ?>
                            <div class="small_images_holder">
                                <?php
                                while (have_rows('auction_gallery')):
                                    the_row('auction_gallery');
                                    $attachment_id = get_sub_field('images');
                                    $size = "auction_image_single";
                                    $image = wp_get_attachment_image_src($attachment_id, $size);
                                    ?>
                                    <div class="small_image_wrapper">
                                        <img class="small_image" alt="<?php the_title(); ?>"
                                             src="<?php echo $image[0]; ?>">
                                    </div>
                                    <?php
                                endwhile;
                                $auction_video = get_field('auction_video');
                                $auction_video_image = get_field('auction_video_image');
                                $size_video_image = "auction_image_single";
                                $size_video_image_exp = wp_get_attachment_image_src($auction_video_image, $size_video_image);
                                if ($auction_video && $auction_video_image) {
                                    ?>
                                    <div class="small_image_wrapper video_presenter">
                                        <img class="small_image" alt="Watch video"
                                             src="<?php echo $size_video_image_exp[0]; ?>">
                                        <div class="overlay">
                                            <a href="<?php echo $auction_video; ?>" class="auction_yt">
                                                <img class="play_button" alt="click to watch"
                                                     src="<?php echo get_template_directory_uri(); ?>/images/arrows/play_button.png">
                                            </a>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                            <?php
                        endif;
                        ?>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 product_information<?php if (count($res) > 0): echo ' all_green'; endif; ?>">
                        <div class="product_information_wrapper">
                            <div class="product_info_wrapper">
                                <h3>Live Bidders</h3>
                                <div class="time_holder">
                                    <div data-auction-id="<?php echo get_the_id(); ?>"
                                         class="auction-current-time remaining"
                                         data-time="<?php
                                         if ($bidding_ended != 1):
                                             echo $sec;
                                         endif;
                                         ?>">
                                        <?php
                                        if (count($res) > 0):
                                            ?>
                                            <img class="clock_img" alt="bids"
                                                 src="<?php echo get_template_directory_uri(); ?>/images/clock_green.png">
                                            <?php
                                        else:
                                            ?>
                                            <img class="clock_img" alt="bids"
                                                 src="<?php echo get_template_directory_uri(); ?>/images/clock_red.png">
                                            <?php
                                        endif;
                                        ?>
                                        <time></time>
                                    </div>
                                    <div class='clear'></div>
                                </div>
                                <div class='clear'></div>
                                <div class="user_wrapper">
                                    <?php
                                    $closed = get_post_meta($pid, 'closed', true);
                                    $highest_bidder_id = minbizeed_get_highest_bid_owner_obj(get_the_ID())->uid;
                                    $country = get_user_meta($highest_bidder_id, '_country_', true);
                                    $post = get_post($pid);
                                    global $wpdb;
                                    //$bids = "select * from " . $wpdb->prefix . "penny_bids where pid='$pid' order by id DESC limit 7";
                                    //$res = $wpdb->get_results($bids);

                                    // MongoDB Changes
                                    $filter = ['pid'=>$pid];
                                    $options = ['sort' => ['bid' => -1],'limit'=>7];                                    
                                    $query=new MongoDB\Driver\Query($filter, $options);
                                    $res = $GLOBALS['mongodb_manager']->executeQuery('minbizeed.pennybids', $query);
                                    $res=$res->toArray();
                                    // MongoDB Changes     

                                    if (count($res) > 0) {
                                        $z = 0;
                                        foreach ($res as $row) {
                                            $z++;
                                            $user = get_userdata($row->uid);
                                            $user_id = $user->ID;
                                            $user_country = get_user_meta($user->ID, '_country_', true);
                                            ?>
                                            <div class="each_user"<?php if ($z != 1):echo " disabled_user";endif; ?>>
                                                <h3><?php echo $user->user_login; ?></h3>
                                                <div class="user_info">
                                                    <div class="user_country">
                                                        <?php
                                                        if ($country) {
                                                            ?>
                                                            <span><?php echo $user_country; ?></span>
                                                            <span class="bfh-countries"
                                                                  data-country="<?php echo $user_country; ?>"
                                                                  data-flags="true">
                                                            </span>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                    <div class="user_bids">
                                                        <span>$<?php echo minbizeed_get_show_price($row->bid); ?></span>
                                                    </div>
                                                </div>
                                                <div class='clear'></div>
                                            </div>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <div class="each_user">
                                            <h3 class="no_bidders">No Bidders Yet</h3>
                                            <div class='clear'></div>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="bidding_info_wrapper">
                                <div class="your_bids">
                                    <span>You have</span>
                                    <span class="bids_ribbon"><span class="landing_balance"><?php echo($user_credits ? $user_credits : "0"); ?></span>Bids<span class="fold"></span></span>
                                </div>
                                <div class="live_bids_counter">
                                    <span class="bid_counter">$<?php echo $current_price; ?></span>
                                    <span>Each bid raises the price by $<?php if ($price_increase): echo $price_increase;
                                        else: "0.01"; endif; ?> </span>
                                </div>
                                <div class="bid_cost">
                                    <span>Bid Cost</span>
                                    
                                    <span class="bids_ribbon"><?php if ($time_increase): echo "x<span class='price_increase'>" . $time_increase . "</span> Bids"; endif; ?>
                                        <span class="fold"></span></span>
                                </div>
                                <div class='clear'></div>
                                <img class="shadow_line" alt="shadow_line"
                                     src="<?php echo get_template_directory_uri(); ?>/images/shadow_line.png">
                            </div>
                            <div class='bid_now_wrapper'>
                                <?php
                                if (count($res) > 0):
                                    ?>
                                    <img class="eye_img" alt="bids"
                                         src="<?php echo get_template_directory_uri(); ?>/images/eye_green.png">
                                    <?php
                                else:
                                    ?>
                                    <img class="eye_img" alt="bids"
                                         src="<?php echo get_template_directory_uri(); ?>/images/eye_red.png">
                                    <?php
                                endif;
                                ?>
                                <div class="auction-current-bidnow">
                                    <?php
                                    if (is_user_logged_in()) {
                                        if ($bidding_ended != 1) {
                                            $bid_auction = get_field('bid_auction');
                                            ?>
                                            <a href="#"
                                               class="bid_now_button<?php if ($bid_auction): echo ' bid_auction';endif; ?> bid_now mm_bid_mm"
                                               rel="<?php the_ID(); ?>">
                                                BID NOW
                                            </a>
                                            <input type="hidden" id="balance2"
                                                   value="<?php echo get_user_meta($user_ID, 'user_credits', true) ?>"/>
                                            <img class="bid_loader"
                                                 src="<?php echo get_template_directory_uri(); ?>/images/ajax_loader.gif"
                                                 alt="Loading"/>
                                            <?php
                                        } else {
                                            ?>
                                            <a href="#" class="bid_now_button no_link_btn" rel="<?php the_ID(); ?>">
                                                BIDDING ENDED
                                            </a>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <a class="bid_now_button bid_not_logged_in mm_bid_mm" rel="<?php the_ID(); ?>"
                                           href='#'>BID NOW</a>
                                        <?php
                                    }
                                    ?>
                                </div>

                                <img class="exclamation_img" alt="bids"
                                     src="<?php echo get_template_directory_uri(); ?>/images/exclamation.png">
                            </div>
                            <div class="pricing_wrapper">
                                <div class="prices_wrapper">
                                    <span class="triangle-right"><span
                                                class="price_label">Retail Price</span> $<?php echo $retail_price; ?></span>
                                    <h3>$<?php echo $price_to_reach; ?> <span class="price_label">Price to Reach</span>
                                    </h3>
                                </div>
                            </div>
                            <div class="social_media_wrapper">
                                <span class="share">SHARE</span>
                                <a href="#" class="facebook_wrap"
                                   onclick='event.preventDefault(); window.open("http://www.facebook.com/sharer/sharer.php?s=100&p[url]=<?php echo urlencode(get_permalink()); ?>&p[title]=<?php echo urlencode('Win ' . get_the_title() . ' for ' . $price_to_reach); ?>", "Facebook-dialog", "width=626,height=436")'>
                                    <i class="fa fa-facebook" aria-hidden="true"></i>
                                </a>
                                <a href="#" class="twitter_wrap"
                                   onclick='event.preventDefault(); window.open("http://twitter.com/intent/tweet/?text=<?php echo urlencode('Win ' . get_the_title() . ' for ' . $price_to_reach); ?>&url=<?php echo get_permalink(); ?>", "Twitter-dialog", "width=626,height=436")'>
                                    <i class="fa fa-twitter" aria-hidden="true"></i>
                                </a>
                                <a href="#" class="google_plus_wrap"
                                   onclick='event.preventDefault(); window.open("https://plus.google.com/share?url=<?php echo urlencode(get_permalink()); ?>", "GooglePlus-dialog", "width=626,height=436")'>
                                    <i class="fa fa-google-plus" aria-hidden="true"></i>
                                </a>
                                <a class='whatsapp_wrap' href='whatsapp://send'
                                   data-text="<?php echo urlencode(get_the_title()); ?>"
                                   data-href='<?php echo urlencode(get_permalink()); ?>'>
                                    <i class="fa fa-whatsapp" aria-hidden="true"></i>
                                </a>
                                <a class="link_wrap to_copy" href="#"><i class="fa fa-link" aria-hidden="true"></i></a>
                                <input type="hidden" value="<?php echo get_the_permalink(); ?>" id="share_link"/>
                                <div class="fb_like_cont">
                                    <div class="fb-like" data-href="/" data-layout="button_count" data-action="like"
                                         data-size="small" data-show-faces="false" data-share="false"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    $auction_info_lines = get_field('auction_info_lines');
                    if ($auction_info_lines):
                        ?>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 product_tech_specs">
                            <div class="tech_specs_wrapper">
                                <h3>Tech Specs</h3>
                                <div class="all_specs_holder">
                                    <?php
                                    echo $auction_info_lines;
                                    ?>
                                </div>
                                <h5 class="read_more_button" style="display: none">
                                    <span class="button_button show_more">more</span>
                                    <span class="dropdown_arrow">&#9698;</span>
                                </h5>
                            </div>
                        </div>
                        <?php
                    endif;
                    ?>
                </div>
                <div class="page_overlay"></div>
            </div>
        </div>

        <script>
            jQuery(document).ready(function () {
                var p_height = jQuery('.tech_specs_wrapper .all_specs_holder p').height();
                if (p_height > 275) {
                    jQuery('.read_more_button').show();
                }
            });
        </script>
        <?php
    endwhile; // end of the loop.
endif;
get_footer();