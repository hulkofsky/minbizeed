<?php
/*
 * Template: Purchase Bids
 * Template Name: V20 Purchase Items
 */

get_header();
if (!is_user_logged_in()) {
    wp_redirect('/');
    exit;
}
?>
    <div class="buy_bids_page">
        <div class="page_identifier">
            <h2 id="threeDotsType">Preparing payment</h2>
        </div>
        <?php echo minbizeed_display_my_account_purchase_items_fncs(); ?>
        <div class="page_overlay"></div>
    </div>
<?php
get_footer();
