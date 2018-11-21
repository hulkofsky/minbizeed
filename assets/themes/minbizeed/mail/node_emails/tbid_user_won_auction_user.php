<?php
/*
 * Send emails when user wins auction
 * */
$received_user_id=$_POST['user_id'];
$received_auction_name=$_POST['auction_name'];
tbid_user_won_auction_user($received_user_id,$received_auction_name);
tbid_user_won_auction_admin($received_user_id,$received_auction_name);