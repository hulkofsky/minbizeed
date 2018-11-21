<?php
/*
 * Template: Welcome
 * Template Name: V20 welcome
 */
if (!is_user_logged_in()) {
    wp_redirect('/?msg=login&redirect_url=/my-account');
    exit;
}
get_header();
?>
    <div class="buy_bids_page">
        <div class="error_cont unsub">
            <div class="page_identifier">
                <h2>Hello and Welcome <i class="fa fa-smile-o" aria-hidden="true"></i></h2>
            </div>
            <div class="page_identifier_sub">
                <h3>You are already logged in, However We sent you an email with your credentials.</h3>
                <h3>Good luck, and happy bidding!</h3>
                <h3>Click <a href="/">here</a> to start!</h3>
            </div>
        </div>
        <div class="clear"></div>
        <div class="page_overlay"></div>
    </div>
<?php
get_footer();

