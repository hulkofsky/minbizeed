<?php
/*
 * Send emails when user bids return due to auction not being fullfilled
 * */
$received_user_id=$_POST['user_id'];
$received_auction_name=$_POST['auction_name'];
$received_returned_bids=$_POST['returned_bids'];
tbid_auction_not_fullfilled_user($received_user_id,$received_auction_name,$received_returned_bids);
//tbid_auction_not_fullfilled_admin($received_user_id,$received_auction_name,$received_returned_bids);