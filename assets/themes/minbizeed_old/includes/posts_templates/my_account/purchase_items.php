<?php

function minbizeed_display_my_account_purchase_items_fncs()
{
    ob_start();
    ?>
    <div class="main-container col-md-10" role="main">
        <?php
        ob_start();
        global $current_user;
        get_currentuserinfo();
        $user_id = $current_user->ID;

        $pid = strip_tags($_GET['pid']);
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
                                <input type="hidden" name="order_red_3"
                                       value="/my-account/trophy-room/?msg=sig_error">
                                <script type="text/javascript">
                                    var redirect_to = jQuery('input[name="order_red_3"]').val();
                                    window.location.replace(redirect_to);
                                </script>
                                <?php
                            }
                            global $wpdb;
                            $s = "select * from " . $wpdb->prefix . "penny_bids where `pid`='$pid' and `winner`=1 and `uid`=$user_id and `paid`='0'";
                            $r = $wpdb->get_results($s);
                            if ($r) {
                                foreach ($r as $row) {
                                    $amount = $row->bid;
                                }
                                $timestamp = time();
                                $vpc_MerchTxnRef = $user_id . "_item_" . $timestamp;
                                $auction_name = get_the_title($pid);
                                $vpc_OrderInfo = "Auction=$auction_name";
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
                                        'auction_id' => "$pid",
                                        'user_id' => "$user_id",
                                        'user_ip' => "$user_ip",
                                        'date_time' => "$date_time",
                                    ),
                                    array(
                                        '%s',
                                        '%s',
                                        '%s',
                                        '%s',
                                        '%d',
                                        '%d',
                                        '%s',
                                        '%s',
                                    )
                                );
                            } else {
                                ?>
                                <input type="hidden" name="order_red_0" value="/my-account/trophy-room/?msg=sig_error">
                                <script type="text/javascript">
                                    var redirect_to = jQuery('input[name="order_red_0"]').val();
                                    window.location.replace(redirect_to);
                                </script>
                                <?php
                            }
                            if ($transaction_insert) {
                                ?>
                                <p style="font-size:18px;color:#37607D;width:100%;text-align:center;padding:30px 0;">You
                                    are being redirected to the bank platform...</p>
                                <!--<a href="/item-order-form/?signature=--><?php //echo $signature;
                            ?><!--" class="green_btn2">-->
                                <!--                        <div class='payment_div col-lg-5'>-->
                                <!--                            <img class='img-responsive payment_img' src='--><?php //echo get_template_directory_uri() . '/newimages/bank.png'
                            ?><!--'/>-->
                                <!--                        </div>-->
                                <!--                    </a>-->
                            <?php
                            ?>
                            <input type="hidden" name="order_red_1"
                                   value="<?php echo '/item-order-form?signature=' . $signature; ?>">
                                <script type="text/javascript">
                                    var redirect_to = jQuery('input[name="order_red_1"]').val();
                                    window.location.replace(redirect_to);
                                </script>
                            <?php
                            } else {
                            ?>
                            <input type="hidden" name="order_red_2"
                                   value="/my-account/trophy-room/?msg=sig_error">
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
