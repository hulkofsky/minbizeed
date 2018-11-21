<?php
/*
 * Template: Privacy Policy
 * Template Name: V20 Privacy Policy
 */

get_header();
?>
    <div class="privacy_policy_page">
        <div class="page_identifier">
            <h2>PRIVACY POLICY</h2>
        </div>
        <div class="privacy_policy_wrapper">
            <div class="container">
                <?php
                if (have_posts()):
                    while (have_posts()):
                        the_post();
                        the_content();
                    endwhile;
                endif;
                ?>
            </div>
        </div>
        <div class="page_overlay"></div>
    </div>
<?php
get_footer();

