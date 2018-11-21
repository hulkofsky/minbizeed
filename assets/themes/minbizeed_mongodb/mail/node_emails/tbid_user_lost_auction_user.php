<?php
/*
 * Send emails to users who lost bids in auctions
 * */
$received_user_id=$_POST['user_id'];
$received_auction_name=$_POST['auction_name'];
tbid_user_lost_auction_user($received_user_id,$received_auction_name);