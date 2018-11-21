<?php
/*
 * Template Name: MBZ Forgot Password
 *
 */
//if (is_user_logged_in()) {
//    wp_redirect('/');
//    exit;
//}
get_header();
?>
    <div class="sign_up_page">
        <div class="container">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 sign_up_identifier">
                <h1>Forgot password<span>Please enter your username or email address.<br>You will receive a link to create a new password via email.</span></h1>
                <div class="ajax_loader_cont" style="display: block">
                    <img alt="Loader" class="ajax_loader"
                         src="<?php echo get_template_directory_uri(); ?>/images/ajax_loader.gif"/>
                </div>
                <div class="edit_container" style="display: none;">
                    <?php echo do_shortcode('[wppb-recover-password]'); ?>
                </div>
            </div>
        </div>
        <div class="page_overlay"></div>
    </div>
    <script type="text/javascript">
        jQuery(window).on('load', function () {
            $('.ajax_loader_cont').slideUp();
            jQuery('.wppb-form-field #username_email').attr('placeholder', 'Username or E-mail');
            jQuery('.wppb-form-field #username_email').addClass('input_fields');
            jQuery('.form-submit .submit').addClass('submit_button');
            $('.edit_container').slideDown();
        });
    </script>
<?php
get_footer();
