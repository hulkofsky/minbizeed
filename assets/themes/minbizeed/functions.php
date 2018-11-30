<?php
session_start();
/*Disable errors start*/
error_reporting(0);
ini_set("display_errors", "off");
/*Disable errors end*/

//---------------------------------------------------------------------------------------

global $current_theme_locale_name;
$current_theme_locale_name = 'minbizeed';
//echo TEMPLATEPATH;exit;
//---------------------------------------------------------------------------------------


add_action('after_setup_theme', 'mb_remove_admin_bar');

function mb_remove_admin_bar()
{
    show_admin_bar(false);
}

/*Functions files end*/

/*Enqueues Functions start*/
require_once(TEMPLATEPATH . '/includes/functions/enqueues.php');
/*Enqueues Functions end*/

/*Code Functions start*/
require_once(TEMPLATEPATH . '/includes/functions/code_functions.php');
/*Code Functions  end*/

/*Core start*/
require_once(TEMPLATEPATH . '/includes/functions/core.php');
/*Core end*/

/*WP BS start*/
require_once(TEMPLATEPATH . '/includes/functions/wp_bs_pagination.php');
/*WP BS end*/

/*Functions files end*/

/*Backend functions files start*/
require_once(TEMPLATEPATH . '/includes/backend_functions/admin_menu.php');

require_once(TEMPLATEPATH . '/includes/backend_functions/option-pages.php');
require_once(TEMPLATEPATH . '/includes/backend_functions/custom-post.php');

/*Tabs files start*/
require_once(TEMPLATEPATH . '/includes/backend_functions/tabs/not_fullfilled_refunds.php');
require_once(TEMPLATEPATH . '/includes/backend_functions/tabs/hist_transact.php');
require_once(TEMPLATEPATH . '/includes/backend_functions/tabs/site_summary.php');
require_once(TEMPLATEPATH . '/includes/backend_functions/tabs/orders_main_screen.php');
require_once(TEMPLATEPATH . '/includes/backend_functions/tabs/bid_packages.php');
require_once(TEMPLATEPATH . '/includes/backend_functions/tabs/user_balances.php');
require_once(TEMPLATEPATH . '/includes/backend_functions/tabs/user_bids.php');
require_once(TEMPLATEPATH . '/includes/backend_functions/tabs/min_price_calculator.php');
require_once(TEMPLATEPATH . '/includes/backend_functions/tabs/realtime_notifications.php');
require_once(TEMPLATEPATH . '/includes/backend_functions/tabs/stats/payments-stats.php');
require_once(TEMPLATEPATH . '/includes/backend_functions/tabs/stats/bids-stats.php');
require_once(TEMPLATEPATH . '/includes/backend_functions/tabs/stats/free-bids.php');
/*Tabs files end*/

/*Auction edit fields start*/
require_once(TEMPLATEPATH . '/includes/backend_functions/auctions/users-bids.php');
require_once(TEMPLATEPATH . '/includes/backend_functions/auctions/winner.php');
//require_once(TEMPLATEPATH . '/includes/backend_functions/auctions/auction_bids.php');
/*Auction edit fields end*/

/*AJAX files start*/
require_once(TEMPLATEPATH . '/includes/backend_functions/ajax/credits.php');
require_once(TEMPLATEPATH . '/includes/backend_functions/ajax/orders.php');
require_once(TEMPLATEPATH . '/includes/backend_functions/ajax/realtime_notifications.php');
/*AJAX files start*/

/*Backend functions files end*/

/*Post templates start*/
require_once(TEMPLATEPATH . '/includes/posts_templates/posts_templates.php');
/*Post templates end*/

/*Lib start*/
/*Lib end*/

/*Mail processing start*/
require_once(TEMPLATEPATH . '/mail/mail_templates.php');
/*Mail processing end*/

/*WP mail interception start*/
require_once(TEMPLATEPATH . '/mail/wp/registration.php');
/*WP mail interception end*/

/*MM login start*/
require_once(TEMPLATEPATH . '/includes/mm-forms/mm-login.php');
require_once(TEMPLATEPATH . '/includes/mm-forms/mm-register.php');
/*MM login end*/

/*AJAX start*/
require_once(TEMPLATEPATH . '/includes/ajax/update_country.php');
require_once(TEMPLATEPATH . '/includes/ajax/update_personal_info.php');
require_once(TEMPLATEPATH . '/includes/ajax/update_pw.php');
/*AJAX end*/

/*-*****Functions end*****-*/

if (!function_exists('mb_setup')) :

    function mb_setup()
    {
        add_theme_support('post-thumbnails');
        add_image_size('auction_image', 547, 400, true);
        add_image_size('auction_image_single', 505, 500, true);
        add_image_size('users_profile', 200, 200, true);

        /**
         * Remove wpautop filter from content
         * (Remove <p> wrapping the text)
         */
//        remove_filter('the_content', 'wpautop');
    }

endif;
add_action('after_setup_theme', 'mb_setup');


