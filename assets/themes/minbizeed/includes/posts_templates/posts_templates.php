<?php


function minbizeed_get_open_auctions( $pid, $is_example ) {
	$sec = get_post_meta( $pid, 'ending', true ) - current_time( 'timestamp', 0 );
	if ( $sec < 0 ) {
		$bidding_ended = 1;
	} else {
		$bidding_ended = 0;
	}

	if ( strtotime( $sec ) > strtotime( "-30 minutes" ) ) {
		$ending_soon = 1;
	} else {
		$ending_soon = 0;
	}

	$closed = get_post_meta( $pid, 'closed', true );

	$price_increase = get_post_meta( $pid, 'price_increase', true );

	$retail_price   = minbizeed_get_show_price( get_post_meta( $pid, 'retail_price', true ), 2 );
	$price_to_reach = minbizeed_get_show_price( get_post_meta( $pid, 'minimum_price', true ), 2 );
	$current_price  = minbizeed_get_show_price( get_post_meta( $pid, 'current_bid', true ), 2 );

	$time_increase = get_post_meta( $pid, 'time_increase', true );

	$brand = get_field( 'brand_name' );

	$item_price_range = get_field( 'item_price_range' );

	$item_price_desc = get_field( 'item_short_description' );

	$current_user = wp_get_current_user();
	$user_id      = $current_user->ID;

	global $wpdb;
	$bids = "select * from " . $wpdb->prefix . "penny_bids where pid='$pid' and uid='$user_id' order by id DESC limit 1";
	$res  = $wpdb->get_results( $bids );

	$g_bids = "select * from " . $wpdb->prefix . "penny_bids where pid='$pid' order by id DESC limit 1";
	$g_res  = $wpdb->get_results( $g_bids );

	?>
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12<?php if ( $is_example ): echo " auction_example";endif; ?> auction_wrapper<?php if ( count( $res ) > 0 ): echo ' all_green'; endif; ?>">
        <a href="<?php echo get_the_permalink( $pid ) ?>">
            <article class="featured_bid bid" data-auction-id="<?php echo $pid; ?>"
                     data-id="<?php echo $pid; ?>">
                <div class="auction_wrap">
                    <h3 class="category_title"<?php if ( $brand ): echo ' title="' . $brand . '"';endif; ?>>
						<?php
						if ( strlen( $brand ) > 20 ) {
							echo mb_substr( $brand, 0, 20 ) . '...';
						} else {
							echo $brand;
						}
						?>
                        <span class="bids_ribbon"><?php if ( $time_increase ): echo "x" . "<span class='price_increase'>" . $time_increase . "</span> Bids"; endif; ?>
                            <span class="fold"></span>
                                        </span>
                    </h3>
					<?php
					if ( strlen( get_the_title() ) > 30 ) {
						?>
                        <h3 class="product_title"
                            title="<?php the_title(); ?>"><?php echo mb_substr( the_title( $before = '', $after = '', false ), 0, 30 ) . '...'; ?></h3>
						<?php
					} else {
						?>
                        <h3 class="product_title">
							<?php
							the_title();
							?>
                        </h3>
						<?php
					}
					?>
                    <div class="pricing_wrapper">
                        <h6>Retail Price</h6>
                        <h6>Price to Reach</h6>
                        <div class="prices_wrapper">
                            <span class="triangle-right">$<?php echo $retail_price; ?></span>
                            <h3>$<?php echo $price_to_reach; ?></h3>
                        </div>
                    </div>
                    <div class="image_holder">
	                    <?php
	                    if ( has_post_thumbnail( $pid ) ) {
		                    ?>
                            <img class="top_arrow img-responsive" alt="<?php the_title(); ?>"
                                 src="<?php echo get_the_post_thumbnail_url( $pid, 'auction_image' ); ?>"/>
		                    <?php
	                    } else {
		                    ?>
                            <img class="top_arrow img-responsive" alt="<?php the_title(); ?>"
                                 src="<?php echo get_template_directory_uri(); ?>/images/default.png"/>
		                    <?php
	                    }
	                    
						if ( count( $res ) > 0 ) {
							if ( $item_price_range ) {
								?>
                                <img class="bids_img <?php echo( $item_price_range == 4 ? 'fourth_bid' : false ); ?>"
                                     alt="bids"
                                     src="<?php echo get_template_directory_uri(); ?>/images/bids/bids_green_<?php echo $item_price_range; ?>.png"/>
								<?php
							}
						} else {
							if ( $item_price_range ) {
								?>
                                <img class="bids_img <?php echo( $item_price_range == 4 ? 'fourth_bid' : false ); ?>"
                                     alt="bids"
                                     src="<?php echo get_template_directory_uri(); ?>/images/bids/bids_<?php echo $item_price_range; ?>.png"/>
								<?php
							}
						}
						?>
                    </div>
                    <div class="time_holder">
						<?php
						if ( count( $res ) > 0 ):
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
                        <h3>
                            <time class="remaining time_holder" data-time="<?php
							if ( $bidding_ended != 1 ):
								echo $sec;
							endif;
							?>">
                                <span class="time_holder"></span>
                            </time>
                        </h3>
                        <div class='clear'></div>
                    </div>
                    <div class="hr_horizontal"></div>

					<?php
					if ( count( $g_res ) > 0 ) {
						foreach ( $g_res as $g_row ) {
							$user = get_userdata( $g_row->uid );
							?>
                            <div class="description_holder last_bidder">
                                Last bidder: <?php echo $user->user_login; ?>
                            </div>
							<?php
						}
					} else {
						?>
                        <div class="description_holder last_bidder">
                            No bids yet

                        </div>
						<?php
					}

					//<?php
					//                if (strlen($item_price_desc) > 64) {
					//
					?>
                    <!--                    <div class="description_holder" title="--><?php //echo $item_price_desc;
					?><!--">-->
                    <!--                        <p>--><?php //echo mb_substr($item_price_desc, 0, 64) . '...';
					?><!--</p>-->
                    <!--                    </div>-->
                    <!--                    --><?php
					//                } else {
					//
					?>
                    <!--                    <div class="description_holder">-->
                    <!--                        <p>--><?php //echo $item_price_desc;
					?><!--</p>-->
                    <!--                    </div>-->
                    <!--                    --><?php
					//                }
					//
					?>
                    <div class='biding_holder'>
						<?php
						if ( count( $res ) > 0 ):
							?>
                            <!--                        <img class="eye_img" alt="bids"-->
                            <!--                             src="--><?php //echo get_template_directory_uri();
							?><!--/images/eye_green.png">-->
						<?php
						else:
							?>
                            <!--                        <img class="eye_img" alt="bids"-->
                            <!--                             src="--><?php //echo get_template_directory_uri();
							?><!--/images/eye_grey.png">-->
						<?php
						endif;
						?>
                        <h3 class="price_reached">$<?php echo $current_price; ?></h3>
                        <div class='clear'></div>
                    </div>
                </div>

				<?php
				if ( $bidding_ended != 1 ) {
					?>
                    <a class="bid_now_button" href='<?php echo get_the_permalink(); ?>'>BID NOW</a>
					<?php
				} else {
					?>
                    <a href="#" class="bid_now_button no_link_btn" rel="<?php the_ID(); ?>">
                        BIDDING ENDED
                    </a>
					<?php
				}
				?>
            </article>
        </a>
    </div>
	<?php
}

