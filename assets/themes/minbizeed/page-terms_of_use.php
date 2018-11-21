<?php
/*
 * Template Name: MBZ Terms of use
 *
 */
get_header();
?>
    <div class="terms_of_use_page">
        <div class="container">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 terms_wrapper">
                <h1>Terms of Use</h1>
                <?php
                $terms = get_field('terms_of_use', 'option');
                if ($terms) {
                    ?>
                    <p>
                        <?php echo $terms; ?>
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
