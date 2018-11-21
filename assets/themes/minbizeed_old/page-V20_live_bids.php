<?php
/*
 * Template Name: V20 Live Bids
 *
 */
get_header();
?>
    <div class="live_bids_page">
        <div class="page_identifier">
            <h2>LIVE BIDS</h2>
        </div>
        <?php
        $ppp = 8;
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $offset = ($ppp * $paged) - $ppp;
        $args_2 = array(
            'paged' => $paged,
            'offset' => $offset,
            'post_per_page' => $ppp,
            "post_type" => "auction",
            'order' => 'ASC',
            'meta_key' => 'ending',
            'orderby' => 'meta_value_num',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'closed',
                    'value' => '0',
                    'compare' => '='
                ),
            )
        );
        $wp_query = new WP_Query($args_2);
        if ($wp_query->have_posts()):
            $z = 0;
            $color_combination = array('cyan', 'blue', 'pink');
            ?>
            <div class="live_bids_section">
                <div class="container-fluid no_padding">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 section_identifier">
                        <div class="category_filter_wrapper" style="display: none">
                            <div class="first_shown_wrap">
                                <span class="first_shown" data-selected="CATEGORY">CATEGORY</span>
                                <span class="dropdown_arrow">
                                    <i class="fa fa-angle-down" aria-hidden="true"></i>
                                </span>
                            </div>
                            <ul class="categories_list">
                                <li data-to-select="ALL">ALL</li>
                                <li data-to-select="ELECTRONICS">ELECTRONICS</li>
                                <li data-to-select="CARS">CARS</li>
                                <li data-to-select="SPORTS">SPORTS</li>
                                <li data-to-select="HOUSE">HOUSE</li>
                                <li data-to-select="GADGETS">GADGETS</li>
                            </ul>
                        </div>
                    </div>
                    <?php
                    while ($wp_query->have_posts()):
                        $wp_query->the_post();

                        $pid = get_the_ID();

                        $closed = get_post_meta(get_the_ID(), 'closed', true);
                        $highest_bid = minbizeed_get_show_price(get_post_meta($pid, 'current_bid', true));
                        $price_increase = get_post_meta($pid, 'price_increase', true);

                        $sec = get_post_meta($pid, 'ending', true) - current_time('timestamp', 0);
                        if ($sec < 0) {
                            $bidding_ended = 1;
                        } else {
                            $bidding_ended = 0;
                        }


                        if (strtotime($sec) > strtotime("-30 minutes")) {
                            $ending_soon = 1;
                        } else {
                            $ending_soon = 0;
                        }

                        $closed = get_post_meta($pid, 'closed', true);

                        $retail_price = minbizeed_get_show_price(get_post_meta($pid, 'retail_price', true), 2);
                        $price_to_reach = minbizeed_get_show_price(get_post_meta($pid, 'minimum_price', true), 2);
                        $current_price = minbizeed_get_show_price(get_post_meta($pid, 'current_bid', true), 2);

                        $time_increase = get_post_meta($pid, 'time_increase', true);

                        $highest_bidder_id = minbizeed_get_highest_bid_owner_obj($pid)->uid;
                        $country = get_user_meta($highest_bidder_id, '_country_', true);
                        global $wpdb;
                        $bids = "select * from " . $wpdb->prefix . "penny_bids where pid='$pid' order by id DESC limit 1";
                        $res = $wpdb->get_results($bids);

                        ?>
                        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 live_bids_wrapper <?php echo $color_combination[$z]; ?>">
                            <article class="featured_bid bid" data-auction-id="<?php echo $pid; ?>"
                                     data-id="<?php echo $pid; ?>">
                                <div class="live_bids_wrap">
                                    <a href="<?php the_permalink(); ?>">
                                        <h3 class="product_title" title="<?php the_title(); ?>">
                                            <?php
                                            if (strlen(get_the_title()) > 28) {
                                                echo mb_substr(the_title($before = '', $after = '', FALSE), 0, 28) . '...';
                                            } else {
                                                the_title();
                                            }
                                            ?>
                                        </h3>
                                        <div class="image_holder">
                                            <img alt="<?php the_title(); ?>"
                                                 src="<?php echo get_the_post_thumbnail_url($pid, 'home_top_slider'); ?>">
                                        </div>
                                    </a>
                                    <div class="description_holder">


                                        <a href="<?php the_permalink(); ?>">
                                            <h3>Price to reach: <?php echo $price_to_reach; ?></h3>
                                            <h3>Retail Price: <?php echo $retail_price; ?></h3>
                                            <h3 class="price_reached"><?php echo $current_price; ?></h3>

                                            <time class="remaining time_holder" data-time="<?php
                                            if ($bidding_ended != 1):
                                                echo $sec;
                                            endif;
                                            ?>">
                                                <span class="time_holder"></span>
                                            </time>
                                        </a>


                                        <div class="bid_now_wrapper">
                                            <a href="<?php the_permalink(); ?>" class="bid_now_button">
                                                <?php
                                                if ($time_increase) {
                                                    ?>
                                                    <span class="bids_number"><?php echo " x " . $time_increase; ?>
                                                        Bids</span>
                                                    <?php
                                                }
                                                ?>
                                                <span class="bid_now_label">BID NOW</span>
                                            </a>
                                        </div>


                                        <?php
                                        if (count($res) > 0) {
                                            foreach ($res as $row) {
                                                $user = get_userdata($row->uid);
                                                $user_id = $user->ID;
                                                ?>
                                                <a href="<?php the_permalink(); ?>">
                                                        <span class="last_bidder">
                                                            Last bidder: <?php echo $user->user_login; ?>
                                                        </span>
                                                </a>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <a href="<?php the_permalink(); ?>">
                                                        <span class="last_bidder">
                                                            No bids yet
                                                        </span>
                                            </a>
                                            <?php
                                        }

                                        if ($ending_soon == 1) {
                                            ?>
                                            <span title="This auction will end soon! Hurry Up"
                                                  class="ending_soon">ENDING SOON</span>
                                            <?php
                                        }
                                        ?>

                                    </div>
                                </div>
                            </article>
                        </div>
                        <?php
                        $z++;
                    endwhile;
                    $pages = $wp_query->max_num_pages;
                    bootstrap_pagination($paged, $pages);
                    ?>
                    <div class="clear"></div>
                </div>
            </div>
            <div class="page_overlay"></div>
            <?php
        else:
            ?>
            <h2 class="no_active">NO ACTIVE BIDS YET</h2>
            <?php
        endif;
        ?>
    </div>
<?php
get_footer();
