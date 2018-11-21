<?php
/*
 * Template Name: MBZ Home
 *
 */
get_header();
$args = array(
    'post_type' => 'auction',
    'posts_per_page' => 27,
    'order' => 'ASC',
    'cat' => -2,
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
$query = new WP_Query($args);
$i = 0;
?>
    <div class="live_auctions_page not_s">
        <div class="container">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 page_identifier">
                <h1>LIVE AUCTIONS<span>All Bids on items that do not reach the minimum <br> price goal, will be refunded to the user</span>
                </h1>
                <?php
                $to_exclude = array(
                    'hide_empty' => 1,
                    'exclude' => array(1, 2)
                );
                $all_cats = get_categories($to_exclude);
                if (count($all_cats) > 0) {
                    ?>
                    <div class="category_filter_wrapper">
                        <div class="first_shown_wrap">
                            <span class="first_shown" data-selected="CATEGORY">CATEGORY</span>
                            <span class="dropdown_arrow">&#9698;</span>
                        </div>
                        <ul class="categories_list">
                            <li data-to-select="/">ALL</li>
                            <?php
                            foreach ($all_cats as $all_cat) {
                                $cat_name = $all_cat->name;
                                $cat_slug = $all_cat->slug;
                                ?>
                                <li data-to-select-link="/category/<?php echo $cat_slug; ?>"
                                    data-to-select="<?php echo strtoupper($cat_name); ?>"><?php echo strtoupper($cat_name); ?></li>
                                <?php
                            }
                            ?>
                        </ul>
                    </div>
                    <?php
                }
                ?>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 all_auctions_wrapper">
                <?php
                if ($query->have_posts()):
                    while ($query->have_posts()):
                        $query->the_post();
                        minbizeed_get_open_auctions(get_the_ID(),0);
                        $i++;
                    endwhile;
                else:
                    ?>
                    <p class="no_content">No auctions yet</p>
                    <?php
                endif;
                ?>
            </div>
            <input type="hidden" id="balance2" value="<?php echo get_user_meta($user_ID, 'user_credits', true) ?>"/>
        </div>
        <div class="page_overlay"></div>
    </div>
<?php
get_footer();
