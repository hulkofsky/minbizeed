<?php
/*
  Plugin Name: MM Custom Login
  Version: 2.0
  Author: Maroun Melhem
  Author URI: http://maroun.me
 */

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

function mm_login_errors()
{
    static $wp_error; // Will hold global variable safely
    return isset($wp_error) ? $wp_error : ($wp_error = new WP_Error(null, null, null));
}

function mm_show_login_error_messages()
{
    if ($codes = mm_login_errors()->get_error_codes()) {
        ?>
        <div class="mm_login_error error">
            <h2>Login Error:</h2>
            <?php
            $errors = 0;
            foreach ($codes as $code) {
                $errors++;
                $message = mm_login_errors()->get_error_message($code);
                ?>
                <p class="error_line">
                    <?php
                    echo $message;
                    ?>
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

        <input placeholder="Password" name="mm_user_pass" required="required" id="mm_user_pass" class="pw_fields input_fields"
               type="password"/>

        <div class="required_fields_identify">
            <div class="forgot_and_identify">
                <span class="required_identify">*Required fields</span>
                <span class="forgot_password"><a href="/forgot-password">Forgot Password?</a><br><a href="/register">or Register</a></span>
            </div>
            <div class="g-recaptcha" data-sitekey="6LfcxDkUAAAAAOZ1Gw03Sp4XqbiSajCUvpzXkAeG"></div>
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
        $user = get_user_by('login', $_POST['mm_user_login']);
        $user_pw=$user->data->user_pass;
        $user_id=$user->ID;
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
        if (!wp_check_password( $_POST['mm_user_pass'],$user_pw,$user_id)) {
            // if the password is incorrect for the specified user
            mm_login_errors()->add('empty_password', __('Incorrect password'));
        }

        if ($g_recaptcha_response == '') {
            //check google recaptcha
            mm_login_errors()->add('google_recaptcha_reg', __('Please verify Google Re-captcha'));
        }

        // retrieve all error messages
        $errors = mm_login_errors()->get_error_messages();

        // only log the user in if there are no errors
        if (empty($errors)) {

            /*Log user in*/
            wp_clear_auth_cookie();
            wp_set_current_user($user->ID);
            wp_set_auth_cookie($user->ID);

            /*Redirect after login*/
            $redirect_url = strip_tags($_GET['redirect_url']);
            if ($redirect_url) {
                wp_safe_redirect($redirect_url);
            } else {
                wp_safe_redirect(home_url());
            }
            exit;
        }
    }
}

add_action('init', 'mm_login_member');