function minbizeed_get_closed_auctions( $pid, $to_pay ) {

	$retail_price   = minbizeed_get_show_price( get_post_meta( $pid, 'retail_price', true ), 2 );
	$price_to_reach = minbizeed_get_show_price( get_post_meta( $pid, 'minimum_price', true ), 2 );
	$current_price  = minbizeed_get_show_price( get_post_meta( $pid, 'current_bid', true ), 2 );

	$brand = get_field( 'brand_name' );

	$current_user = wp_get_current_user();
	$user_id      = $current_user->ID;

	$winner           = get_post_meta( $pid, 'winner', true );
	$winner_user      = get_userdata( $winner );
	$winner_user_name = $winner_user->display_name;

	$paid    = get_post_meta( $pid, 'winner_paid', true );
	$paid_on = get_post_meta( $pid, 'paid_on', true );

	$shipped    = get_post_meta( $pid, 'shipped', true );
	$shipped_on = get_post_meta( $pid, 'shipped_on', true );

	?>
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 auction_wrapper<?php if ( $winner ):echo ' all_green';endif; ?>">
        <div class="auction_wrap" id="p_<?php echo $pid; ?>">
            <h3 class="category_title"<?php if ( $brand ): echo ' title="' . $brand . '"';endif; ?>>
				<?php
				if ( strlen( $brand ) > 20 ) {
					echo mb_substr( $brand, 0, 20 ) . '...';
				} else {
					echo $brand;
				}
				?>
            </h3>
			<?php
			if ( strlen( get_the_title() ) > 30 ) {
				?>
                <h3 class="product_title"
                    title="<?php the_title(); ?>"><?php echo mb_substr( the_title( $before = '', $after = '', false ), 0, 30 ) . '...'; ?></h3>
				<?php
			} else {
				?>
                <h3 class="product_title">
					<?php
					the_title();
					?>
                </h3>
				<?php
			}
			?>
            <div class="image_holder">
				<?php
				if ( has_post_thumbnail( $pid ) ) {
					?>
                    <img class="top_arrow img-responsive" alt="<?php the_title(); ?>"
                         src="<?php echo get_the_post_thumbnail_url( $pid, 'auction_image' ); ?>"/>
					<?php
				} else {
					?>
                    <img class="top_arrow img-responsive" alt="<?php the_title(); ?>"
                         src="<?php echo get_template_directory_uri(); ?>/images/default.png"/>
					<?php
				}
				?>
            </div>
            <div class="pricing_wrapper">
                <h6>Retail Price / Value</h6>
                <h3>$<?php echo $retail_price; ?></h3>
            </div>
			<?php
			if ( $winner ) {
				?>
                <div class='won_by_holder'>
                    <span>Won by</span>
                    <h3><?php echo $winner_user_name; ?></h3>
                    <div class='clear'></div>
                </div>
				<?php
				if ( $paid ) {
					?>
                    <div class='paid_shipped_holder'>
                        <span>Paid on</span>
                        <h3><?php echo $paid_on; ?></h3>
                        <div class='clear'></div>
                    </div>
					<?php
				}
				if ( $shipped ) {
					?>
                    <div class='paid_shipped_holder'>
                        <span>Shipped on</span>
                        <h3><?php echo $shipped_on; ?></h3>
                        <div class='clear'></div>
                    </div>
					<?php
				}
				?>
                <div class='biding_holder'>
                    <span>for</span>
                    <h3>$<?php echo $current_price; ?></h3>
                    <div class='clear'></div>
                </div>
				<?php
			} else {
				?>
                <div class='won_by_holder'>
                    <span>Price to reach</span>
                    <h3>$<?php echo $price_to_reach; ?></h3>
                    <div class='clear'></div>
                </div>
                <div class='biding_holder'>
                    <span>Price reached</span>
                    <h3>$<?php echo $current_price; ?></h3>
                    <div class='clear'></div>
                </div>
				<?php
			}
			?>
        </div>

		<?php

		if ( $to_pay ) {
			$shipping_info = get_user_meta( $user_id, '_address_', true );
			$city          = get_user_meta( $user_id, '_city_', true );
			$state         = get_user_meta( $user_id, '_state_', true );
			$country       = get_user_meta( $user_id, '_country_', true );
			if ( $shipping_info && $city && $state && $country ) {
				?>
                <a href="/profile/purchase-item/?pid=<?php the_ID(); ?>" class="custom_pay_now_btn" rel="28">
                    PAY FOR THE ITEM
                </a>
				<?php
			} else {
				?>
                <a href="#" class="custom_pay_now_btn no_shpg_msg_link" rel="28">
                    PAY FOR THE ITEM
                </a>
				<?php
			}
		}
		?>
    </div>
	<?php

}


