<?php
/*send user email after losing auction*/
function tbid_user_lost_auction_user($user_id,$auction_id,$winner_id){
    $user = get_user_by('id', $user_id);
    $user_email = $user->user_email;
    $user_username = $user->user_login;
    $auction_name=get_the_title($auction_id);
    $user_winner = get_user_by('id', $winner_id);
    $winner_name = $user_winner->user_login;
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
                    <img style="width:100%;max-width:130px" src="' . get_template_directory_uri() . '/newimages/email-sidebar.png" />
                </td>
                <td style="vertical-align:top; padding:30px;font-family:lucida grande,tahoma,verdana,arial,sans-serif">
                    <p style="font-weight: lighter;font-size:20px;">
                        Sorry <b>' . $user_username . '</b>, <b>' . $winner_name . '</b> have won the <b>' . $auction_name . '</b> you were bidding on :(
                    </p>
                    <p>
                        Better luck next time!
                    </p>
                                        <p style="color:#808080;font-size:11px;">
<i>This email was sent via <a href="http://test.dev.minbizeed.com" style="font-size:11px;font-weight: normal;color:#000000;">test.dev.minbizeed.com</a>,<br><a href="*|UNSUB:http://test.dev.minbizeed.com/unsubscribe|*" style="font-size:11px;font-weight: normal;color:#000000;">Unsubscribe?</a></i>
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
        'subject' => 'Min Bi Zeed | Sorry, '.$winner_name.' has won the '.$auction_name,
        'from_email' => 'info@test.dev.minbizeed.com',
        'from_name' => 'minbizeed',
        'to' => array(
            array(
                'email' => $user_email,
                'name' => $user_username,
                'type' => 'to'
            )
        ),
        'headers' => array('Reply-To' => 'info@test.dev.minbizeed.com'),
        'important' => false,
        'track_clicks' => null,
        'inline_css' => null,
        'url_strip_qs' => null,
        'preserve_recipients' => null,
        'view_content_link' => null,
        'return_path_domain' => null,
        'metadata' => array('website' => 'www.test.dev.minbizeed.com'),
        'recipient_metadata' => array(
            array(
                'rcpt' => $user_email,
                'values' => array('user_id' => $user_id)
            )
        ),
    );
    $result = $mandrill->messages->send($message);
}