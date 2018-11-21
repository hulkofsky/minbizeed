<?php
/*
 * Send emails to users when bidding on auctions ending in 15 mins
 * */
$received_user_id=$_POST['user_id'];
$received_auction_id=$_POST['auction_id'];
tbid_custom_auction_reminder_user($received_user_id,$received_auction_id);