<?php
/**
 * Main Functions
 *
 * @package  mm-dashboard-sharing
 * @developer  Maroun Melhem <http://maroun.me>
 * @version 2.11
 *
 */

/*Adding sharing cols start*/

$mmds_disabled = get_option('disable_mmds');

if (!$mmds_disabled) {

    $mmds_disabled_pages = get_option('disable_mmds_pages');

    if (!$mmds_disabled_pages) {
        function mm_dashboard_sharing_pages_columns($columns)
        {
            $columns['mm_post_sharing'] = __('Share this post');
            return $columns;
        }

        add_filter('manage_pages_columns', 'mm_dashboard_sharing_pages_columns', 5);
        add_action('manage_pages_custom_column', 'mm_dashboard_sharing_col', 5, 2);
    }

    function mm_dashboard_sharing_posts_columns($columns)
    {
        $columns['mm_post_sharing'] = __('Share this post');
        return $columns;
    }

    add_filter('manage_posts_columns', 'mm_dashboard_sharing_posts_columns', 5);
    add_action('manage_posts_custom_column', 'mm_dashboard_sharing_col', 5, 2);

    function mm_dashboard_sharing_col($col, $id)
    {
        $mmds_disabled_fb = get_option('disable_mmds_fb');
        $mmds_disabled_tw = get_option('disable_mmds_tw');
        $mmds_disabled_gp = get_option('disable_mmds_gp');
        switch ($col) {
            case 'mm_post_sharing':
                $permalink = get_permalink($id);
                if (!$mmds_disabled_fb) {
                    $fb = '<a class="mmds_btns mmds_btns_fb" href="http://www.facebook.com/sharer/sharer.php?u=' . $permalink . '" target="_blank" title="Share on Facebook"><span class="dashicons dashicons-facebook"></span></a>';
                } else {
                    $fb = '';
                }

                if (!$mmds_disabled_tw) {
                    $tw = '<a class="mmds_btns mmds_btns_tw" style="margin:0 10px;" href="https://twitter.com/intent/tweet?text=' . get_post($id)->post_title . '. ' . $permalink . '" target="_blank" title="Tweet this Post"><span class="dashicons dashicons-twitter"></span></a>';
                } else {
                    $tw = '';
                }

                if (!$mmds_disabled_gp) {
                    $gp = '<a class="mmds_btns mmds_btns_gp" href="https://plus.google.com/share?url=' . $permalink . '" target="_blank" title="Share on G+"><span class="dashicons dashicons-googleplus"></span></a>';
                } else {
                    $gp = '';
                }

                $links = $fb . $tw . $gp;
                echo $links;
                break;
        }
    }
}
/*Adding sharing cols end*/
