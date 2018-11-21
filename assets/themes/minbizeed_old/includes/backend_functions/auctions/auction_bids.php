<?php
/**
 * Auction bids
 *
 * @package  MinBizeed
 * @company  Triangle Mena <http://trianglemena.com>
 * @developer  Maroun Melhem <http://maroun.me>
 *
 */
function minbizeed_theme_penny_bids()
{
    global $post;
    $pid = $post->ID;

    $closed = get_post_meta($pid, 'closed', true);
    $post = get_post($pid);
    global $wpdb;

    $bids = "select * from " . $wpdb->prefix . "penny_bids where pid='$pid' order by id DESC";
    $res = $wpdb->get_results($bids);

    if (count($res) > 0) {


        echo '<table width="100%">';
        echo '<thead><tr>';
        echo '<th>' . __('Username', 'minbizeed') . '</th>';
        echo '<th>' . __('Bid Amount', 'minbizeed') . '</th>';
        echo '<th>' . __('Date Made', 'minbizeed') . '</th>';

        echo '<th>' . __('Winner', 'minbizeed') . '</th>';

        echo '</tr></thead><tbody>';


//-------------

        foreach ($res as $row) {


            $user = get_userdata($row->uid);
            echo '<tr>';
            echo '<th>' . $user->user_login . '</th>';
            echo '<th>' . minbizeed_get_show_price($row->bid) . '</th>';
            echo '<th>' . date("d-M-Y H:i:s", $row->date_made) . '</th>';


            if ($row->winner == 1)
                echo '<th>Yes</th>';
            else
                echo '<th>&nbsp;</th>';

            echo '</tr>';
        }

        echo '</tbody></table>';
    } else
        _e("No bids placed yet.");
}