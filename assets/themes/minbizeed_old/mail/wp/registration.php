<?php
/*Stop WP emails start*/
add_action('phpmailer_init', 'minbizeed_intercept_registration_email');
function minbizeed_intercept_registration_email($phpmailer){
    $admin_email = get_option( 'admin_email' );

    # Intercept username and password email by checking subject line
    if( strpos($phpmailer->Subject, 'Your username and password info') ){
        # clear the recipient list
        $phpmailer->ClearAllRecipients();
        # optionally, send the email to the WordPress admin email
        $phpmailer->AddAddress($admin_email);
    }else{
        #not intercepted
    }
}
/*Stop WP emails end*/