<?php
/*send user email when auction is ending in 15 mins*/
function tbid_15mins_auction_reminder_user($user_id, $auction_id)
{
    $user = get_user_by('id', $user_id);
    $user_email = $user->user_email;
    $user_username = $user->user_login;
    $auction_name = get_the_title($auction_id);
    $auction_link = get_the_permalink($auction_id);
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
                        Hurry up <b>' . $user_username . '</b>! The <b>' . $auction_name . '</b> auction you were bidding on is ending in 15 mins.
                    </p>
                    <p>
                        Click <b><a href="'.WP_SITEURL.'?msg=login&redirect_url=' . $auction_link . '">here</a></b> to bid on this auction.
                    </p>
                                        <p style="color:#808080;font-size:11px;">
<i>This email was sent via <a href="'.WP_SITEURL.'" style="font-size:11px;font-weight: normal;color:#000000;">'.WP_SITEURL.'</a>,<br><a href="*|UNSUB:'.WP_SITEURL.'unsubscribe|*" style="font-size:11px;font-weight: normal;color:#000000;">Unsubscribe?</a></i>
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
        'subject' => get_bloginfo('name').' | Auction ' . $auction_name . ' is ending in 15 mins!',
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
        'important' => true,
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