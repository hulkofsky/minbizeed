<?php
/*
  Plugin Name: MM Custom Login
  Plugin URI: http://maroun.me/mm-custom-login
  Description: Embed WordPress login and register forms in your website
  Version: 1.0
  Author: marounmelhem
  Author URI: http://maroun.me
 */

// user registration login form
function mm_registration_form()
{
    // only show the registration form to non-logged-in members
    if (!is_user_logged_in()) {

        // check to make sure user registration is enabled
        $registration_enabled = get_option('users_can_register');

        // only show the registration form if allowed
        if ($registration_enabled) {
            $output = mm_registration_form_fields();
        } else {
            $output = __('User registration is not enabled');
        }
        return $output;
    } else {
        $output = __('You are already logged in');
        return $output;
    }
}

add_shortcode('mm_register_form', 'mm_registration_form');

// user login form
function mm_login_form()
{

    if (!is_user_logged_in()) {
        $output = mm_login_form_fields();
    } else {
        $output = __('You are already logged in');
    }
    return $output;
}

add_shortcode('mm_login_form', 'mm_login_form');

// registration form fields
function mm_registration_form_fields()
{

    ob_start();
    // show any error messages after form submission
    mm_show_register_error_messages();

    $user_login = $_POST["mm_user_login"];
    $user_email = $_POST["mm_user_email"];
    $user_first = $_POST["mm_user_first"];
    $user_last = $_POST["mm_user_last"];
    $user_pass = $_POST["mm_user_pass"];
    $pass_confirm = $_POST["mm_user_pass_confirm"];
    $country = $_POST['country'];
    ?>
    <form id="mm_registration_form" class="mm_form" action="" method="POST">

        <input placeholder="First Name" class="input_fields" name="mm_user_first" id="mm_user_first" type="text"
            <?php if ($user_first): echo "value: '" . $user_first . "'"; endif; ?>
        />


        <input placeholder="Last Name" class="input_fields" name="mm_user_last" id="mm_user_last" type="text"
            <?php if ($user_last): echo "value: '" . $user_last . "'"; endif; ?>
        />


        <input placeholder="Username*" name="mm_user_login" id="mm_user_login"
               class="input_fields required"
               type="text"
            <?php if ($user_login): echo "value: '" . $user_login . "'"; endif; ?>
        />


        <input placeholder="Email*" name="mm_user_email" id="mm_user_email"
               class="input_fields required"
               type="email"
            <?php if ($user_email): echo "value: '" . $user_email . "'"; endif; ?>
        />
        <div class="fields_wrapper country_fields">
            <div class="bfh-selectbox bfh-countries" data-country="<?php if ($country): echo "$country";
            else: echo "LB"; endif; ?>" data-flags="true">
                <input type="hidden" value="">
                <a class="bfh-selectbox-toggle" role="button" data-toggle="bfh-selectbox" href="#">
                    <span class="bfh-selectbox-option input-medium" data-option=""></span>
                    <b class="caret"></b>
                </a>

                <div class="bfh-selectbox-options">
                    <input type="text" class="bfh-selectbox-filter">

                    <div role="listbox">
                        <ul role="option">
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <input placeholder="Password*" name="mm_user_pass" id="password"
               class="input_fields required"
               type="password"
            <?php if ($user_pass): echo "value: '" . $user_pass . "'"; endif; ?>
        />

        <input placeholder="Password Confirmation*" name="mm_user_pass_confirm" id="password_again"
               class="input_fields required" type="password"
            <?php if ($pass_confirm): echo "value: '" . $pass_confirm . "'"; endif; ?>
        />
        <input type="hidden" name="mm_register_nonce"
                       value="<?php echo wp_create_nonce('mm-register-nonce'); ?>"/>
        <!--        <div class="required_fields_identify">-->
        <!--            <span class="required_identify">Required fields</span>-->
        <!--            <div class="g-recaptcha" data-sitekey="6LfcxDkUAAAAAOZ1Gw03Sp4XqbiSajCUvpzXkAeG"></div>-->
        <!--            <div class="clear"></div>-->
        <!--        </div>-->

        <div class="submit_wrapper">
            <input id="mm_register_submit" type="submit" class="submit_button" value="SIGN UP"/>
        </div>
    </form>

    <script>
        $(document).ready(function (e) {
            $(document).find(".bfh-countries input[type=hidden]").attr("name", "country");
        });
    </script>

    <?php
    return ob_get_clean();
}

