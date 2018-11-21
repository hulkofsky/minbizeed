<?php
/*
 * Template Name: MBZ How it works
 *
 */
get_header();
?>

    <div class="how_it_works_page">
        <div class="ajax_loader_cont" style="display: block">
            <img alt="Loader" class="ajax_loader"
                 src="<?php echo get_template_directory_uri(); ?>/images/ajax_loader.gif"/>
        </div>
        <div class="to_show" style="display: none">
            <div class="how_it_works_guid">
                <div class="container">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 how_it_works_identifier">
                        <h1>HOW IT WORKS<span>All bids on items that do not reach the minimum <br> price goal, will be refunded to the user</span>
                        </h1>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 guide_wrapper">
                        <div class="single_guide_wrap2 guide_1">
                            <?php
                            $title_1 = get_field('hiw_one_title', 'option');
                            $subtitle_1 = get_field('hiw_one_title', 'option');
                            $text_1 = get_field('hiw_one_text', 'option');
                            $yt_1 = get_field('hiw_one_youtube', 'option');
                            ?>
                            <div class="left_section_wrap">
                                <div class="wrapper_one">
                                    <div class="wrapper_two">
                                        <h1>01</h1>
                                    </div>
                                </div>
                            </div>
                            <div class="middle_section_wrap">
                                <img class="guide_image" alt="bids"
                                     src="<?php echo get_template_directory_uri(); ?>/images/how_it_works/howitworks_sec_bg.png">
                                <div class="text_wrapper">
                                    <?php
                                    if ($title_1) {
                                        ?>
                                        <h2><?php echo $title_1; ?></h2>
                                        <?php
                                    }
                                    if ($subtitle_1) {
                                        ?>
                                        <h4><?php echo $subtitle_1; ?></h4>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="right_section_wrap">
                                <div class="wrapper_one">
                                    <div class="wrapper_two">
                                        <div class="wrapper_zero">
                                            <?php
                                            if ($yt_1) {
                                                ?>
                                                <a class="hiw_yt" href="<?php echo $yt_1; ?>">
                                                    <img class="play_button" alt="small_image"
                                                         src="<?php echo get_template_directory_uri(); ?>/images/arrows/play_button.png">
                                                </a>
                                                <?php
                                            }else{
	                                            ?>
                                                <a class="hiw_yt no_link_btn" href="#">
                                                    <img class="play_button sub_zero_opac" alt="small_image"
                                                         src="<?php echo get_template_directory_uri(); ?>/images/arrows/play_button.png">
                                                </a>
	                                            <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        if ($text_1) {
                            ?>
                            <h5 class="educational_text">
                                <?php echo $text_1; ?>
                            </h5>
                            <?php
                        }
                        ?>
                        <div class="single_guide_wrap2 guide_2">
                            <?php
                            $title_2 = get_field('hiw_two_title', 'option');
                            $subtitle_2 = get_field('hiw_two_title', 'option');
                            $text_2 = get_field('hiw_two_text', 'option');
                            $yt_2 = get_field('hiw_two_youtube', 'option');
                            ?>
                            <div class="left_section_wrap">
                                <div class="wrapper_one">
                                    <div class="wrapper_two">
                                        <h1>02</h1>
                                    </div>
                                </div>
                            </div>
                            <div class="middle_section_wrap">
                                <img class="guide_image" alt="bids"
                                     src="<?php echo get_template_directory_uri(); ?>/images/how_it_works/howitworks_sec_bg.png">
                                <div class="text_wrapper">
                                    <?php
                                    if ($title_2) {
                                        ?>
                                        <h2><?php echo $title_2; ?></h2>
                                        <?php
                                    }
                                    if ($subtitle_2) {
                                        ?>
                                        <h4><?php echo $subtitle_2; ?></h4>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="right_section_wrap">
                                <div class="wrapper_one">
                                    <div class="wrapper_two">
                                        <div class="wrapper_zero">
                                            <?php
                                            if ($yt_2) {
                                                ?>
                                                <a class="hiw_yt" href="<?php echo $yt_2; ?>">
                                                    <img class="play_button" alt="small_image"
                                                         src="<?php echo get_template_directory_uri(); ?>/images/arrows/play_button.png">
                                                </a>
                                                <?php
                                            }else{
	                                            ?>
                                                <a class="hiw_yt no_link_btn" href="#">
                                                    <img class="play_button sub_zero_opac" alt="small_image"
                                                         src="<?php echo get_template_directory_uri(); ?>/images/arrows/play_button.png">
                                                </a>
	                                            <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        if ($text_2) {
                            ?>
                            <h5 class="educational_text">
                                <?php echo $text_2; ?>
                            </h5>
                            <?php
                        }
                        ?>
                        <div class="single_guide_wrap2 guide_3">
                            <?php
                            $title_3 = get_field('hiw_three_title', 'option');
                            $subtitle_3 = get_field('hiw_three_title', 'option');
                            $text_3 = get_field('hiw_three_text', 'option');
                            $yt_3 = get_field('hiw_three_youtube', 'option');
                            ?>
                            <div class="left_section_wrap">
                                <div class="wrapper_one">
                                    <div class="wrapper_two">
                                        <h1>03</h1>
                                    </div>
                                </div>
                            </div>
                            <div class="middle_section_wrap">
                                <img class="guide_image" alt="bids"
                                     src="<?php echo get_template_directory_uri(); ?>/images/how_it_works/howitworks_sec_bg.png">
                                <div class="text_wrapper">
                                    <?php
                                    if ($title_3) {
                                        ?>
                                        <h2><?php echo $title_3; ?></h2>
                                        <?php
                                    }
                                    if ($subtitle_3) {
                                        ?>
                                        <h4><?php echo $subtitle_3; ?></h4>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="right_section_wrap">
                                <div class="wrapper_one">
                                    <div class="wrapper_two">
                                        <div class="wrapper_zero">
                                            <?php
                                            if ($yt_3) {
                                                ?>
                                                <a class="hiw_yt" href="<?php echo $yt_3; ?>">
                                                    <img class="play_button" alt="small_image"
                                                         src="<?php echo get_template_directory_uri(); ?>/images/arrows/play_button.png">
                                                </a>
                                                <?php
                                            }else{
	                                            ?>
                                                <a class="hiw_yt no_link_btn" href="#">
                                                    <img class="play_button sub_zero_opac" alt="small_image"
                                                         src="<?php echo get_template_directory_uri(); ?>/images/arrows/play_button.png">
                                                </a>
	                                            <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        if ($text_3) {
                            ?>
                            <h5 class="educational_text">
                                <?php echo $text_3; ?>
                            </h5>
                            <?php
                        }
                        ?>
                        <div class="single_guide_wrap2 guide_4">
                            <?php
                            $title_4 = get_field('hiw_four_title', 'option');
                            $subtitle_4 = get_field('hiw_four_title', 'option');
                            $text_4 = get_field('hiw_four_text', 'option');
                            $yt_4 = get_field('hiw_four_youtube', 'option');
                            ?>
                            <div class="left_section_wrap">
                                <div class="wrapper_one">
                                    <div class="wrapper_two">
                                        <h1>04</h1>
                                    </div>
                                </div>
                            </div>
                            <div class="middle_section_wrap">
                                <img class="guide_image" alt="bids"
                                     src="<?php echo get_template_directory_uri(); ?>/images/how_it_works/howitworks_sec_bg.png">
                                <div class="text_wrapper">
                                    <?php
                                    if ($title_4) {
                                        ?>
                                        <h2><?php echo $title_4; ?></h2>
                                        <?php
                                    }
                                    if ($subtitle_4) {
                                        ?>
                                        <h4><?php echo $subtitle_4; ?></h4>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="right_section_wrap">
                                <div class="wrapper_one">
                                    <div class="wrapper_two">
                                        <div class="wrapper_zero">
                                            <?php
                                            if ($yt_4) {
                                                ?>
                                                <a class="hiw_yt" href="<?php echo $yt_4; ?>">
                                                    <img class="play_button" alt="small_image"
                                                         src="<?php echo get_template_directory_uri(); ?>/images/arrows/play_button.png">
                                                </a>
                                                <?php
                                            }else{
	                                            ?>
                                                <a class="hiw_yt no_link_btn" href="#">
                                                    <img class="play_button sub_zero_opac" alt="small_image"
                                                         src="<?php echo get_template_directory_uri(); ?>/images/arrows/play_button.png">
                                                </a>
	                                            <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        if ($text_4) {
                            ?>
                            <h5 class="educational_text">
                                <?php echo $text_4; ?>
                            </h5>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="product_info_wrapper">
                <div class="section_identifier">
                    <div class="container">
                        <h2>INFO</h2>
                    </div>
                </div>
                <img class="product_info bigger_image" alt="bids"
                     src="<?php echo get_template_directory_uri(); ?>/images/how_it_works/product_info.png">
                <img class="product_info smaller_image" alt="bids"
                     src="<?php echo get_template_directory_uri(); ?>/images/how_it_works/product_info_smaller.png">
                <h5>n.b: Numbers Change relatively in each product</h5>
            </div>
        </div>
        <div class="page_overlay"></div>
    </div>
    <script type="text/javascript">
        jQuery(window).on('load', function () {
            $('.ajax_loader_cont').slideUp();
            $('.to_show').slideDown();
        });
    </script>
<?php
get_footer();
