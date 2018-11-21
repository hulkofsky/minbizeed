<?php
/**
 * Bank gateway bids payment form
 *
 * @package  MinBizeed
 * @company  Triangle Mena <http://trianglemena.com>
 * @developer  Maroun Melhem <http://maroun.me>
 *
 * Template: Payment form order Form
 * Template Name: Payment form order Form template
 */
get_header();
if (is_user_logged_in()) {
    // *********************
    // START OF MAIN PROGRAM
    // *********************

    // Define Constants
    // ----------------
    // This is secret for encoding the MD5 hash
    // This secret will vary from merchant to merchant
    // To not create a secure hash, let SECURE_SECRET be an empty string - ""

    // test $securesecret = "BD6CB9730975B98D5ACB777FC625E307";
    $securesecret = "9014CC5CD0297198E9647AACD0347DEB";

    //Include VPCPaymentConnection.php file
    include('VPCPaymentConnection.php');
    $conn = new VPCPaymentConnection();


    // Set the Secure Hash Secret used by the VPC connection object
    $conn->setSecureSecret($securesecret);


    // *******************************************
    // START OF MAIN PROGRAM
    // *******************************************

    $_POST["virtualPaymentClientURL"] = "https://migs.mastercard.com.au/vpcpay";
    $_POST["vpc_Version"] = "1";
    $_POST["vpc_Command"] = "pay";
// test       $_POST["vpc_Merchant"] = "TEST79349999";
// test   $_POST["vpc_AccessCode"] = "8E07362B";
    $_POST["vpc_Merchant"] = "79349999";
    $_POST["vpc_AccessCode"] = "E03B264A";
    $_POST["vpc_ReturnURL"] = WP_SITEURL."order-return/";
    $_POST["vpc_Gateway"] = "ssl";


    $signature = strip_tags($_GET["signature"]);

    global $wpdb;
    $s = "select * from `tb_5550121_migs_transactions_data` where `signature`='$signature' AND `verified`=0";
    $r = $wpdb->get_results($s);
    if ($r) {
        foreach ($r as $row) {
            $org_amount = $row->vpc_Amount;
            $modded_amount = $org_amount * 100;
            $_POST["vpc_Amount"] = $modded_amount;
            $_POST["vpc_OrderInfo"] = $row->vpc_OrderInfo;
            $_POST["vpc_MerchTxnRef"] = $row->vpc_MerchTxnRef;
        }
        if ($_POST["vpc_Amount"] && $_POST["vpc_OrderInfo"] && $_POST["vpc_MerchTxnRef"]) {
            $migs_ver_update = $wpdb->update(
                'tb_5550121_migs_transactions_data',
                array(
                    'verified' => 1
                ),
                array('signature' => $signature),
                array(
                    '%d'
                ),
                array('%d')
            );
            if ($migs_ver_update) {

// add the start of the vpcURL querystring parameters
                $vpcURL = $_POST["virtualPaymentClientURL"];
                $redirectURL = $_POST["virtualPaymentClientURL"];

// Remove the Virtual Payment Client URL from the parameter hash as we
// do not want to send these fields to the Virtual Payment Client.
                unset($_POST["virtualPaymentClientURL"]);
                unset($_POST["btnPay"]);


// The URL link for the receipt to do another transaction.
// Note: This is ONLY used for this example and is not required for
// production code. You would hard code your own URL into your application.

// Create the request to the Virtual Payment Client which is a URL encoded GET
// request. Since we are looping through all the data we may as well sort it in
// case we want to create a secure hash and add it to the VPC data if the
// merchant secret has been provided.
//    $md5HashData = $SECURE_SECRET;
                ksort($_POST);

// set a parameter to show the first pair in the URL
                $appendAmp = 0;

                ?>
                <div class="buy_bids_page">
                    <div class="page_identifier">
                        <h2>Processing payment<span id="threeDotsType">.</span></h2>
                    </div>
                    <div class="bank_gw_holder main-container" role="main">

                        <body onload="document.order.submit()">

                        <div class="payment_order">
                            <p>Please wait while your payment is being processed</p>
                        </div>
                        <!--body-->
                        <form id="order" name="order" action="<?php echo($redirectURL); ?>" method="post">
                            <!-- input type="submit" name="submit" value="Continue"/ -->
                            <?php
                            $hashinput = "";
                            foreach ($_POST as $key => $value) {
                                // create the hash input and URL leaving out any fields that have no value
                                if (strlen($value) > 0) {

                                    ?>
                                    <input type="hidden" name="<?php echo($key); ?>" value="<?php echo($value); ?>"/>
                                    <br>
                                    <?php
                                    if ((strlen($value) > 0) && ((substr($key, 0, 4) == "vpc_") || (substr($key, 0, 5) == "user_"))) {
                                        $hashinput .= $key . "=" . $value . "&";
                                    }
                                }
                            }
                            $hashinput = rtrim($hashinput, "&");
                            ?>
                            <!-- attach SecureHash -->
                            <input type="hidden" name="vpc_SecureHash"
                                   value="<?php echo(strtoupper(hash_hmac('SHA256', $hashinput, pack('H*', $securesecret)))); ?>"/>
                            <input type="hidden" name="vpc_SecureHashType" value="SHA256">
                        </form>
                        <script>
                            jQuery(window).load(function () {
                                $('#order').submit();
                            });
                        </script>
                    </div>
                    <div class="page_overlay"></div>
                </div>
                <div class="clear"></div>
                <?php
            } else {
                ?>
                <input type="hidden" name="order_red_0" value="/buy-bids/?msg=sig_error">
                <script type="text/javascript">
                    var redirect_to = jQuery('input[name="order_red_0"]').val();
                    window.location.replace(redirect_to);
                </script>
                <?php
            }
        }
    } else {
        ?>
        <input type="hidden" name="order_red_1" value="/buy-bids/?msg=sig_error">
        <script type="text/javascript">
            var redirect_to = jQuery('input[name="order_red_1"]').val();
            window.location.replace(redirect_to);
        </script>
        <?php
    }
} else {
    ?>
    <input type="hidden" name="order_red_2" value="/?msg=buy_bids">
    <script type="text/javascript">
        var redirect_to = jQuery('input[name="order_red_2"]').val();
        window.location.replace(redirect_to);
    </script>
    <?php
}
get_footer();

