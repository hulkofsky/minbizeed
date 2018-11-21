<?php
/*
 * Template Name: MBZ Login
 *
 */
if (is_user_logged_in()) {
    wp_redirect('/');
    exit;
}
get_header();
?>
    <div class="sign_up_page">
        <div class="container">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 sign_up_identifier">
                <h1>SIGN UP<span>Register now and get 10 Bids for FREE. <br> You can always buy more Bids from the menu</span>
                </h1>
                <?php
                echo mm_registration_form();
                ?>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 sign_in_identifier">
                <h1>SIGN
                    IN<span>Log In and start saving money by bidding and <br> winning your selected Live Auction</span>
                </h1>

                <?php
                echo mm_login_form();
                ?>

            </div>
        </div>
        <div class="page_overlay"></div>
    </div>

<?php
get_footer();
