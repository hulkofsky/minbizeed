<?php
/**
 * Users bids
 *
 * @package  MinBizeed
 * @company  Triangle Mena <http://trianglemena.com>
 * @developer  Maroun Melhem <http://maroun.me>
 *
 */
function minbizeed_users_bids()
{
    global $post;
    $pid = $post->ID;


    $closed = get_post_meta($pid, 'closed', true);
    $time_increase = get_post_meta($pid, 'time_increase', true);
    $winner_id = get_post_meta($pid, 'winner', true);

    $post = get_post($pid);
    global $wpdb;

    $bids = "SELECT * from " . $wpdb->prefix . "penny_bids where pid='$pid'";
    $res = $wpdb->get_results($bids);
   
    $listed_users = array();

    if (count($res) > 0) {

        echo '<table width="100%">';
        echo '<thead><tr>';
        echo '<th>' . __('Username', 'minbizeed') . '</th>';
        echo '<th>' . __('Total Clicks', 'minbizeed') . '</th>';
        echo '<th>' . __('Bid multiplier', 'minbizeed') . '</th>';
        echo '<th>' . __('Total Bids', 'minbizeed') . '</th>';
        echo '<th>' . __('Spent Amount', 'minbizeed') . '</th>';

        echo '</tr></thead><tbody>';

        foreach ($res as $row) {
            if (!in_array($row->uid, $listed_users)) {
                array_push($listed_users, $row->uid);
            }
        }

        foreach ($listed_users as $listed_user) {
            $user = get_userdata($listed_user);
            $total_bids = "SELECT * from " . $wpdb->prefix . "penny_bids where pid='$pid' AND uid=$listed_user";
            $total_bids_res = $wpdb->get_results($total_bids);
            $total_bids_res_count = count($total_bids_res);
            $total_bids = $total_bids_res_count * $time_increase;
            $spent_amount = "$ ".$total_bids * 0.416;

            echo '<tr>';
            echo '<th>' . $user->user_login . '</th>';
            echo '<th>' . $total_bids_res_count . '</th>';
            echo '<th>' . $time_increase . '</th>';
            echo '<th>' . $total_bids . '</th>';
            echo '<th>' . $spent_amount . '</th>';
            echo '</tr>';
        }

        echo '</tbody></table>';
    } else
        _e("No bids placed yet.");
}