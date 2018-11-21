<?php
/*
 * Template: My Account Template
 * Template Name: V20 My Account Template
 */

if (!is_user_logged_in()):
    wp_redirect('/?msg=login&redirect_url=/my-account');
    exit;
else:
    get_header();
    global $current_user;
    get_currentuserinfo();
    $user_id = $current_user->ID;
    ?>
    <div class="account_settings_page">
        <div class="page_identifier">
            <h2>MY ACCOUNT</h2>
            <ul>
                <li class="active"><a href="/my-account">ACCOUNT SETTINGS</a></li>
                <li><a href="/my-account/bids-history">BIDS HISTORY</a></li>
                <li><a href="/my-account/trophy-room/">BIDS WON</a></li>
                <li><?php echo do_shortcode('[wppb-logout text="" redirect_url="/" link_text="LOGOUT"]') ?></li>
            </ul>
        </div>
        <div class="form_wrapper">
            <div class='container'>
                <div class="ajax_loader_cont" style="display: block">
                    <img alt="Loader" class="ajax_loader"
                         src="<?php echo get_template_directory_uri(); ?>/images/ajax_loader.gif"/>
                </div>
                <div class="edit_container" style="display: none;">
                    <?php
                    echo do_shortcode('[wppb-edit-profile]');
                    ?>
                </div>
            </div>
        </div>
        <div class="page_overlay"></div>
    </div>
    <div class="clear"></div>
    <script type="text/javascript">
        $(window).on('load', function () {
            $('.ajax_loader_cont').slideUp();
            $('.edit_container').slideDown();
        });
    </script>
    <?php
    get_footer();
endif;

