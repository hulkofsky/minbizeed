<?php
/**
 * Bank gateway auction payment return
 *
 * @package  MinBizeed
 * @company  Triangle Mena <http://trianglemena.com>
 * @developer  Maroun Melhem <http://maroun.me>
 *
 * Template Name: MBZ Item order return PG
 */
get_header();
if (is_user_logged_in()) {
    // Initialisation
    // ==============
    //
    include('VPCPaymentConnection.php');
    $conn = new VPCPaymentConnection();


    // This is secret for encoding the MD5 hash
    // This secret will vary from merchant to merchant

    $secureSecret = "";

    // Set the Secure Hash Secret used by the VPC connection object
    $conn->setSecureSecret($secureSecret);


    // Set the error flag to false
    $errorsExist = false;


    // *******************************************
    // START OF MAIN PROGRAM
    // *******************************************


    // Add VPC post data to the Digital Order
    foreach ($_GET as $key => $value) {
        if (($key != "vpc_SecureHash") && ($key != "vpc_SecureHashType") && ((substr($key, 0, 4) == "vpc_") || (substr($key, 0, 5) == "user_"))) {
            $conn->addDigitalOrderField($key, $value);
        }
    }


    // Obtain a one-way hash of the Digital Order data and
    // check this against what was received.
    $secureHash = $conn->hashAllFields();

    if (array_key_exists("vpc_SecureHash", $_GET)) {
        if ($secureHash == $_GET["vpc_SecureHash"]) {
            $hashValidated = "<font color='#00AA00'><strong>CORRECT</strong></font>";
        } else {
            $hashValidated = "<font color='#FF0066'><strong>INVALID HASH</strong></font>";
            $errorsExist = true;
        }
    } else {
        $hashValidated = "<font color='#FF0066'><strong>NO HASH RETURNED</strong></font>";
    }


    // Extract the available receipt fields from the VPC Response
    // If not present then let the value be equal to 'Unknown'
    // Standard Receipt Data
    if (array_key_exists("Title", $_GET)) $Title = $_GET["Title"];
    if (array_key_exists("AgainLink", $_GET)) $againLink = $_GET["AgainLink"];
    if (array_key_exists("vpc_Amount", $_GET)) $amount = $_GET["vpc_Amount"];
    if (array_key_exists("vpc_Locale", $_GET)) $locale = $_GET["vpc_Locale"];
    if (array_key_exists("vpc_BatchNo", $_GET)) $batchNo = $_GET["vpc_BatchNo"];
    if (array_key_exists("vpc_Command", $_GET)) $command = $_GET["vpc_Command"];
    if (array_key_exists("vpc_Message", $_GET)) $message = $_GET["vpc_Message"];
    if (array_key_exists("vpc_Version", $_GET)) $version = $_GET["vpc_Version"];
    if (array_key_exists("vpc_Card", $_GET)) $cardType = $_GET["vpc_Card"];
    if (array_key_exists("vpc_OrderInfo", $_GET)) $orderInfo = $_GET["vpc_OrderInfo"];
    if (array_key_exists("vpc_ReceiptNo", $_GET)) $receiptNo = $_GET["vpc_ReceiptNo"];
    if (array_key_exists("vpc_Merchant", $_GET)) $merchantID = $_GET["vpc_Merchant"];
    if (array_key_exists("vpc_MerchTxnRef", $_GET)) $merchTxnRef = $_GET["vpc_MerchTxnRef"];
    if (array_key_exists("vpc_AuthorizeId", $_GET)) $authorizeID = $_GET["vpc_AuthorizeId"];
    if (array_key_exists("vpc_TransactionNo", $_GET)) $transactionNo = $_GET["vpc_TransactionNo"];
    if (array_key_exists("vpc_AcqResponseCode", $_GET)) $acqResponseCode = $_GET["vpc_AcqResponseCode"];
    if (array_key_exists("vpc_TxnResponseCode", $_GET)) $txnResponseCode = $_GET["vpc_TxnResponseCode"];

    // Obtain the 3DS response
    if (array_key_exists("vpc_3DSECI", $_GET)) $vpc_3DSECI = $_GET["vpc_3DSECI"];
    if (array_key_exists("vpc_3DSXID", $_GET)) $vpc_3DSXID = $_GET["vpc_3DSXID"];
    if (array_key_exists("vpc_3DSenrolled", $_GET)) $vpc_3DSenrolled = $_GET["vpc_3DSenrolled"];
    if (array_key_exists("vpc_3DSstatus", $_GET)) $vpc_3DSstatus = $_GET["vpc_3DSstatus"];
    if (array_key_exists("vpc_VerToken", $_GET)) $vpc_VerToken = $_GET["vpc_VerToken"];
    if (array_key_exists("vpc_VerType", $_GET)) $vpc_VerType = $_GET["vpc_VerType"];
    if (array_key_exists("vpc_VerStatus", $_GET)) $vpc_VerStatus = $_GET["vpc_VerStatus"];
    if (array_key_exists("vpc_VerSecurityLevel", $_GET)) $vpc_VerSecurityLevel = $_GET["vpc_VerSecurityLevel"];


    // CSC Receipt Data
    if (array_key_exists("vpc_CSCResultCode", $_GET)) $cscResultCode = $_GET["vpc_CSCResultCode"];
    if (array_key_exists("vpc_AcqCSCRespCode", $_GET)) $ACQCSCRespCode = $_GET["vpc_AcqCSCRespCode"];

    // AVS Receipt Data
    if (array_key_exists("vpc_AVSResultCode", $_GET)) $avsResultCode = $_GET["vpc_AVSResultCode"];
    if (array_key_exists("vpc_AcqAVSRespCode", $_GET)) $ACQAVSRespCode = $_GET["vpc_AcqAVSRespCode"];
    // Get the descriptions behind the QSI, CSC and AVS Response Codes
    // Only get the descriptions if the string returned is not equal to "No Value Returned".

    $txnResponseCodeDesc = "";
    $cscResultCodeDesc = "";
    $avsResultCodeDesc = "";

    if ($txnResponseCode != "No Value Returned") {
        $txnResponseCodeDesc = getResultDescription($txnResponseCode);
    }

    if ($cscResultCode != "No Value Returned") {
        $cscResultCodeDesc = getCSCResultDescription($cscResultCode);
    }

    if ($avsResultCode != "No Value Returned") {
        $avsResultCodeDesc = getAVSResultDescription($avsResultCode);
    }

    $error = "";
    // Show this page as an error page if error condition
    if ($txnResponseCode == "7" || $txnResponseCode == "No Value Returned") {
        $error = "Error ";
    }

    // FINISH TRANSACTION - Process the VPC Response Data
    // =====================================================
    // For the purposes of demonstration, we simply display the Result fields on a
    // web page.
    ?>
    <div class="buy_bids_page">
        <div class="page_identifier">
            <h2>Transaction result</h2>
        </div>
        <div class="main-container" role="main">

            <?php
            global $wpdb;

            /*Getting UserID and AuctionID start*/
            $s = "select * from `mb_272727023023_migs_transactions_data` where `vpc_MerchTxnRef`='$merchTxnRef' AND `verified`=1";
            $r = $wpdb->get_results($s);
            if ($r) {
                foreach ($r as $row) {
                    $user_id = $row->user_id;
                    $auction_id = $row->auction_id;
                    $auction_name = $row->vpc_OrderInfo;
                }
            }
            /*Getting UserID and AuctionID end*/

            /*Getting current time start*/
            date_default_timezone_set('Asia/Beirut');
            $date_time = date('m/d/Y h:i:s a', time());
            /*Getting current time end*/

            /*checking transaction exists start*/
            $transaction_exist_checker = "select * from `mb_272727023023_penny_payment_transactions` where `uid2`='$merchTxnRef'";
            $transaction_exist_checker_r = $wpdb->get_results($transaction_exist_checker);
            if (empty($transaction_exist_checker_r)) {
                $amount = $amount / 100;
                /*updating inSite Transactions start*/
                $insite_trans = $wpdb->insert(
                    'mb_272727023023_penny_payment_transactions',
                    array(
                        'uid' => $user_id,
                        'datemade' => "$date_time",
                        'amount' => "$amount",
                        'tp' => "$auction_name",
                        'uid2' => $merchTxnRef,
                    ),
                    array(
                        '%s',
                        '%s',
                        '%d',
                        '%s',
                        '%s',
                    )
                );
                /*updating inSite Transactions end*/

            }
            /*checking transaction exists end*/


            /*Updating values and returning results according to gateway result start*/
            switch ($txnResponseCode) {
                case "0" :
                    $case = "0";

                    /*checking if bids were added in this transaction start*/
                    $bid_added_checker = "select * from `mb_272727023023_penny_payment_transactions` where `uid2`='$merchTxnRef'";
                    $bid_added_checker_r = $wpdb->get_results($bid_added_checker);
                    if ($bid_added_checker_r) {
                        foreach ($bid_added_checker_r as $row_bid_added_checker_r) {
                            $is_transmitted = $row_bid_added_checker_r->bid_added;
                        }
                    }
                    /*checking if bids were added in this transaction end*/
                    if ($is_transmitted == "NO") {
                        /*updating Auction paid start*/
                        $auction_update = $wpdb->update(
                            'mb_272727023023_postmeta',
                            array(
                                'meta_value' => "1"
                            ),
                            array(
                                'post_id' => $auction_id,
                                'meta_key' => "winner_paid"
                            ),
                            array(
                                '%d'
                            )
                        );
                        /*$auction_bids_update = $wpdb->update(
                            'mb_272727023023_penny_bids',
                            array(
                                'paid' => "1"
                            ),
                            array(
                                'pid' => $auction_id,
                                'uid' => "$user_id",
                                'winner' => '1'
                            ),
                            array(
                                '%d'
                            )
                        );*/
                        // MongoDB Changes
                        $collection = (new MongoDB\Client)->minbizeed->pennybids;
                        $auction_bids_update = $collection->updateMany(
                            [ 'pid' => $auction_id,'uid' => "$user_id",'winner' => 1],
                            [ '$set' => [ 'paid' => 1 ]]
                        );
                        // MongoDB Changes   

                        if ($auction_update && $auction_bids_update) {
                            /*Bid added trigger start*/
                            $bid_added_trigger = $wpdb->update(
                                'mb_272727023023_penny_payment_transactions',
                                array(
                                    'bid_added' => "YES",
                                ),
                                array(
                                    'uid2' => $merchTxnRef
                                ),
                                array(
                                    '%s',
                                )
                            );
                            /*Bid added trigger end*/
                            $result = "Transaction Successful, auction item successfully paid";
                            /*Send Email start*/
                            tbid_auction_item_purchase_user($user_id, $auction_id);
                            tbid_auction_item_purchase_admin($user_id, $auction_id);
                            /*Send Email end*/
                        } else {
                            /*Bid added trigger start*/
                            $bid_added_trigger = $wpdb->update(
                                'mb_272727023023_penny_payment_transactions',
                                array(
                                    'bid_added' => "ERROR",
                                ),
                                array(
                                    'uid2' => $merchTxnRef
                                ),
                                array(
                                    '%s',
                                )
                            );
                            /*Bid added trigger end*/
                            $result = "Transaction Successful, BUT there was a problem paying for the auction.<br>Please <a href='/contact-us'>contact</a> us here";
                        }
                    }
                    /*updating Auction paid end*/

                    /*Send Email start*/
//                minbizeed_send_email_when_bids_have_been_purchased($user_id, $package_bid);
//                minbizeed_send_email_when_bids_have_been_purchased_admin($user_id, $package_bid);
                    /*Send Email end*/

                    break;
                case "?" :
                    $case = "?";
                    $result = "Transaction status is unknown";
                    break;
                case "E" :
                    $case = "E";
                    $result = "Referred";
                    break;
                case "1" :
                    $case = "1";
                    $result = "Transaction Declined";
                    break;
                case "2" :
                    $case = "2";
                    $result = "Bank Declined Transaction";
                    break;
                case "3" :
                    $case = "3";
                    $result = "No Reply from Bank";
                    break;
                case "4" :
                    $case = "4";
                    $result = "Expired Card";
                    break;
                case "5" :
                    $case = "5";
                    $result = "Insufficient funds";
                    break;
                case "6" :
                    $case = "6";
                    $result = "Error Communicating with Bank";
                    break;
                case "7" :
                    $case = "7";
                    $result = "Payment Server detected an error";
                    break;
                case "8" :
                    $case = "8";
                    $result = "Transaction Type Not Supported";
                    break;
                case "9" :
                    $case = "9";
                    $result = "Bank declined transaction (Do not contact Bank)";
                    break;
                case "A" :
                    $case = "A";
                    $result = "Transaction Aborted";
                    break;
                case "C" :
                    $case = "C";
                    $result = "Transaction Cancelled";
                    break;
                case "D" :
                    $case = "D";
                    $result = "Deferred transaction has been received and is awaiting processing";
                    break;
                case "F" :
                    $case = "F";
                    $result = "3D Secure Authentication failed";
                    break;
                case "I" :
                    $case = "I";
                    $result = "Card Security Code verification failed";
                    break;
                case "L" :
                    $case = "L";
                    $result = "Shopping Transaction Locked (Please try the transaction again later)";
                    break;
                case "N" :
                    $case = "N";
                    $result = "Cardholder is not enrolled in Authentication scheme";
                    break;
                case "P" :
                    $case = "P";
                    $result = "Transaction has been received by the Payment Adaptor and is being processed";
                    break;
                case "R" :
                    $case = "R";
                    $result = "Transaction was not processed - Reached limit of retry attempts allowed";
                    break;
                case "S" :
                    $case = "S";
                    $result = "Duplicate SessionID (Amex Only)";
                    break;
                case "T" :
                    $case = "T";
                    $result = "Address Verification Failed";
                    break;
                case "U" :
                    $case = "U";
                    $result = "Card Security Code Failed";
                    break;
                case "V" :
                    $case = "V";
                    $result = "Address Verification and Card Security Code Failed";
                    break;
                default  :
                    $case = "UNDEFINED";
                    $result = "Unable to be determined";
            }
            /*Updating values and returning results according to gateway result end*/

            /*updating inSite Transactions start*/
            $insite_trans = $wpdb->update(
                'mb_272727023023_penny_payment_transactions',
                array(
                    'reason' => "Bank Result: $case, Bank Payment: $result",
                    'status' => $case
                ),
                array(
                    'uid2' => $merchTxnRef
                ),
                array(
                    '%s',
                    '%s',
                )
            );
            /*updating inSite Transactions end*/

            /*Updating Migs Transactions start*/
            $migs_transaction_update = $wpdb->update(
                'mb_272727023023_migs_transactions_data',
                array(
                    'done' => 1,
                    'status' => $result,
                    'bank_result' => $case,
                ),
                array(
                    'vpc_MerchTxnRef' => $merchTxnRef
                ),
                array(
                    '%d',
                    '%s',
                    '%s'
                )
            );
            /*Updating Migs Transactions end*/


            if ($result) {
                ?>
                <div class="payment_result">
                    <?php
                    if ($case == "0") {
                        ?>
                        <img src="<?php echo get_template_directory_uri(); ?>/images/check_yes.png" alt="Yes"/>
                        <p>
                            <i class="fa fa-smile-o" aria-hidden="true"></i>
                            <?php echo $result; ?>
                        </p>
                        <p class="again">Click <a href="/">here</a> to go home</p>
                        <?php
                    } else {
                        ?>
                        <img src="<?php echo get_template_directory_uri(); ?>/images/check_no.png" alt="No"/>
                        <p>
                            <i class="fa fa-frown-o" aria-hidden="true"></i>
                            <?php echo $result; ?></br>
                        </p>
                        <p class="again">Click <a href="/my-account/payments/">here</a> to try again</p>
                        <?php
                    }
                    ?>
                </div>
                <?php
            } else {
                ?>
                <div class="payment_result">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/check_no.png" alt="No"/>
                    <p>
                        <i class="fa fa-frown-o" aria-hidden="true"></i>
                        No feedback provided from the bank.<br>Please <a href='/contact-us'>contact</a> us here
                    </p>
                </div>
                <?php
            }
            ?>
        </div>
        <div class="page_overlay"></div>
    </div>
    <div class="clear"></div>
    <?php
} else {
    ?>
    <input type="hidden" name="order_red_0" value="/?msg=buy_bids">
    <script type="text/javascript">
        var redirect_to = jQuery('input[name="order_red_0"]').val();
        window.location.replace(redirect_to);
    </script>
    <?php
}
get_footer();

