<?php

function minbizeed_display_my_account_purchase_bids_fncs()
{
    global $current_user;
    get_currentuserinfo();
    $uid = $current_user->ID;

    $ids = $_GET['bid_id'];
    $error_msg = strip_tags($_GET['msg']);
    ?>
    <div id="content" xmlns="http://www.w3.org/1999/html">
        <!-- page content here -->
        <div class="my_box3">
            <div class="section padd10">
                <div class="payment_holder">
                    <div class="row">
                        <?php
                        if ($error_msg == "sig_error") {
                            ?>
                            <input type="hidden" name="order_red_0" value="/buy-bids?msg=sig_error">
                            <script type="text/javascript">
                                var redirect_to = jQuery('input[name="order_red_0"]').val();
                                window.location.replace(redirect_to);
                            </script>
                            <?php
                        }
                        ?>
                        <?php
                        $bid_pckg = strip_tags($_GET['bid_id']);
                        global $wpdb;
                        $s = "select * from " . $wpdb->prefix . "penny_packages where `id`='$bid_pckg' order by cost asc";
                        $r = $wpdb->get_results($s);
                        foreach ($r as $row) {
                            $amount = $row->cost;
                        }
                        $user_id = get_current_user_id();
                        $timestamp = time();
                        $vpc_MerchTxnRef = $user_id . "_" . $timestamp;
                        $vpc_OrderInfo = "bid_package=$bid_pckg";
                        date_default_timezone_set('Asia/Beirut');
                        $date_time = date('m/d/Y h:i:s a', time());
                        $key = 'vqQNsl)%S)oddNvs8*D2#cOY';
                        $nonce = rand();
                        $timestamp = time();
                        $signature = hash_hmac('sha1', $_SERVER['REMOTE_ADDR'] . $nonce . $timestamp, $key);

                        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                            $user_ip = $_SERVER['HTTP_CLIENT_IP'];
                        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                            $user_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                        } else {
                            $user_ip = $_SERVER['REMOTE_ADDR'];
                        }

                        $transaction_insert = $wpdb->insert(
                            'tb_5550121_migs_transactions_data',
                            array(
                                'signature' => $signature,
                                'vpc_OrderInfo' => "$vpc_OrderInfo",
                                'vpc_MerchTxnRef' => "$vpc_MerchTxnRef",
                                'vpc_Amount' => "$amount",
                                'bid_package' => "$bid_pckg",
                                'user_id' => "$user_id",
                                'user_ip' => "$user_ip",
                                'date_time' => "$date_time",
                            ),
                            array(
                                '%s',
                                '%s',
                                '%s',
                                '%d',
                                '%d',
                                '%d',
                                '%s',
                                '%s',
                            )
                        );
                        if ($transaction_insert) {
                            ?>
                            <p style="font-size:18px;color:#37607D;width:100%;text-align:center;padding:30px 0;">You are
                                being redirected to the bank platform...</p>
                        <input type="hidden" name="order_red_1"
                               value="<?php echo '/order-form?signature=' . $signature; ?>">
                            <script type="text/javascript">
                                var redirect_to = jQuery('input[name="order_red_1"]').val();
                                window.location.replace(redirect_to);
                            </script>
                        <?php
                        exit;
                        } else {
                        ?>
                        <input type="hidden" name="order_red_2" value="/buy-bids?msg=sig_error">
                            <script type="text/javascript">
                                var redirect_to = jQuery('input[name="order_red_2"]').val();
                                window.location.replace(redirect_to);
                            </script>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- page content here -->
    </div>
    <?php
}

?>
