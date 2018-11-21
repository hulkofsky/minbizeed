<?php
/*
 * Template: Purchase Bids
 * Template Name: V20 Purchase Bids
 */

if (!is_user_logged_in()) {
    wp_redirect('/');
    exit;
}
get_header();
?>
    <div class="buy_bids_page">
        <div class="page_identifier">
            <h2>Preparing payment...</h2>
        </div>

        <?php
        if ($_GET['msg'] == "sig_error") {
            ?>
            <p class="wppb-error no_margins">
                Something went wrong before sending the payment to the bank, please try purchasing the package again.
            </p>
            <?php
        }
        ?>

        <?php echo minbizeed_display_my_account_purchase_bids_fncs(); ?>
        <div class="page_overlay"></div>
    </div>
<?php
get_footer();