function minbizeed_single_auction_testing( $post_id ) {
	if ( has_category( 'do-not-show-on-frontend', $post_id ) ) {
		$passwords = array( 'M@207', 'Abh@652' );
		?>
        <html>
        <head>
            <!-- Latest compiled and minified CSS -->
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
                  integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
                  crossorigin="anonymous">

            <!-- Optional theme -->
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css"
                  integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp"
                  crossorigin="anonymous">

            <!-- Latest compiled and minified JavaScript -->
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
                    integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
                    crossorigin="anonymous"></script>
            <title>Minbizeed Live Testing</title>
        </head>
        <body>
		<?php
		if ( isset( $_POST['pw'] ) ) {
			if ( ! in_array( $_POST['pw'], $passwords ) ) {
				?>
                <div class="container">
                    <form method="post" action="" style="width:100%;max-width: 400px;margin:50px auto;">
                        <div class="form-group">
                            <div class="alert alert-danger">
                                <strong>Error!</strong> wrong password
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input name="pw" type="password" class="form-control"/>
                        </div>
                        <div class="form-group">
                            <input name="submit" type="submit" value="Submit" class="btn btn-primary"/>
                        </div>
                    </form>
                </div>
				<?php
				die;
			}
		} else {
			?>
            <div class="container">
                <form method="post" action="" style="width:100%;max-width: 400px;margin:50px auto;">
                    <div class="form-group">
                        <div class="alert alert-info">
                            Enter password to continue
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input name="pw" type="password" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <input name="submit" type="submit" value="Submit" class="btn btn-primary"/>
                    </div>
                </form>
            </div>
			<?php
			die;
		}
		?>
        </body>
        </html>
		<?php
	}
}

