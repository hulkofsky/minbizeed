<?php
/*send user email after winning auction*/
function tbid_user_won_auction_user($user_id, $auction_id)
{
    $user = get_user_by('id', $user_id);
    $user_email = $user->user_email;
    $user_username = $user->user_login;
    $auction_name = get_the_title($auction_id);
    $html_msg = '
<html>
    <body style="margin:0px;padding:0px;">
    <style type="text/css">
    html,body{
    width:100%;
    margin:0px;
    padding:0px;
    color:#000;
    }
    .has_link a{
    color:#000;
    }
    </style>
        <table>
            <tr>
                <td style="padding:0px;vertical-align:top;">
                    <img style="width:100%;max-width:130px" src="' . get_template_directory_uri() . '/images/email-sidebar.png" />
                </td>
                <td style="vertical-align:top; padding:30px;font-family:lucida grande,tahoma,verdana,arial,sans-serif">
                    <p style="font-weight: lighter;font-size:20px;">
                        Congratulations <b>' . $user_username . '</b> you have won the <b>' . $auction_name . '</b> you were bidding on!
                    </p>
                    <p>
                        Click <a href="'.WP_SITEURL.'?msg=login&redirect_url=/my-account/trophy-room/">here</a> to buy it!
                    </p>
                                        <p style="color:#808080;font-size:11px;">
<i>This email was sent via <a href="'.WP_SITEURL.'" style="font-size:11px;font-weight: normal;color:#000000;">'.WP_SITENAME.'</a>,<br><a href="*|UNSUB:'.WP_SITEURL.'unsubscribe|*" style="font-size:11px;font-weight: normal;color:#000000;">Unsubscribe?</a></i>
</p>
            </tr>
        </table>
    </body>
</html>
';
    require_once(TEMPLATEPATH . '/mail/Mandrill.php');
    $mandrill = new Mandrill('_urLOPn4JH1b6Ms9iuhN-A');
    $message = array(
        'html' => $html_msg,
        'subject' => get_bloginfo('name').' | You have won your ' . $auction_name . '!',
        'from_email' => FROM_EMAIL,
        'from_name' => 'minbizeed',
        'to' => array(
            array(
                'email' => $user_email,
                'name' => $user_username,
                'type' => 'to'
            )
        ),
        'headers' => array('Reply-To' => FROM_EMAIL),
        'important' => false,
        'track_clicks' => null,
        'inline_css' => null,
        'url_strip_qs' => null,
        'preserve_recipients' => null,
        'view_content_link' => null,
        'return_path_domain' => null,
        'metadata' => array('website' => WEBSITE_URL),
        'recipient_metadata' => array(
            array(
                'rcpt' => $user_email,
                'values' => array('user_id' => $user_id)
            )
        ),
    );
    $result = $mandrill->messages->send($message);
}

/*send admin email after winning auction*/
function tbid_user_won_auction_admin($user_id, $auction_id)
{
    $user = get_user_by('id', $user_id);
    $user_email = $user->user_email;
    $user_username = $user->user_login;
    $questionable_ip = $_SERVER['REMOTE_ADDR'];
    $ip = get_ip_address();
    $agent = $_SERVER['HTTP_USER_AGENT'];
    date_default_timezone_set('Asia/Beirut');
    $time = date('l jS \of F Y h:i:s A');
    $auction_name = get_the_title($auction_id);
    $html_msg = '
<html>
    <body style="margin:0px;padding:0px;">
    <style type="text/css">
    html,body{
    width:100%;
    margin:0px;
    padding:0px;
    color:#37607D;
    }
    </style>
        <table>
            <tr>
                <td style="padding:0px;vertical-align:top;">
                    <img style="width:100%;max-width:130px" src="' . get_template_directory_uri() . '/newimages/email-sidebar.png" />
                </td>
                <td style="vertical-align:top; padding:30px;font-family:lucida grande,tahoma,verdana,arial,sans-serif">
                    <p style="font-weight: lighter;font-size:20px;">
                        User <b style="color:#EFA007;">' . $user_username . '</b> won auction <b style="color:#EFA007;">' . $auction_name . '</b> on '.get_bloginfo('name').'
                    </p>
                    <p>
                        Username: <b style="color:#EFA007;">' . $user_username . '</b>
                    </p>
                    <p>
                        Email: <b style="color:#EFA007;">' . $user_email . '</b>
                    </p>
                    <p>
                        Date: <b style="color:#EFA007;">' . $time . '</b>
                    </p>
                    <p>
                        Real IP: <b style="color:#EFA007;">' . $ip . '</b>
                    </p>
                    <p>
                        Questionable IP: <b style="color:#EFA007;">' . $questionable_ip . '</b>
                    </p>
                    <p>
                        User Agent: <b style="color:#EFA007;">' . $agent . '</b>
                    </p>
                    <p style="color:#808080;font-size:11px;">
                        <i>This email was sent via <a href="'.WP_SITEURL.'" style="font-size:11px;font-weight: normal;color:#000000;">'.WP_SITENAME.'</a></i>
                    </p>
                </td>
            </tr>
        </table>
    </body>
</html>
';
    require_once(TEMPLATEPATH . '/mail/Mandrill.php');
    $mandrill = new Mandrill('_urLOPn4JH1b6Ms9iuhN-A');
    $message = array(
        'html' => $html_msg,
        'subject' => get_bloginfo('name').' | User ' . $user_username . ' won the ' . $auction_name . '!',
        'from_email' => FROM_EMAIL,
        'from_name' => 'minbizeed',
        'to' => array(
            array(
                'email' => FROM_EMAIL,
                'name' => 'minbizeed',
                'type' => 'to'
            )
        ),
        'headers' => array('Reply-To' => FROM_EMAIL),
        'important' => false,
        'track_clicks' => null,
        'inline_css' => null,
        'url_strip_qs' => null,
        'preserve_recipients' => null,
        'view_content_link' => null,
        'return_path_domain' => null,
        'metadata' => array('website' => WEBSITE_URL),
    );
    $result = $mandrill->messages->send($message);
}