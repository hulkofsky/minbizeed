<?php
/*
 * Template: Contact Us
 * Template Name: V20 Contact Us
 */

get_header();
?>
    <div class="contact_us_page">
        <div class="page_identifier">
            <h2>CONTACT US</h2>
        </div>
        <div class='container-fluid no_padding'>
            <div class='col-lg-4 col-md-4 col-sm-4 col-xs-12 left_section'>
                <div class='left_section_inner'>
                    <h2>REACH <br>US</h2>
                    <a href="mailto:<?php echo FROM_EMAIL; ?>"><i class="fa fa-envelope-o" aria-hidden="true"></i><?php echo FROM_EMAIL; ?></a>
                </div>
            </div>
            <div class='col-lg-8 col-md-8 col-sm-8 col-xs-12 right_section'>
                <?php echo do_shortcode('[contact-form-7 id="101" title="Contact Us"]'); ?>
            </div>
        </div>
        <div class="page_overlay"></div>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                jQuery('textarea[name="uMessage"]').attr('rows','7');
            });
        </script>
    </div>
<?php
get_footer();

