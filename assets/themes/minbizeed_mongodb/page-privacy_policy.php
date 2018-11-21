<?php
/*
 * Template Name: MBZ Privacy Policy
 *
 */
get_header();
?>
    <div class="terms_of_use_page">
        <div class="container">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 terms_wrapper">
                <h1>Privacy Policy</h1>
                <?php
                $privacy_policy = get_field('privacy_policy', 'option');
                if ($privacy_policy) {
                    ?>
                    <p>
                        <?php echo $privacy_policy; ?>
                    </p>
                    <?php
                }
                ?>
            </div>
        </div>
        <div class="page_overlay"></div>
    </div>
<?php
get_footer();
