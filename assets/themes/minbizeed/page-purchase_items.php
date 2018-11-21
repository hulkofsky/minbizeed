<?php
/*
 * Template Name: MBZ Purchase Items
 */

get_header();
if (!is_user_logged_in()) {
    wp_redirect('/');
    exit;
}
?>
    <div class="live_auctions_page closed_auctions_page">
        <div class="container">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 page_identifier">
                <h1 id="threeDotsType">Preparing payment</h1>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 all_auctions_wrapper">
                <?php
                $current_user = wp_get_current_user();
                $user_id = $current_user->ID;

                $pid = strip_tags($_GET['pid']);
                $error_msg = strip_tags($_GET['msg']);
                if ($error_msg == "sig_error") {
                    ?>
                    <input type="hidden" name="order_red_3"
                           value="/profil/e#trophy_room?msg=sig_error">
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
                        'mb_272727023023_migs_transactions_data',
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
        <div class="page_overlay"></div>
    </div>
<?php
get_footer();
