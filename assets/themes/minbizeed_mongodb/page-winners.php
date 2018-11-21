<?php
/*
 * Template Name: MBZ Winners
 *
 */
get_header();

$current_user = wp_get_current_user();
$user_login = $current_user->user_login;
$user_id = $current_user->ID;

?>
    <div class="winners_page">
        <div class="winners_wrapper">
            <div class="container-fluid">

                <?php
                $winner = array(
                    'key' => 'winner',
                    'value' => $user_id,
                    'compare' => '='
                );

                $args = array(
                    'post_type' => 'auction',
                    'order' => 'DESC',
                    'orderby' => 'meta_value_num',
                    'meta_key' => 'closed_date',
                    'posts_per_page' => 15,
                    'meta_query' => array($winner)
                );
                $query = new WP_Query($args);
                $i = 0;
                if ($query->have_posts()):
                    ?>
                    <div class="swiper-container swinners">
                        <div class="swiper-wrapper">
                            <?php
                            while ($query->have_posts()):
                                $query->the_post();

                                $pid = get_the_ID();

                                $closed_on = get_post_meta($pid, 'closed_date', true);

                                $user_country = get_user_meta($user_id, '_country_', true);

                                $brand = get_field('brand_name');

                                $retail_price = minbizeed_get_show_price(get_post_meta($pid, 'retail_price', true), 2);

                                $winner = get_post_meta($pid, 'winner', true);
                                $winner_user = get_userdata($winner);
                                $winner_user_name = $winner_user->display_name;

                                $current_price = minbizeed_get_show_price(get_post_meta($pid, 'current_bid', true), 2);
                                ?>
                                <div class="swiper-slide single_winner_wrap all_green">
                                    <div class="image_holder" style="background-image: url('<?php echo get_template_directory_uri(); ?>/images/winners/winner-bg-green.png')">

                                        <?php
                                        $gravatar_img = get_gravatar_url($user_email);

                                        if ($gravatar_img!="http://gravatar.com/avatar/75bcf051619c09dfa394e873dd00b849") {
                                            ?>
                                            <img class="winner_image" alt="Gravatar Image" src="<?php echo $gravatar_img; ?>" />
                                            <?php
                                        } else {
                                            $profile_id = get_user_meta($user_id, 'mb_272727023023_user_avatar')[0];
                                            if ($profile_id) {
                                                ?>
                                                <img class="winner_image" alt="<?php echo $user_login; ?>_image"
                                                     src="<?php echo wp_get_attachment_image_src($profile_id, 'users_profile')[0]; ?>">
                                                <?php
                                            } else {
                                                ?>
                                                <img class="winner_image" alt="<?php echo $user_login; ?>_image"
                                                     src="<?php echo get_template_directory_uri(); ?>/images/default-avatar.png">
                                                <?php
                                            }
                                        }
                                        ?>
                                        <h3><?php echo $user_login; ?></h3>
                                        <div class="date_wrapper">
                                            <span class="big_date"><?php echo date('d', $closed_on); ?></span>
                                            <span class="medium_date"><?php echo date('m', $closed_on); ?></span>
                                            <span class="small_date"><?php echo date('Y', $closed_on); ?></span>
                                        </div>
                                        <span class="bfh-countries country_img" data-country="<?php echo $user_country; ?>"
                                              data-flags="true"></span>
                                    </div>
                                    <div class="auction_won_holder">
                                        <div class="auction_wrap">
                                            <h3 class="category_title"<?php if ($brand): echo ' title="' . $brand . '"';endif; ?>>
                                                <?php
                                                if (strlen($brand) > 20) {
                                                    echo mb_substr($brand, 0, 20) . '...';
                                                } else {
                                                    echo $brand;
                                                }
                                                ?>
                                            </h3>
                                            <!--                                    <img class="winner_badge" alt="winner_badge" src="/images/badge1.png">-->
                                            <?php
                                            if (strlen(get_the_title()) > 30) {
                                                ?>
                                                <h3 class="product_title"
                                                    title="<?php the_title(); ?>">
                                                    <?php echo mb_substr(the_title($before = '', $after = '', FALSE), 0, 30) . '...'; ?>
                                                    <img class="winner_arrow" alt="winner_arrow"
                                                         src="<?php echo get_template_directory_uri(); ?>/images/winners/winner_arrow_top.png">
                                                    <img class="winner_arrow_bottom" alt="winner_arrow"
                                                         src="<?php echo get_template_directory_uri(); ?>/images/winners/winner_arrow_down.png">
                                                </h3>
                                                <?php
                                            } else {
                                                ?>
                                                <h3 class="product_title">
                                                    <?php
                                                    the_title();
                                                    ?>
                                                    <img class="winner_arrow" alt="winner_arrow"
                                                         src="<?php echo get_template_directory_uri(); ?>/images/winners/winner_arrow_top.png">
                                                    <img class="winner_arrow_bottom" alt="winner_arrow"
                                                         src="<?php echo get_template_directory_uri(); ?>/images/winners/winner_arrow_down.png">
                                                </h3>
                                                <?php
                                            }
                                            ?>
                                            <div class="hidden_wrapper">
                                                <div class="image_holder">
                                                    <img class="top_arrow" alt="<?php the_title(); ?>"
                                                         src="<?php echo get_the_post_thumbnail_url($pid, 'auction_image'); ?>"/>
                                                </div>
                                                <div class="pricing_wrapper">
                                                    <h6>Retail Price / Value</h6>
                                                    <h3>$<?php echo $retail_price; ?></h3>
                                                </div>

                                                <?php
                                                if ($winner) {
                                                    ?>
                                                    <div class='won_by_holder'>
                                                        <span>Won by</span>
                                                        <h3><?php echo $winner_user_name; ?></h3>
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
                                            </div>
                                        </div>
                                        <div class="show_more_holder">
                                            <span>more</span>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                $i++;
                            endwhile;
                            ?>
                        </div>
                        <img class="shadow_right_overlay" alt="shadow_right_overlay"
                             src="<?php echo get_template_directory_uri(); ?>/images/shadow_overlay2.png">
                        <img class="shadow_left_overlay" alt="shadow_right_overlay"
                             src="<?php echo get_template_directory_uri(); ?>/images/shadow_overlay2.png">
                    </div>
                    <div class='swinners previous_winners_wrapper'>
                        <div class="inner_holder">
                            <span class="big_title">PREVIOUS <span class="dropdown_arrow">&#9698;</span></span>
                            <span class="small_title">WINNER</span>
                        </div>
                    </div>
                    <div class='swinners next_winners_wrapper'>
                        <div class="inner_holder">
                            <span class="big_title"><span class="dropdown_arrow">&#9698;</span> NEXT</span>
                            <span class="small_title">WINNER</span>
                        </div>
                    </div>
                    <?php
                else:
                    ?>
                    <p class="no_content">No won auctions yet</p>
                    <?php
                endif;
                ?>
            </div>
            <div class="page_overlay"></div>
        </div>
    </div>
    <script type="text/javascript">
        jQuery(window).on('load', function () {
            var f1_node = $('.single_winner_wrap .image_holder .country_img');
            jQuery(f1_node).each(function() {
                var f1_node_i = jQuery(this).children('i');
                jQuery(this).empty().append(f1_node_i);
            });
        });

        var windowW = jQuery(window).width();
        var slidesPerPage = 3.9;
        var space_between = 40;
        if (windowW < 1200) {
            slidesPerPage = 3.7;
            space_between = 40;
        }
        if (windowW < 1150) {
            slidesPerPage = 3.5;
            space_between = 40;
        }
        if (windowW < 1050) {
            slidesPerPage = 2.7;
            space_between = 60;
        }
        if (windowW < 900 || windowW < 700) {
            space_between = 30;
        }
        if (windowW < 800) {
            slidesPerPage = 2.3;
            space_between = 30;
        }
        if (windowW < 700) {
            slidesPerPage = 1.8;
            space_between = 30;
        }
        if (windowW < 550) {
            slidesPerPage = 1.5;
            space_between = 30;
        }

        if (windowW < 500) {
            slidesPerPage = 1.2;
            space_between = 30;
        }
        if (windowW < 400) {
            slidesPerPage = 1.1;
            space_between = 15;
        }
        if (windowW < 350) {
            slidesPerPage = 1;
            space_between = 15;
        }
        //winners 3 per slide swiper
        var swiper_clips = new Swiper('.swiper-container.swinners', {
            pagination: '.swiper-pagination.swinners',
            slidesPerView: slidesPerPage,
            paginationClickable: true,
            spaceBetween: space_between,
            nextButton: '.swinners.previous_winners_wrapper',
            prevButton: '.swinners.next_winners_wrapper',
            speed: 1000,
            centeredSlides: true,
            loop: true
//                onSlideChangeStart: function () {
//                    jQuery('.shadow_right_overlay,.shadow_left_overlay').animate({width: '25px'}, 100);
//                },
//                onSlideChangeEnd: function () {
//                    jQuery('.shadow_right_overlay,.shadow_left_overlay').animate({width: '0px'}, 5);
//                }
        });
    </script>
<?php
get_footer();
