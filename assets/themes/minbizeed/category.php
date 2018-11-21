<?php
$category = get_category(get_query_var('cat'));
$cat_ID = $category->term_id;
$cat_SLUG = $category->slug;
$cat_NAME = $category->name;

$excluded_cats = array(1, 2);

if (in_array($cat_ID, $excluded_cats)) {
    wp_redirect('/');
    die;
}

get_header();

$args = array(
    'post_type' => 'auction',
    'posts_per_page' => 27,
    'order' => 'ASC',
    'cat' => $cat_ID,
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
$wp_query = new WP_Query($args);
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
                            <?php
                            if ($cat_SLUG) {
                                ?>
                                <span class="first_shown"
                                      data-selected="<?php echo strtoupper($cat_NAME); ?>"><?php echo strtoupper($cat_NAME); ?></span>
                                <?php
                            }
                            ?>
                            <span class="dropdown_arrow">&#9698;</span>
                        </div>
                        <ul class="categories_list">
                            <li data-to-select-link="/" data-to-select="ALL">ALL</li>
                            <?php
                            foreach ($all_cats as $all_cat) {
                                $cat_id = $all_cat->term_id;
                                $cat_name = $all_cat->name;
                                $cat_slug = $all_cat->slug;
                                if ($cat_id != $cat_ID) {
                                    ?>
                                    <li data-to-select-link="/category/<?php echo $cat_slug; ?>"
                                        data-to-select="<?php echo strtoupper($cat_name); ?>"><?php echo strtoupper($cat_name); ?></li>
                                    <?php
                                }
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
                if ($wp_query->have_posts()):
                    while ($wp_query->have_posts()):
                        $wp_query->the_post();

                        minbizeed_get_open_auctions(get_the_ID(),0);

                        $i++;
                    endwhile;
                else:
                    ?>
                    <p class="no_content">No auctions under this category</p>
                    <?php
                endif;
                ?>
            </div>
        </div>
        <div class="page_overlay"></div>
    </div>
<?php
get_footer();
