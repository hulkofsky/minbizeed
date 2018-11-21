<?php
/*
  Template Name: V20 Forgot Password Template
 */
if (is_user_logged_in()) {
    wp_redirect('/my-account/');
    exit;
}
get_header();
?>
    <div class="login_page forgotpw">
        <div class="page_identifier">
            <h2>Password Recovery</h2>
        </div>
        <div class="sign_in_wrapper_page">
            <div class="sign_in_wrap">
                <?php echo do_shortcode('[wppb-recover-password]'); ?>
            </div>
            <div class="dont_have_acc_wrapper">
                <div class="dont_have_acc_inner_wrapper">
                    <span>Suddenly remembered your password?</span>
                    <a href='/?msg=login'>LOG IN</a>
                    <div class='clear'></div>
                </div>
            </div>
        </div>
    </div>
<?php
get_footer();
