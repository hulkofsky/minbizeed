<?php
/**
 * Dashboard Widgets
 *
 * @package  TriMS
 * @company  Triangle Mena <http://trianglemena.com>
 * @developer  Maroun Melhem <http://maroun.me>
 * @version 1.0
 *
 */

add_action('wp_dashboard_setup', 'trims_widgets');

function trims_widgets()
{
    global $wp_meta_boxes;

    wp_add_dashboard_widget('custom_help_widget', 'Triangle Mena Facebook Feed', 'trims_fb_widget');
}

function trims_fb_widget()
{
    ?>
    <div id="fb-root"></div>
    <script>(function (d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s);
            js.id = id;
            js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.9&appId=827159877349093";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>
    <div class="fb-page" data-href="https://www.facebook.com/TriangleMena/" data-tabs="timeline" data-width="500"
         data-small-header="true" data-adapt-container-width="true" data-hide-cover="true" data-show-facepile="true">
        <blockquote cite="https://www.facebook.com/TriangleMena/" class="fb-xfbml-parse-ignore"><a
                href="https://www.facebook.com/TriangleMena/">Triangle Mena</a></blockquote>
    </div>
    <?php
}

add_action('wp_dashboard_setup', 'remove_wpseo_dashboard_overview' );
function remove_wpseo_dashboard_overview() {
    remove_meta_box( 'wpseo-dashboard-overview', 'dashboard', 'side' );
}