add_action('init', 'minbizeed_redirect_logged_in_user');
function minbizeed_redirect_logged_in_user(){
    if(is_user_logged_in()){
        $redirect_url = strip_tags($_GET['redirect_url']);
        if($redirect_url){
            wp_redirect($redirect_url);
            die;
        }
    }
}


//function minbizeed_no_admin_access() {
//    if (is_admin() && !current_user_can('administrator') && !current_user_can('editor') && !(defined('DOING_AJAX') && DOING_AJAX)) {
//        $redirect = isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : home_url( '/' );
//        global $current_user;
//        $user_roles = $current_user->roles;
//        $user_role = array_shift($user_roles);
//        if($user_role === 'subscriber'){
//            exit( wp_redirect( $redirect ) );
//        }
//    }
//}
//add_action( 'admin_init', 'minbizeed_no_admin_access', 100 );


add_filter('wp_mail_from', 'new_mail_from');
add_filter('wp_mail_from_name', 'new_mail_from_name');

function new_mail_from($old)
{
    return 'info@minbizeed.com';
}

function new_mail_from_name($old)
{
    return 'Min Bi zeed';
}

function set_encrypted_session_id_cookie()
{
    $sessionId = session_id();
    $encrypted = base64_encode(mcrypt_encrypt('rijndael-128', '9031606119255751', $sessionId, 'ecb'));
    setrawcookie("_auth", $encrypted, 0, '/');
}

add_action( 'show_user_profile', 'ds_extra_profile_fields' );
add_action( 'edit_user_profile', 'ds_extra_profile_fields' );

function ds_extra_profile_fields( $user ) {
    $status_usr = get_the_author_meta( '_user_status', $user->ID );
    ?>

    <table class="form-table">
        <tr>
            <th><label for="usr_bl"><?php _e( 'Block User Status', 'minbized' ); ?></label></th>
            <td>
            <?php 
            $block_options = array(
                'unblocked' => 'Unblocked',
                'blocked_daily' => 'Blocked Daily',
                'blocked_weekly' => 'Blocked Weekly'

            );
            ?>
            <select name="blocking_user" id="usr_bl">
            <?php foreach( $block_options as $key => $option ): ?>
                <option value="<?php echo $key; ?>" <?php echo ($status_usr === $key) ? 'selected' : ''; ?>><?php _e($option, 'minbized') ?></option>
            <?php endforeach; ?>
            </select>
            </td>
        </tr>
    </table>
    <?php
}

add_action( 'personal_options_update', 'extr_profile_fields' );
add_action( 'edit_user_profile_update', 'extr_profile_fields' );

function extr_profile_fields( $user_id ) {

   if ( !current_user_can( 'edit_user', $user_id ) )
       return false;

   update_usermeta( $user_id, '_user_status', $_POST['blocking_user'] );
}
/**
 * Set blocked To date
 */
add_action( 'show_user_profile', 'block_user_to' );
add_action( 'edit_user_profile', 'block_user_to' );

function block_user_to( $user ) {
    $start_block_date = get_the_author_meta( '_blocked_to', $user->ID );
    
    $sec = $start_block_date / 1000;

    $retDate = date("Y-m-d H:i:s", $sec);
            ?>
    <table class="form-table_usr_date_block form-table">
        <tr>
        <th><label for="blocked_date"><?php _e('Blocked Until: ', 'minbizeed') ?></label></th>
            <td>
            <input type="text" name="user_blocked_date" id="blocked_date" value="<?php echo $retDate; ?>">
            <input type="hidden" name="get_formatted_date" id="form_date_formatted" value="">
            <div class="dateC">
                <h2><?php _e('User will be unblocked in:', 'minbizeed'); ?> <span id="countdown"></span>
                </h2>
            </div>
            </td>
        </tr>
    </table>
    <?php
}

add_action( 'personal_options_update', 'svae_usr_blocked_to' );
add_action( 'edit_user_profile_update', 'svae_usr_blocked_to' );

function svae_usr_blocked_to( $user_id ) {

   if ( !current_user_can( 'edit_user', $user_id ) ){
       return false;
    }
       if(!empty($_POST['get_formatted_date'])){
        $yourdate = $_POST['get_formatted_date'];
        $stamp = strtotime($yourdate);
        $milFrom = $stamp * 1000;
        $time_in_ms_to_save = $milFrom;
       }

   update_usermeta( $user_id, '_blocked_to', $time_in_ms_to_save );
}
/**
 * create databese field on user registration
 */
add_action( 'user_register', 'fill_db_user_registration' );
function fill_db_user_registration( $user_id ) {
    $defDate = date( 'Y-m-d H:i:s' );
    $dds = strtotime( $defDate );
    $milisDefDate = $dds * 1000;

    update_user_meta( $user_id, '_blocked_to', $milisDefDate );
    update_user_meta( $user_id, '_user_status', 'unblocked');
    update_user_meta( $user_id, '_reserved_credits', '');
}


function add_datepicker_admin(){
    wp_enqueue_script('jq-ui-datetime', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.js', array('jquery'), '1.6.3');
    wp_enqueue_script('custom-admin-scripts', get_template_directory_uri() . '/js/admin_scripts.js');
}
add_action('admin_enqueue_scripts', 'add_datepicker_admin');