<?php
/*
 * Template: How to bid
 * Template Name: V20 How to bid
 */

get_header();
?>
    <div class="how_it_works_page">
        <div class="page_identifier">
            <h2>HOW IT WORKS</h2>
        </div>
        <div class="how_it_works_wrapper">

            <?php
            $hiw_1 = get_field('sign_up_section', 'option');
            $hiw_2 = get_field('buy_bids_section', 'option');
            $hiw_3 = get_field('start_bidding_section', 'option');
            $hiw_4 = get_field('win_the_game_section', 'option');
            ?>

            <div class="how_it_works_wrap how_it_works_1">
                <img alt="how-it-works1" src="<?php echo get_template_directory_uri(); ?>/images/how_it_works/how-it-works1.png">
                <div class="how_it_works_desc">
                    <h2>01</h2>
                    <h3>Buy Bids</h3>
                    <?php
                    if($hiw_1){
                        ?>
                        <h4><?php echo $hiw_1; ?></h4>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <div class="how_it_works_wrap how_it_works_2 middle">
                <img alt="how-it-works2" src="<?php echo get_template_directory_uri(); ?>/images/how_it_works/how-it-works2.png">
                <div class="how_it_works_desc">
                    <h2>02</h2>
                    <h3>Sign Up</h3>
                    <?php
                    if($hiw_2){
                        ?>
                        <h4><?php echo $hiw_2; ?></h4>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <div class="how_it_works_wrap how_it_works_3">
                <img alt="how-it-works3" src="<?php echo get_template_directory_uri(); ?>/images/how_it_works/how-it-works3.png">
                <div class="how_it_works_desc">
                    <h2>03</h2>
                    <h3>Start Bidding</h3>
                    <?php
                    if($hiw_3){
                        ?>
                        <h4><?php echo $hiw_3; ?></h4>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <div class="how_it_works_wrap how_it_works_4 middle">
                <img alt="how-it-works4" src="<?php echo get_template_directory_uri(); ?>/images/how_it_works/how-it-works4.png">
                <div class="how_it_works_desc">
                    <h2>04</h2>
                    <h3>Win The Game</h3>
                    <?php
                    if($hiw_4){
                        ?>
                        <h4><?php echo $hiw_4; ?></h4>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <div class="start_button">
                <a href="/buy-bids" class="buy_bids_now">START NOW</a>
            </div>
        </div>
        <div class="page_overlay"></div>
    </div>
<?php
get_footer();

