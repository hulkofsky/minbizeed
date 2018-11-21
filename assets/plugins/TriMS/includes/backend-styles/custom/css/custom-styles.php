<?php
/**
 * Custom Styles
 *
 * @package  TriMS
 * @company  Triangle Mena <http://trianglemena.com>
 * @developer  Maroun Melhem <http://maroun.me>
 * @version 1.0
 *
 */

function custom_styles()
{
    ?>
    <style type="text/css">
        #wpfooter #footer-upgrade {
            display: none !important;
        }
    </style>
    <?php
}

add_action('admin_head', 'custom_styles');