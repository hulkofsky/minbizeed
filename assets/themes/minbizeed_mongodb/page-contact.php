<?php
/*
 * Template Name: MBZ Contact
 *
 */
get_header();
?>
    <div class="contact_us_page">
        <div class="container">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 contact_us_identifier">
                <h1>CONTACT US<span>Please feel free to contact us, our team will <br> respond as soon as we have any reply.</span>
                </h1>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12 contact_us_section">
                <?php
                echo do_shortcode('[contact-form-7 id="4" title="Contact form"]');
                ?>
            </div>
            <?php
            $args = array(
                'post_type' => 'auction',
                'posts_per_page' => 1,
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
            if ($query->have_posts()):
                while ($query->have_posts()):
                    $query->the_post();
                    minbizeed_get_open_auctions(get_the_ID(),1);
                endwhile;
            endif;
            ?>
        </div>
        <div class="page_overlay"></div>
    </div>
    <script type="text/javascript">
        jQuery(window).on('load', function () {
            var cf7_loader_html = '<span class="ajax-loader"></span>';
            jQuery('.wpcf7 .submit_wrapper .ajax-loader').hide();
            jQuery('.wpcf7 .submit_wrapper').prepend(cf7_loader_html);
        });
    </script>
<?php
get_footer();
