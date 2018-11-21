<?php
/**
 * Login Styles
 *
 * @package  TriMS
 * @company  Triangle Mena <http://trianglemena.com>
 * @developer  Maroun Melhem <http://maroun.me>
 * @version 1.0
 *
 */

function custom_login()
{
    ?>
    <style type="text/css">
        body, html {
            background-color: #3e3d44 !important;
        }

        .login #backtoblog a, .login #nav a, .login h1 a {
            color: #fff !important;
        }

        .login #backtoblog a:hover, .login #nav a:hover, .login h1 a:hover {
            color: #e8e8e8 !important;
            text-decoration: underline !important;
        }

        .login #login h1 a {
            background-image: url(<?php echo WP_PLUGIN_URL.'/TriMS/includes/backend-styles/custom/images/logo.png' ?>) !important;
            padding-bottom: 0px !important;
            margin: 0px auto !important;
            height: 200px !important;
            width: 200px !important;
            background-size: unset !important;
        }

        .newsociallogins, #loginform h3 {
            display: none;
        }

        .wp-core-ui .button-primary {
            background: #3b5998 none repeat scroll 0 0 !important;
            border-color: #3b5998 !important;
            box-shadow: none !important;
            text-shadow: none !important;
            color: #fff !important;

        }

        .wp-core-ui .button-primary.focus, .wp-core-ui .button-primary.hover, .wp-core-ui .button-primary:focus, .wp-core-ui .button-primary:hover {
            background: #3071a9 none repeat scroll 0 0 !important;
            color: #fff !important;
        }

        #backtoblog {
            display: none !important;
        }

        .login .message {
            background-color: #fff;
            border-left: 4px solid #009a4e !important;
            box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.1) !important;
        }

        .login #login_error {
            border-left-color: #444343 !important;
        }
    </style>
    <?php
}

add_action('login_enqueue_scripts', 'custom_login');