<?php
/*
 * Template Name: MBZ Mail function TEST
 */
get_header();
$user_username = "Maroun";
$auction_name = "iPhone 7";
$auction_link = "/";
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
    font-weight: bold;
    }
    </style>
        <table style="margin:200px 0 0;">
            <tr>
                <td style="padding:0px;vertical-align:top;">
                    <img style="width:100%;max-width:130px" src="' . get_template_directory_uri() . '/images/email-sidebar.png" />
                </td>
                <td style="vertical-align:top; padding:30px;font-family:lucida grande,tahoma,verdana,arial,sans-serif">
                    <p style="font-weight: lighter;font-size:20px;">
                        Hurry up <b>' . $user_username . '</b>! The <b>' . $auction_name . '</b> auction you were bidding on is ending in 15 mins.
                    </p>
                    <p>
                        Click <b><a style="color:#000;" href="' . $auction_link . '">here</a></b> to bid on this auction.
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

?>
    <br/>
    <br/>
    <br/>
    <br/>
    <br/>
    <br/>
    <br/>
    <br/>

<?php
$html_msg_2 = '<html style="margin:0px;padding:0px;color:#000;width:100%;">
    <body style="margin:0px;padding:0px;color:#000;width:100%;">
    <style type="text/css">
    p a{
    color:#DF222E;
    font-weight: bold;
    text-decoration: none;
    }
    </style>
        <table>
            <tr>
                <td style="padding:0px;vertical-align:top;width:15%">
                    <img style="width:100%;max-width:130px" src="http://test.dev.minbizeed.com/assets/themes/minbizeed/images/email-sidebar.png" />
                </td>
                <td style="vertical-align:top; padding:10px;font-family:lucida grande,tahoma,verdana,arial,sans-serif">
                    <p style="font-weight: lighter;font-size:16px;margin:0 0 10px;">
                        Hello {{username}} and welcome to Min Bi Zeed!
                    </p>
                    <p style="font-weight: lighter;font-size:16px;line-height:27px;margin:20px 0;">
                        Please use the following credentials to login to your account:
                    </p>
                    <p style="font-weight: lighter;font-size:16px;line-height:27px;margin:20px 0;">
                        Login: {{site_url}}
                    </p>
                    <p style="font-weight: lighter;font-size:16px;line-height:27px;margin:20px 0;">
                        Username: {{username}}
                    </p>
                    <p style="font-weight: lighter;font-size:16px;line-height:27px;margin:20px 0;">
                        Password: {{password}}
                    </p>
                    <p style="color:#808080;font-size:11px;">
                        <i>This email was sent via <a href="http://test.dev.minbizeed.com" style="font-size:11px;font-weight: normal;color:#000000;">test.dev.minbizeed.com</a>,<br><a href="*|UNSUB:http://www.test.dev.minbizeed.com/unsubscribe|*" style="font-size:11px;font-weight: normal;color:#000000;">Unsubscribe?</a></i>
                    </p>
                </td>
            </tr>
        </table>
    </body>
</html>';

echo $html_msg_2;
get_footer();

