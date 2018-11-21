<?php
/*
 * Template Name: MBZ Closed Auctions
 *
 */
get_header();
$ppp = 12;
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$offset = ($ppp * $paged) - $ppp;
$args = array(
    'paged' => $paged,
    'offset' => $offset,
    'posts_per_page' => $ppp,
    'post_type' => 'auction',
    'order' => 'ASC',
    'cat' => -2,
    'meta_query' => array(
        array(
            'key' => 'closed',
            'value' => '1',
            'compare' => '='
        )
    )
);
$wp_query = new WP_Query($args);
$i = 0;
?>
    <div class="live_auctions_page closed_auctions_page">
        <div class="container">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 page_identifier">
                <h1>CLOSED AUCTIONS<span>Closed bids are listed by date. Won auctions are <br> highlighted with green,everything else is passed auctions.</span>
                </h1>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 all_auctions_wrapper">
                <?php
                if ($wp_query->have_posts()):
                    while ($wp_query->have_posts()):
                        $wp_query->the_post();

                        minbizeed_get_closed_auctions(get_the_ID(),0);

                        $i++;
                    endwhile;
                    ?>
                    <div class="clear"></div>
                    <div class="bs_pag">
                        <?php
                        $pages = $wp_query->max_num_pages;
                        bootstrap_pagination($paged, $pages);
                        ?>
                    </div>
                    <?php
                else:
                    ?>
                    <p class="no_content">No closed auctions yet</p>
                    <?php
                endif;
                ?>
            </div>
        </div>
        <div class="page_overlay"></div>
    </div>
<?php
get_footer();
