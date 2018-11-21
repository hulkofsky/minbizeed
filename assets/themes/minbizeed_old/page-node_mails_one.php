<?php
/*
  Template Name: Mail functions Api
 */
get_header();
$token = "9kV95XxL5sPE8tfWtjYnUO27GG2017";
$received_token = strip_tags($_GET['token']);
$received_action = strip_tags($_GET['action']);
$received_user_id = strip_tags($_GET['user_id']);
$received_auction_id = strip_tags($_GET['auction_id']);
if ($token == $received_token) {
    if ($received_action == "user_won") {
        tbid_user_won_auction_user($received_user_id, $received_auction_id);
        tbid_user_won_auction_admin($received_user_id, $received_auction_id);
    } elseif ($received_action == "user_lost") {
        $received_winner_id= strip_tags($_GET['winner_id']);
        tbid_user_lost_auction_user($received_user_id, $received_auction_id, $received_winner_id);
    } elseif ($received_action == "auction_not_fullfilled") {
        $received_returned_bids = strip_tags($_GET['returned_bids']);
        tbid_auction_not_fullfilled_user($received_user_id, $received_auction_id, $received_returned_bids);
//        tbid_auction_not_fullfilled_admin($received_user_id, $received_auction_id, $received_returned_bids);
    } elseif ($received_action == "auction_reminder") {
        tbid_15mins_auction_reminder_user($received_user_id, $received_auction_id);
    } elseif ($received_action == "custom_auction_reminder") {
        tbid_custom_auction_reminder_user($received_user_id, $received_auction_id);
    }
}
get_footer();
