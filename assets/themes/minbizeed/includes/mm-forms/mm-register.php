<?php
/*
  Plugin Name: MM Custom Login
  Version: 2.0
  Author: Maroun Melhem
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


// used for tracking error messages
function mm_register_errors()
{
    static $wp_error; // Will hold global variable safely
    return isset($wp_error) ? $wp_error : ($wp_error = new WP_Error(null, null, null));
}


// displays error messages from form submissions
function mm_show_register_error_messages()
{

    if ($codes = mm_register_errors()->get_error_codes()) {
        ?>
        <div class="mm_register_error error">
            <?php
            $errors = 0;
            foreach ($codes as $code) {
                $errors++;
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
                    src: '.mm_register_error'
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
               required="required"
            <?php if ($user_login): echo "value: '" . $user_login . "'"; endif; ?>
        />


        <input placeholder="Email*" name="mm_user_email" id="mm_user_email"
               class="input_fields required"
               type="email"
               required="required"
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
               class="input_fields required pw_fields"
               required="required"
               type="password"
            <?php if ($user_pass): echo "value: '" . $user_pass . "'"; endif; ?>
        />

        <input placeholder="Password Confirmation*" name="mm_user_pass_confirm" id="password_again"
               class="input_fields required pw_fields" required="required" type="password"
            <?php if ($pass_confirm): echo "value: '" . $pass_confirm . "'"; endif; ?>
        />

        <div class="required_fields_identify">
            <span class="required_identify">*Required fields</span>
            <div class="g-recaptcha" data-sitekey="6LfcxDkUAAAAAOZ1Gw03Sp4XqbiSajCUvpzXkAeG"></div>
            <div class="clear"></div>
            <span class="forgot_password"><a href="/login">or Login</a></span>
        </div>

        <div class="submit_wrapper">
            <input type="hidden" name="mm_register_nonce" value="<?php echo wp_create_nonce('mm-register-nonce'); ?>"/>
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
        $g_recaptcha_response = $_POST['g-recaptcha-response'];

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

        //if ($g_recaptcha_response == '') {
        if ($g_recaptcha_response != '') {
            //check google recaptcha
            mm_register_errors()->add('google_recaptcha_reg', __('Please verify Google Re-captcha'));
        }

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

                /*Add user country*/
                add_user_meta($new_user_id, '_country_', $country);

                /*add 10 credits start*/
                increaseBids($new_user_id, 10, 'REGISTRATION_TRANSFER');

                //send email to admin on user registration
//                tbid_user_reg_user($new_user_id);

                /*log the new user in*/
                wp_clear_auth_cookie();
                wp_set_current_user($new_user_id);
                wp_set_auth_cookie($new_user_id);

                /*Redirect after login*/
                wp_redirect('/');
                exit;
            }
        }
    }
}

add_action('init', 'mm_add_new_member');
