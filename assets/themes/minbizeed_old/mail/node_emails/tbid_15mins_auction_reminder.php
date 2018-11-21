<?php
/*
 * Send emails to users when bidding on auctions ending in 15 mins
 * */
$received_user_id=$_POST['user_id'];
$received_auction_name=$_POST['auction_name'];
$received_auction_link=$_POST['auction_link'];
tbid_15mins_auction_reminder_user($received_user_id,$received_auction_name,$received_auction_link);