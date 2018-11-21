<?php
/*
  Template Name: V20 Register Template
 */
if (is_user_logged_in()) {
    wp_redirect('/my-account/');
    exit;
}
get_header();
?>
    <div class="sign_up_page">
        <div class='container-fluid no_padding'>
            <div class='col-lg-4 col-md-4 col-sm-12 col-xs-12 left_section'>
                <h2>JOIN <br><?php echo strtoupper(get_bloginfo('name')); ?></h2>
            </div>
            <div class='col-lg-8 col-md-8 col-sm-12 col-xs-12 right_section'>
                <?php
                echo mm_registration_form();
                ?>
            </div>
        </div>
        <div class="page_overlay"></div>
    </div>

<?php
get_footer();
