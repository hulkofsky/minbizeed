<?php
/**
 * Winner bids
 *
 * @package  MinBizeed
 * @company  Triangle Mena <http://trianglemena.com>
 * @developer  Maroun Melhem <http://maroun.me>
 *
 */
function minbizeed_winner()
{
    global $post;
    $pid = $post->ID;


    $closed = get_post_meta($pid, 'closed', true);
    $time_increase = get_post_meta($pid, 'time_increase', true);
    $winner_id = get_post_meta($pid, 'winner', true);

    $post = get_post($pid);
    global $wpdb;

    if ($closed==1 && $winner_id) {
        ?>
        <table width="100%">
            <thead>
            <tr>
                <th>Winner</th>
                <th>Total Clicks</th>
                <th>Bid Multiplier</th>
                <th>Total Bids</th>
                <th>Spent Amount</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <?php
                $user_winner = get_userdata($winner_id);
                $total_bids_winner = "SELECT * from " . $wpdb->prefix . "penny_bids where pid='$pid' AND uid=$winner_id";
                $total_bids_winner_res = $wpdb->get_results($total_bids_winner);
                $total_bids_res_winner_count = count($total_bids_winner_res);
                $total_bids_winner = $total_bids_res_winner_count * $time_increase;
                $spent_amount_winner = $total_bids_winner * 0.416 . " $";

                echo '<th>' . $user_winner->user_login . '</th>';
                echo '<th>' . $total_bids_res_winner_count . '</th>';
                echo '<th>' . $time_increase . '</th>';
                echo '<th>' . $total_bids_winner . '</th>';
                echo '<th>' . $spent_amount_winner . '</th>';
                ?>
            </tr>
            </tbody>
        </table>
        <?php
    } else
        _e("No Winner yet.");
}