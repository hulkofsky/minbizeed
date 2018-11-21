<?php
/*
 * Template: Unsubscribe
 * Template Name: V20 unsubscribe
 */
$md_email = strip_tags($_GET['md_email']);
$user = get_user_by('email', $md_email);
$user_id = $user->ID;
$is_unsub = get_user_meta($user_id, 'user_unsub');
if ($md_email == "") {
    wp_redirect('/');
    exit;
}
get_header();
?>
    <div class="buy_bids_page">
        <?php
        if ($is_unsub[0]) {
            $uns = 1;
            update_user_meta($user_id, 'user_unsub', $uns);
            ?>
            <div class="error_cont unsub">
                <div class="page_identifier">
                    <h2>We're sorry for the inconvenience <i class="fa fa-frown-o" aria-hidden="true"></i></h2>
                </div>
                <div class="page_identifier_sub">
                    <h3>You have successfully unsubscribed from our mailing list.</h3>
                    <h3>Please <a href="/contact-us">Contact</a> us if you changed your mind!</h3>
                </div>
            </div>
            <?php
        } else {
            ?>
            <div class="error_cont unsub">
                <div class="page_identifier">
                    <h2>You are already unsubscribed! <i class="fa fa-smile-o" aria-hidden="true"></i></h2>
                </div>
                <div class="page_identifier_sub">
                    <h3>Still sure? <a href="/contact-us">Contact</a> us here</h3>
                </div>
            </div>
            <?php
        }
        ?>
        <div class="clear"></div>
        <div class="page_overlay"></div>
    </div>
<?php
get_footer();