// login form fields
function mm_login_form_fields()
{

    ob_start();
    // show any error messages after form submission
    mm_show_login_error_messages();
    ?>

    <form id="mm_login_form" class="mm_form" action="" method="post">

        <input placeholder="Email or Username" required="required" name="mm_user_login" id="mm_user_login"
               class="input_fields" type="text"/>

        <input placeholder="Password" name="mm_user_pass" required="required" id="mm_user_pass" class="input_fields"
               type="password"/>

        <div class="required_fields_identify">
            <div class="forgot_and_identify">
                <span class="required_identify">Required fields</span>
                <span class="forgot_password"><a href="/forgot-password">Forgot Password?</a></span>
            </div>
<!--            <div class="g-recaptcha" data-sitekey="6LdMhzoUAAAAAEdqu7Af9Hrt6RWUC4LFzqiTXNcV"></div>-->
        </div>

        <div class="submit_wrapper">
            <input type="hidden" name="mm_login_nonce" value="<?php echo wp_create_nonce('mm-login-nonce'); ?>"/>
            <input id="mm_login_submit" class="submit_button" type="submit" value="Login"/>
        </div>
    </form>
    <?php
    return ob_get_clean();
}

// logs a member in after submitting a form
function mm_login_member()
{

    if (isset($_POST['mm_user_login']) && wp_verify_nonce($_POST['mm_login_nonce'], 'mm-login-nonce')) {

        // this returns the user ID and other info from the user name
        $user = get_userdatabylogin($_POST['mm_user_login']);
        $g_recaptcha_response = $_POST['g-recaptcha-response'];

        if (!$user) {
            // if the user name doesn't exist
            mm_login_errors()->add('empty_username', __('Invalid username'));
        }

        if (!isset($_POST['mm_user_pass']) || $_POST['mm_user_pass'] == '') {
            // if no password was entered
            mm_login_errors()->add('empty_password', __('Please enter a password'));
        }

        // check the user's login with their password
        if (!wp_check_password($_POST['mm_user_pass'], $user->user_pass, $user->ID)) {
            // if the password is incorrect for the specified user
            mm_login_errors()->add('empty_password', __('Incorrect password'));
        }

        //        if ($g_recaptcha_response == '') {
//            //check google recaptcha
//            mm_register_errors()->add('google_recaptcha', __('Please verify Google Re-captcha'));
//        }

        // retrieve all error messages
        $errors = mm_login_errors()->get_error_messages();

        // only log the user in if there are no errors
        if (empty($errors)) {

            wp_setcookie($_POST['mm_user_login'], $_POST['mm_user_pass'], true);
            wp_set_current_user($user->ID, $_POST['mm_user_login']);
            do_action('wp_login', $_POST['mm_user_login']);

            $redirect_url = strip_tags($_GET['redirect_url']);
            if ($redirect_url) {
                wp_redirect($redirect_url);
            } else {
                wp_redirect(home_url());
            }
            exit;
        }
    }
}

add_action('init', 'mm_login_member');

