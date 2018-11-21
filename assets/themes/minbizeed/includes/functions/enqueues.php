<?php
/**
 * Enqueues
 *
 * @package  MinBizeed
 * @company  Triangle Mena <http://trianglemena.com>
 * @developer  Maroun Melhem <http://maroun.me>
 *
 */
function tb_scripts()
{

    wp_enqueue_script(
        'jQuery', get_template_directory_uri() . '/libraries/jQuery/jquery.min.js', array('jquery'), '1.0'
    );

    wp_enqueue_script(
        'socket_js', get_template_directory_uri() . '/libraries/socket_io/socket-io.js'
    );

    wp_enqueue_script(
        'snap', get_template_directory_uri() . '/libraries/snap/snap.js'
    );

    wp_enqueue_script(
        'bootstrap', get_template_directory_uri() . '/libraries/bootstrap/bs/bootstrap.min.js'
    );

    wp_enqueue_script(
        'bootstrap-form-helper-countries', get_template_directory_uri() . '/libraries/bootstrap-form-helper/js/bootstrap-formhelpers-countries.js'
    );

    wp_enqueue_script(
        'bootstrap-form-helper', get_template_directory_uri() . '/libraries/bootstrap-form-helper/dist/js/bootstrap-formhelpers.min.js'
    );

    wp_enqueue_script(
        'bootstrap-form-helper-selectbox', get_template_directory_uri() . '/libraries/bootstrap-form-helper/js/bootstrap-formhelpers-selectbox.js'
    );

    wp_enqueue_script(
        'bootstrap-form-helper-EN', get_template_directory_uri() . '/libraries/bootstrap-form-helper/js/lang/en_US/bootstrap-formhelpers-countries.en_US.js'
    );

    wp_enqueue_script(
        'magnific-popup_js', get_template_directory_uri() . '/libraries/magnific-popup/magnific-popup.js', array('jquery'), '1.0'
    );

    wp_enqueue_script(
        'nicescroll-js', get_template_directory_uri() . '/libraries/nicescroll/jquery.nicescroll.min.js', array('jquery'), '1.0'
    );

    wp_enqueue_script(
        'swiper-js', get_template_directory_uri() . '/libraries/swiper/swiper.min.js', array('jquery'), '1.0'
    );

    wp_enqueue_script(
        'switchery-js', get_template_directory_uri() . '/libraries/switchery/switchery.min.js', array('jquery'), '1.0'
    );

    wp_enqueue_script(
        'hideShowPassword-js', get_template_directory_uri() . '/libraries/hideShowPassword/js/hideShowPassword.min.js', array('jquery'), '1.0'
    );

    wp_enqueue_script(
        'scripts', get_template_directory_uri() . '/js/scripts.js', array('jquery'), '1.01'
    );

    wp_enqueue_script(
        'auction_js', get_template_directory_uri() . '/js/auction.js', array('jquery'), '1.01'
    );
}

add_action('wp_enqueue_scripts', 'tb_scripts');

function tb_styles()
{
    wp_enqueue_style(
        'fontawesome_css', get_template_directory_uri() . '/fonts/font_awesome/font-awesome.min.css', array(), '1.0'
    );

    wp_enqueue_style(
        'bootstrap_css', get_template_directory_uri() . '/libraries/bootstrap/bs/bootstrap.min.css', array(), '1.0'
    );

    wp_enqueue_style(
        'bootstrap-form-helper', get_template_directory_uri() . '/libraries/bootstrap-form-helper/dist/css/bootstrap-formhelpers.min.css', array(), '1.0'
    );
    wp_enqueue_style(
        'magnific-popup_css', get_template_directory_uri() . '/libraries/magnific-popup/magnific-popup.css', array(), '1.0'
    );

    wp_enqueue_style(
        'swiper-css', get_template_directory_uri() . '/libraries/swiper/swiper.min.css', array(), '1.0'
    );

    wp_enqueue_style(
        'animate-css', get_template_directory_uri() . '/libraries/animate/animate.min.css', array(), '1.0'
    );

    wp_enqueue_style(
        'hover-css', get_template_directory_uri() . '/libraries/hover/hover-min.css', array(), '1.0'
    );

    wp_enqueue_style(
        'hideShowPassword-css', get_template_directory_uri() . '/libraries/hideShowPassword/css/hideShowPassword.wink.css', array(), '1.0'
    );

    wp_enqueue_style(
        'switchery-css', get_template_directory_uri() . '/libraries/switchery/switchery.min.css', array(), '1.0'
    );

    wp_enqueue_style(
        'style_select_1', get_template_directory_uri() . '/libraries/style_select/select-css.css', array(), '1.0'
    );

    wp_enqueue_style(
        'style_select_2', get_template_directory_uri() . '/libraries/style_select/select-css-arrow.css', array(), '1.0'
    );

    wp_enqueue_style(
        'style_select_3', get_template_directory_uri() . '/libraries/style_select/select-css-compat.css', array(), '1.0'
    );

    wp_enqueue_style(
        'style_select_4', get_template_directory_uri() . '/libraries/style_select/select-css-theme.css', array(), '1.0'
    );

    wp_enqueue_style(
        'styles', get_template_directory_uri() . '/css/styles.css', array(), '1.0'
    );
    wp_enqueue_style(
        'styles-1600px', get_template_directory_uri() . '/css/styles-1600px.css', array(), '1.0', 'screen and (max-width:1600px)'
    );
    wp_enqueue_style(
        'styles-1500px', get_template_directory_uri() . '/css/styles-1500px.css', array(), '1.0', 'screen and (max-width:1500px)'
    );
    wp_enqueue_style(
        'styles-1400px', get_template_directory_uri() . '/css/styles-1400px.css', array(), '1.0', 'screen and (max-width:1400px)'
    );
    wp_enqueue_style(
        'styles-1200px', get_template_directory_uri() . '/css/styles-1200px.css', array(), '1.0', 'screen and (max-width:1200px)'
    );
    wp_enqueue_style(
        'styles-992px', get_template_directory_uri() . '/css/styles-992px.css', array(), '1.0', 'screen and (max-width:992px)'
    );
    wp_enqueue_style(
        'styles-768px', get_template_directory_uri() . '/css/styles-768px.css', array(), '1.0', 'screen and (max-width:768px)'
    );
    wp_enqueue_style(
        'styles-500px', get_template_directory_uri() . '/css/styles-500px.css', array(), '1.0', 'screen and (max-width:500px)'
    );

}

