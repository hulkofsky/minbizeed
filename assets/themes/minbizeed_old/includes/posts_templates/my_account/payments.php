<?php
function minbizeed_display_my_account_payments_fncs()
{

    ob_start();


    global $current_user;
    get_currentuserinfo();
    $uid = $current_user->ID;
    global $wpdb;
    ?>


    <div id="content">
        <!-- page content here -->


        <div class="my_box3">
            <div class="padd10">

                <div class="box_title"><?php _e("Payments", "minbizeed"); ?></div>
                <div class="box_content">
                    <div class="padd10">


                        <?php
                        $bal = minbizeed_get_credits($uid);
                        echo '<span class="balance">' . sprintf(__("Your Current Balance is: %s credits", "minbizeed"), $bal) . "</span>";
                        ?>


                    </div>
                </div>
            </div>
        </div>

        <div class="clear10"></div>

        <div class="my_box3">
            <div class="padd10">

                <div class="box_title"><?php _e('Buy Bids', 'minbizeed'); ?></div>
                <div class="box_content">
                    <div class="padd10">

                        <?php
                        global $wpdb;
                        $s = "select * from " . $wpdb->prefix . "penny_packages order by cost asc";
                        $r = $wpdb->get_results($s);

                        if (count($r) == 0)
                            _e('There are no bid packages defined.', 'minbizeed');
                        else {
                            ?>

                            <div class="package_mellon">

                                <div class="pk_name"><b><?php _e('Package Name', 'minbizeed'); ?></b></div>
                                <div class="pk_bids"><b><?php _e('Bids Number', 'minbizeed'); ?></b></div>
                                <div class="pk_cost"><b><?php _e('Cost', 'minbizeed'); ?></b></div>
                                <div class="pk_purchase">&nbsp;</div>

                            </div>


                            <?php
                            foreach ($r as $row) {
                                ?>

                                <div class="package_mellon">

                                    <div class="pk_name"><?php echo $row->package_name; ?></div>
                                    <div class="pk_bids"><?php echo $row->bids; ?></div>
                                    <div class="pk_cost"><?php echo minbizeed_get_show_price($row->cost); ?></div>
                                    <div class="pk_purchase"><a href="<?php
                                        $perm = minbizeed_using_permalinks();
                                        if ($perm == true) {
                                            echo get_permalink(get_option('minbizeed_my_account_purchase_bid_page_id')) . "?bid_id=" . $row->id;
                                        } else {
                                            echo get_bloginfo('siteurl') . '/?page_id=' . get_option('minbizeed_my_account_purchase_bid_page_id') . "&bid_id=" . $row->id;
                                        }
                                        ?>" class="green_btn2"><?php _e('Purchase Now', 'minbizeed'); ?></a></div>

                                </div>


                                <?php
                            }
                        }
                        ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    echo minbizeed_get_users_links();

    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}

?>