// register a new user
function mm_add_new_member()
{
    if (isset($_POST["mm_user_login"]) && wp_verify_nonce($_POST['mm_register_nonce'], 'mm-register-nonce')) {
        $user_login = $_POST["mm_user_login"];
        $user_email = $_POST["mm_user_email"];
        $user_first = $_POST["mm_user_first"];
        $user_last = $_POST["mm_user_last"];
        $user_pass = $_POST["mm_user_pass"];
        $pass_confirm = $_POST["mm_user_pass_confirm"];
        $country = $_POST['country'];
//        $g_recaptcha_response = $_POST['g-recaptcha-response'];

        // this is required for username checks
        require_once(ABSPATH . WPINC . '/registration.php');

        if ($user_login == '') {
            // empty username
            mm_register_errors()->add('username_empty', __('Please enter a username'));
        } else {

            if (username_exists($user_login)) {
                // Username already registered
                mm_register_errors()->add('username_unavailable', __('Username already taken'));
            } else {
                if (!validate_username($user_login)) {
                    // invalid username
                    mm_register_errors()->add('username_invalid', __('Invalid username'));
                }
            }
        }

        if ($user_email == '') {
            mm_register_errors()->add('email_empty', __('Please enter an email'));
        } else {
            if (!is_email($user_email)) {
                //invalid email
                mm_register_errors()->add('email_invalid', __('Invalid email'));
            }

            if (email_exists($user_email)) {
                //Email address already registered
                mm_register_errors()->add('email_used', __('Email already registered'));
            }
        }
        if ($user_pass == '') {
            // passwords do not match
            mm_register_errors()->add('password_empty', __('Please enter a password'));
        } else {
            if ($user_pass != $pass_confirm) {
                // passwords do not match
                mm_register_errors()->add('password_mismatch', __('Passwords do not match'));
            }
        }

//        if ($g_recaptcha_response == '') {
//            //check google recaptcha
//            mm_register_errors()->add('google_recaptcha', __('Please verify Google Re-captcha'));
//        }

        $errors = mm_register_errors()->get_error_messages();

        // only create the user in if there are no errors
        if (empty($errors)) {

            $new_user_id = wp_insert_user(array(
                    'user_login' => $user_login,
                    'user_pass' => $user_pass,
                    'user_email' => $user_email,
                    'first_name' => $user_first,
                    'last_name' => $user_last,
                    'user_registered' => date('Y-m-d H:i:s'),
                    'role' => 'subscriber'
                )
            );
            if ($new_user_id) {

                /*
                 * Add user country
                 */
                add_user_meta($new_user_id, 'country', $country);
                add_user_meta($new_user_id, '_country_', $country);

                /*add 10 credits*/
                add_user_meta($new_user_id, 'user_credits', 10);


                //send email to admin on user registration
//                tbid_user_reg_user($new_user_id);


                // log the new user in
                wp_setcookie($user_login, $user_pass, true);
                wp_set_current_user($new_user_id, $user_login);
                do_action('wp_login', $user_login);

                // send the newly created user to the home page after logging them in
                wp_redirect('/');
                exit;
            }
        }
    }
}

add_action('init', 'mm_add_new_member');

// used for tracking error messages
function mm_register_errors()
{
    static $wp_error; // Will hold global variable safely
    return isset($wp_error) ? $wp_error : ($wp_error = new WP_Error(null, null, null));
}

function mm_login_errors()
{
    static $wp_error; // Will hold global variable safely
    return isset($wp_error) ? $wp_error : ($wp_error = new WP_Error(null, null, null));
}

// displays error messages from form submissions
function mm_show_register_error_messages()
{
    if ($codes = mm_register_errors()->get_error_codes()) {
        ?>
        <div class="mm_login_error error">
            <?php
            foreach ($codes as $code) {
                $message = mm_register_errors()->get_error_message($code);
                ?>
                <p class="error_line">
                    <?php echo $message; ?>
                </p>
                <?php
            }
            ?>
        </div>
        <script type="text/javascript">
            $.magnificPopup.open({
                items: {
                    src: '.mm_login_error'
                },
                type: 'inline',
                closeOnBgClick: false,
                closeMarkup: '<button title="Close (Esc)" type="button" class="mfp-close fa fa-close"></button>',
            });
            jQuery('.mfp-content').addClass('error');
        </script>
        <?php
    }
}

function mm_show_login_error_messages()
{
    if ($codes = mm_login_errors()->get_error_codes()) {
        echo '<div class="mm_errors">';
        // Loop error codes and display errors
        foreach ($codes as $code) {
            $message = mm_login_errors()->get_error_message($code);
            echo '<span class="error"><strong>' . __('Error') . '</strong>: ' . $message . '</span><br/>';
        }
        echo '</div>';
    }
}


