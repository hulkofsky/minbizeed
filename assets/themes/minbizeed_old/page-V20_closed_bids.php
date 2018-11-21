<?php
/*
 * Template Name: V20 Closed Bids
 *
 */
get_header();
?>
    <div class="live_bids_page closed_bids_page">
        <div class="page_identifier">
            <h2>CLOSED BIDS</h2>
        </div>
        <?php
        $ppp=8;
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $offset = ($ppp * $paged) - $ppp;
        $args_2 = array(
            'paged' => $paged,
            'offset' => $offset,
            'post_per_page' => $ppp,
            'post_type' => 'auction',
            'order' => 'ASC',
            'meta_query' => array(
                array(
                    'key' => 'closed',
                    'value' => '1',
                    'compare' => '='
                )
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

                        $price_increase = get_post_meta($pid, 'price_increase', true);

                        $retail_price = minbizeed_get_show_price(get_post_meta($pid, 'retail_price', true), 2);
                        $price_to_reach = minbizeed_get_show_price(get_post_meta($pid, 'minimum_price', true), 2);
                        $current_price = minbizeed_get_show_price(get_post_meta($pid, 'current_bid', true), 2);

                        $winner = get_post_meta($pid, 'winner', true);

                        $winner_user = get_userdata($winner);
                        $winner_user_name = $winner_user->display_name;


                        $int_highest_bid = get_post_meta(get_the_ID(), 'current_bid', true);
                        $int_retail_price = get_post_meta(get_the_ID(), 'retail_price', true);

                        $savings = $int_retail_price - $int_highest_bid;

                        ?>
                        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 live_bids_wrapper <?php echo $color_combination[$z]; ?>">
                            <article class="featured_bid bid" data-auction-id="<?php echo $pid; ?>"
                                     data-id="<?php echo $pid; ?>">
                                <div class="live_bids_wrap">
                                    <a href="#" class="no_link">
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


                                        <a href="<?php the_permalink(); ?>" class="no_link">
                                            <h3>Price to reach: <?php echo $price_to_reach; ?></h3>
                                            <h3>Retail Price: <?php echo $retail_price; ?></h3>
                                            <h3 class="price_reached"><?php echo $current_price; ?></h3>
                                            <img alt="icon4" src="<?php echo get_template_directory_uri(); ?>/images/icons/win-game-icon.png">
                                            <span class="won_by">WON BY <?php echo $winner_user_name; ?></span>
                                        </a>

                                        <a href="#" class="no_link">
                                            <span class="last_bidder">
                                                Saved: <?php echo $savings; ?>$
                                            </span>
                                        </a>

                                        <?php
                                        if($winner){
                                            ?>
                                            <span title="This auction is won!" class="ending_soon won_passed">WON</span>
                                            <?php
                                        }else{
                                            ?>
                                            <span title="This auction passed!" class="ending_soon closed_passed">PASSED</span>
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
                    bootstrap_pagination($paged,$pages);
                    ?>
                    <div class="clear"></div>
                </div>
            </div>
            <div class="page_overlay"></div>
            <?php
        endif;
        ?>
    </div>
<?php
get_footer();