add_action('wp_enqueue_scripts', 'tb_styles');


/*Admin includes start*/
function tb_admin_enqueues()
{

//    wp_enqueue_script(
//        'mb_jquery', get_template_directory_uri() . '/libraries/jQuery/jquery.min.js'
//    );
    wp_enqueue_style(
        'mb_admin-css', get_template_directory_uri() . '/admin/css/admin.css'
    );
    wp_enqueue_style(
        'mb_admin-ui_thing-css', get_template_directory_uri() . '/admin/css/ui-thing.css'
    );
    wp_enqueue_style(
        'mb_admin-jquery_ui-css', get_template_directory_uri() . '/admin/css/jquery-ui-1.8.16.custom.css'
    );
    wp_enqueue_style(
        'fontawesome_css', get_template_directory_uri() . '/fonts/font_awesome/font-awesome.min.css'
    );
    wp_enqueue_style(
        'bootstrap_css', get_template_directory_uri() . '/libraries/bootstrap/bs/bootstrap.min.css'
    );
//    wp_enqueue_script(
//        'mb_admin-jquery-ui', get_template_directory_uri() . "/admin/js/jquery.ui.core.js"
//    );
    wp_enqueue_script(
        'mb_admin-datepicker-js', get_template_directory_uri() . "/admin/js/jquery.ui.datepicker.js"
    );
    wp_enqueue_script(
        'mb_admin-timepicker-js', get_template_directory_uri() . "/admin/js/jquery_timepicker.js"
    );
    wp_enqueue_style(
        'mb-layout-css', get_template_directory_uri() . '/admin/css/layout.css'
    );

    wp_enqueue_style(
        'mb_admin-ui_thing-css', get_template_directory_uri() . '/admin/css/ui-thing.css'
    );
    wp_enqueue_script(
        'bootstrap', get_template_directory_uri() . '/libraries/bootstrap/bs/bootstrap.min.js'
    );
    wp_enqueue_style(
        'magnific-popup_css', get_template_directory_uri() . '/libraries/magnific-popup/magnific-popup.css', array(), '1.0'
    );

    wp_enqueue_script(
        'magnific-popup_js', get_template_directory_uri() . '/libraries/magnific-popup/magnific-popup.js', array('jquery'), '1.0'
    );

    wp_enqueue_style(
        'datepicker_css', get_template_directory_uri() . '/libraries/datepicker/default.css', array(), '1.0'
    );
    wp_enqueue_script(
        'datepicker_js', get_template_directory_uri() . '/libraries/datepicker/datepicker.js', array('jquery'), '1.0'
    );

    wp_enqueue_style(
        'datepicker_date_css', get_template_directory_uri() . '/libraries/datepicker/default.date.css', array(), '1.0'
    );
    wp_enqueue_script(
        'datepicker_date_js', get_template_directory_uri() . '/libraries/datepicker/datepicker.date.js', array('jquery'), '1.0'
    );

    wp_enqueue_script(
        'mb_admin-js', get_template_directory_uri() . '/admin/js/admin.js'
    );
}
add_action('admin_enqueue_scripts', 'tb_admin_enqueues');

add_action('admin_head', 'minbizeed_admin_style_sheet');
function minbizeed_admin_style_sheet()
{
    ?>
    <script type="text/javascript">
        var SITE_CURRENCY = "$";
        var SITE_URL = "<?php echo get_bloginfo('siteurl');?>";
    </script>
    <?php
}
/*Admin includes end*/

