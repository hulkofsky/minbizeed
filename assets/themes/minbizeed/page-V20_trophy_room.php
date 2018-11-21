<?php
/*
 * Template: My Account Trophy room Template
 * Template Name: V20 My Account Trophy room Template
 */

if (!is_user_logged_in()):
    wp_redirect('/?msg=login&redirect_url=/my-account');
    exit;
else:
    get_header();
    global $current_user;
    get_currentuserinfo();
    $uid = $current_user->ID;
    ?>
    <div class="live_bids_page account_settings_page user_bids_won_page">
        <div class="page_identifier">
            <h2>MY ACCOUNT</h2>
            <ul>
                <li><a href="/my-account">ACCOUNT SETTINGS</a></li>
                <li><a href="/my-account/bids-history">BIDS HISTORY</a></li>
                <li class="active"><a href="/my-account/trophy-room/">BIDS WON</a></li>
                <li><?php echo do_shortcode('[wppb-logout text="" redirect_url="/" link_text="LOGOUT"]') ?></li>
            </ul>
        </div>

        <?php

        if ($_GET['msg'] == "sig_error") {
            ?>
            <p class="wppb-error no_margins">
                Something went wrong before sending the payment to the bank, please try paying for the item again.
            </p>
            <?php
        }

        $winner_0 = array(
            'key' => 'winner',
            'value' => $uid,
            'compare' => '='
        );

        $paid_0 = array(
            'key' => 'winner_paid',
            'value' => "0",
            'compare' => '='
        );

        $args_0 = array(
            'post_type' => 'auction',
            'order' => 'DESC',
            'orderby' => 'meta_value_num',
            'meta_key' => 'closed_date',
            'posts_per_page' => -1,
            'meta_query' => array($winner_0, $paid_0)
        );

        $query_0 = new WP_Query($args_0);

        if ($query_0->have_posts()):
            $z = 0;
            ?>
            <div class="live_bids_section shipped_items">
                <div class="page_identifier_sub">
                    <h3>WON ITEMS</h3>
                </div>
                <div class="container-fluid no_padding">
                    <?php
                    while ($query_0->have_posts()):
                        $query_0->the_post();

                        $pid = get_the_ID();

                        $closed = get_post_meta(get_the_ID(), 'closed', true);
                        $highest_bid = minbizeed_get_show_price(get_post_meta($pid, 'current_bid', true));
                        $price_increase = get_post_meta($pid, 'price_increase', true);

                        $closed = get_post_meta($pid, 'closed', true);

                        $retail_price = minbizeed_get_show_price(get_post_meta($pid, 'retail_price', true), 2);
                        $price_to_reach = minbizeed_get_show_price(get_post_meta($pid, 'minimum_price', true), 2);
                        $current_price = minbizeed_get_show_price(get_post_meta($pid, 'current_bid', true), 2);

                        $time_increase = get_post_meta($pid, 'time_increase', true);

                        $highest_bidder_id = minbizeed_get_highest_bid_owner_obj($pid)->uid;
                        $country = get_user_meta($highest_bidder_id, '_country_', true);

                        $int_highest_bid = get_post_meta(get_the_ID(), 'current_bid', true);
                        $int_retail_price = get_post_meta(get_the_ID(), 'retail_price', true);

                        $savings = $int_retail_price - $int_highest_bid;
                        ?>
                        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 live_bids_wrapper">
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


                                        <a href="#" class="no_link">
                                            <h3>Price to reach: <?php echo $price_to_reach; ?></h3>
                                            <h3>Retail Price: <?php echo $retail_price; ?></h3>
                                            <h3 class="price_reached"><?php echo $current_price; ?></h3>
                                        </a>

                                        <a href="#" class="no_link">
                                            <span class="last_bidder">
                                                You Saved: <?php echo $savings; ?>$
                                            </span>
                                        </a>


                                        <?php
                                        $shipping_info = get_user_meta($uid, 'ship_inf', true);
                                        $city = get_user_meta($uid, 'city', true);
                                        $state = get_user_meta($uid, 'state', true);
                                        $country = get_user_meta($uid, 'country', true);
                                        if ($shipping_info && $city && $state && $country) {
                                            ?>
                                            <div class="bid_now_wrapper">
                                                <a href="/my-account/purchase-item/?pid=<?php the_ID(); ?>"
                                                   class="bid_now_button">
                                                    <span class="bid_now_label">PAY FOR THE ITEM</span>
                                                </a>
                                            </div>
                                            <?php
                                        } else {
                                            ?>
                                            <div class="bid_now_wrapper">
                                                <a href="#"
                                                   class="bid_now_button no_shpg_msg_link">
                                                    <span class="bid_now_label">PAY FOR THE ITEM</span>
                                                </a>
                                            </div>
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
                    ?>
                    <div class="profile_inside">
                        <div class="pop_up_editor">
                            <h3>Please update the below info before paying for the item</h3>
                            <p class="return_msg" style="display: none;"></p>
                            <div class="form-group">
                                <label for="city">City</label>

                                <input id="city" class="form-control" type="text" name="city"<?php if(get_user_meta($uid,'city')[0]): echo ' value="'.get_user_meta($uid,'city')[0].'"'; endif; ?> />

                                <input id="user_id" class="form-control"
                                       value="<?php echo $uid; ?>" type="hidden"
                                       name="user_id"/>
                                <input id="redirect_url" class="form-control"
                                       value="/my-account/purchase-item/?pid=<?php the_ID(); ?>"
                                       type="hidden" name="redirect_url"/>
                            </div>
                            <div class="form-group">
                                <label for="state">State</label>
                                <input id="state" class="form-control" type="text"
                                       name="state"<?php if(get_user_meta($uid,'state')[0]): echo ' value="'.get_user_meta($uid,'state')[0].'"'; endif; ?>/>
                            </div>
                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea id="address" class="form-control"
                                          name="address"><?php if(get_user_meta($uid,'ship_inf')[0]): echo get_user_meta($uid,'ship_inf')[0]; endif; ?></textarea>
                            </div>
                            <div class="form-group">
                                <?php
                                $ajax_nonce = wp_create_nonce("update_shpg_info");
                                $ajax_link = admin_url('admin-ajax.php?action=update_shpg_info&nonce=' . $ajax_nonce);
                                ?>
                                <a href="#" data-ajax_link="<?php echo $ajax_link; ?>"
                                   class="submit_btn bid_now_button update_shpg_info">
                                    <span class="bid_now_label">SUBMIT</span>
                                </a>
                                <img class="ajax_loader" style="display: none;"
                                     src="<?php echo get_template_directory_uri(); ?>/images/ajax_loader.gif"
                                     alt="Loader"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script type="text/javascript">
                $(".no_shpg_msg_link").click(function (e) {
                    e.preventDefault();
                    $.magnificPopup.open({
                        items: {
                            src: '.pop_up_editor'
                        },
                        type: 'inline',
                        closeOnBgClick: true,
                        closeMarkup: ''
                    });
                });

                $('.submit_btn.update_shpg_info').click(function () {
                    var ajax_link = $(this).attr('data-ajax_link');

                    var user_id = $('.pop_up_editor #user_id').val();

                    var redirect_url = $('.pop_up_editor #redirect_url').val();

                    var city = $('.pop_up_editor #city').val();
                    var state = $('.pop_up_editor #state').val();
                    var address = $('.pop_up_editor #address').val();

                    var return_msg = jQuery('.pop_up_editor .return_msg');

                    var loader = jQuery('.pop_up_editor .ajax_loader');

                    loader.slideDown();

                    if (ajax_link && city && state && address) {
                        $.ajax({
                            type: "post",
                            dataType: "json",
                            url: ajax_link,
                            data: {
                                user_id: user_id,
                                city: city,
                                state: state,
                                address: address,
                            },
                            success: function (response) {
                                if (response.type == "success") {

                                    loader.slideUp();

                                    return_msg.addClass('success');
                                    return_msg.html('Info Updated! Redirecting...');
                                    return_msg.slideDown();

                                    setTimeout(
                                        function () {
                                            window.location.replace(redirect_url);
                                        }, 2000);

                                } else if (response.type == "error") {

                                    loader.slideUp();
                                    return_msg.addClass('error');
                                    return_msg.html('An error occured, please refresh and try again');
                                    return_msg.slideDown();

                                } else {

                                    loader.slideUp();
                                    return_msg.addClass('error');
                                    return_msg.html('An error occured, please refresh and try again');
                                    return_msg.slideDown();

                                }
                            }
                        });
                    } else {
                        loader.slideUp();
                        return_msg.addClass('error');
                        return_msg.html('Please enter all fields!');
                        return_msg.slideDown();
                    }
                });

            </script>

            <?php
        endif;
        ?>


        <?php
        $winner = array(
            'key' => 'winner',
            'value' => $uid,
            'compare' => '='
        );

        $paid = array(
            'key' => 'winner_paid',
            'value' => "1",
            'compare' => '='
        );


        $shipped = array(
            'key' => 'shipped',
            'value' => "1",
            'compare' => '='
        );


        $args = array(
            'post_type' => 'auction',
            'order' => 'DESC',
            'orderby' => 'meta_value_num',
            'meta_key' => 'closed_date',
            'posts_per_page' => 4,
            'meta_query' => array($winner, $paid, $shipped)
        );

        $query = new WP_Query($args);

        if ($query->have_posts()):
            $z = 0;
            ?>
            <div class="live_bids_section shipped_items">
                <div class="page_identifier_sub">
                    <h3>SHIPPED ITEMS</h3>
                </div>
                <div class="container-fluid no_padding">
                    <?php
                    while ($query->have_posts()):
                        $query->the_post();

                        $pid = get_the_ID();

                        $closed = get_post_meta(get_the_ID(), 'closed', true);
                        $highest_bid = minbizeed_get_show_price(get_post_meta($pid, 'current_bid', true));
                        $price_increase = get_post_meta($pid, 'price_increase', true);

                        $closed = get_post_meta($pid, 'closed', true);

                        $retail_price = minbizeed_get_show_price(get_post_meta($pid, 'retail_price', true), 2);
                        $price_to_reach = minbizeed_get_show_price(get_post_meta($pid, 'minimum_price', true), 2);
                        $current_price = minbizeed_get_show_price(get_post_meta($pid, 'current_bid', true), 2);

                        $time_increase = get_post_meta($pid, 'time_increase', true);

                        $highest_bidder_id = minbizeed_get_highest_bid_owner_obj($pid)->uid;
                        $country = get_user_meta($highest_bidder_id, '_country_', true);

                        $int_highest_bid = get_post_meta(get_the_ID(), 'current_bid', true);
                        $int_retail_price = get_post_meta(get_the_ID(), 'retail_price', true);

                        $savings = $int_retail_price - $int_highest_bid;
                        ?>
                        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 live_bids_wrapper">
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


                                        <a href="#" class="no_link">
                                            <h3>Price to reach: <?php echo $price_to_reach; ?></h3>
                                            <h3>Retail Price: <?php echo $retail_price; ?></h3>
                                            <h3 class="price_reached"><?php echo $current_price; ?></h3>
                                        </a>

                                        <a href="#" class="no_link">
                                                        <span class="last_bidder">
                                                            You Saved: <?php echo $savings; ?>$
                                                        </span>
                                        </a>

                                    </div>
                                </div>
                            </article>
                        </div>
                        <?php
                        $z++;
                    endwhile;
                    ?>
                </div>
            </div>
            <?php
        endif;
        ?>


        <?php

        $winner_2 = array(
            'key' => 'winner',
            'value' => $uid,
            'compare' => '='
        );

        $paid_2 = array(
            'key' => 'winner_paid',
            'value' => "1",
            'compare' => '='
        );


        $shipped_2 = array(
            'key' => 'shipped',
            'value' => "0",
            'compare' => '='
        );


        $args_2 = array(
            'post_type' => 'auction',
            'order' => 'DESC',
            'orderby' => 'meta_value_num',
            'meta_key' => 'closed_date',
            'posts_per_page' => 4,
            'meta_query' => array($winner_2, $paid_2, $shipped_2)
        );

        $query_2 = new WP_Query($args_2);

        if ($query_2->have_posts()):
            $l = 0;
            ?>
            <div class="live_bids_section shipped_items">
                <div class="page_identifier_sub">
                    <h3>NOT SHIPPED ITEMS</h3>
                </div>
                <div class="container-fluid no_padding">
                    <?php
                    while ($query_2->have_posts()):
                        $query_2->the_post();

                        $pid = get_the_ID();

                        $closed = get_post_meta(get_the_ID(), 'closed', true);
                        $highest_bid = minbizeed_get_show_price(get_post_meta($pid, 'current_bid', true));
                        $price_increase = get_post_meta($pid, 'price_increase', true);

                        $closed = get_post_meta($pid, 'closed', true);

                        $retail_price = minbizeed_get_show_price(get_post_meta($pid, 'retail_price', true), 2);
                        $price_to_reach = minbizeed_get_show_price(get_post_meta($pid, 'minimum_price', true), 2);
                        $current_price = minbizeed_get_show_price(get_post_meta($pid, 'current_bid', true), 2);

                        $time_increase = get_post_meta($pid, 'time_increase', true);

                        $highest_bidder_id = minbizeed_get_highest_bid_owner_obj($pid)->uid;
                        $country = get_user_meta($highest_bidder_id, '_country_', true);

                        $int_highest_bid = get_post_meta(get_the_ID(), 'current_bid', true);
                        $int_retail_price = get_post_meta(get_the_ID(), 'retail_price', true);

                        $savings = $int_retail_price - $int_highest_bid;
                        ?>
                        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 live_bids_wrapper">
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


                                        <a href="#" class="no_link">
                                            <h3>Price to reach: <?php echo $price_to_reach; ?></h3>
                                            <h3>Retail Price: <?php echo $retail_price; ?></h3>
                                            <h3 class="price_reached"><?php echo $current_price; ?></h3>
                                        </a>

                                        <a href="#" class="no_link">
                                                        <span class="last_bidder">
                                                            You Saved: <?php echo $savings; ?>$
                                                        </span>
                                        </a>

                                    </div>
                                </div>
                            </article>
                        </div>
                        <?php
                        $l++;
                    endwhile;
                    ?>
                </div>
            </div>
            <?php
        endif;
        ?>
        <div class="page_overlay"></div>
    </div>
    <div class="clear"></div>
    <?php
    get_footer();
endif;

