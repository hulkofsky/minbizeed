<?php
/**
 * Code functions
 *
 * @package  MinBizeed
 * @company  Triangle Mena <http://trianglemena.com>
 * @developer  Maroun Melhem <http://maroun.me>
 *
 */
function minbizeed_get_custom_taxonomy_count($ptype, $pterm)
{
    global $wpdb;

    $s = "select * from " . $wpdb->prefix . "terms where slug='$pterm'";
    $r = $wpdb->get_results($s);
    $r = $r[0];

    $term_id = $r->term_id;


//--------

    $s = "select * from " . $wpdb->prefix . "term_taxonomy where term_id='$term_id'";
    $r = $wpdb->get_results($s);
    $r = $r[0];

    $term_taxonomy_id = $r->term_taxonomy_id;


//--------

    $s = "select distinct posts.ID from " . $wpdb->prefix . "term_relationships rel, $wpdb->postmeta wpostmeta, $wpdb->posts posts
	 where rel.term_taxonomy_id='$term_taxonomy_id' AND rel.object_id = wpostmeta.post_id AND posts.ID = wpostmeta.post_id AND posts.post_status = 'publish' AND posts.post_type = 'auction' AND wpostmeta.meta_key = 'closed' AND wpostmeta.meta_value = '0'";
    $r = $wpdb->get_results($s);


    return count($r);
}

function minbizeed_replace_stuff_for_me($find, $replace, $subject)
{
    $i = 0;
    foreach ($find as $item) {
        $replace_with = $replace[$i];
        $subject = str_replace($item, $replace_with, $subject);
        $i++;
    }

    return $subject;
}

function minbizeed_get_total_nr_of_auction()
{
    $query = new WP_Query("post_type=auction&order=DESC&orderby=id&posts_per_page=-1&paged=1");
    return $query->post_count;
}

function minbizeed_get_total_nr_of_open_auction()
{
    $query = new WP_Query("meta_key=closed&meta_value=0&post_type=auction&order=DESC&orderby=id&posts_per_page=-1&paged=1");
    return $query->post_count;
}

function minbizeed_get_total_nr_of_closed_auction()
{
    $query = new WP_Query("meta_key=closed&meta_value=1&post_type=auction&order=DESC&orderby=id&posts_per_page=-1&paged=1");
    return $query->post_count;
}

function minbizeed_get_option_drop_down($arr, $name)
{
    $opts = get_option($name);
    $r = '<select name="' . $name . '">';
    foreach ($arr as $key => $value) {
        $r .= '<option value="' . $key . '" ' . ($opts == $key ? ' selected="selected" ' : "") . '>' . $value . '</option>';
    }
    return $r . '</select>';
}

add_action('wp_ajax_nopriv_get_credits_act', 'minbizeed_get_credits_act');
add_action('wp_ajax_get_credits_act', 'minbizeed_get_credits_act');
function minbizeed_get_credits_act()
{
    $pidipid = $_POST['pidipid'];

    global $wpdb;
    $current_user=wp_get_current_user();
    $uid = $current_user->ID;

    if ($pidipid != 0) {
        $sk = "select * from " . $wpdb->prefix . "penny_assistant where pid='$pidipid' And uid='$uid'";
        $r = $wpdb->get_results($sk);
        $rhm = $r[0];

        $arr['remleft'] = $rhm->credits_start - $rhm->credits_current;
    } else
        $arr['remleft'] = "";


    $arr['crds'] = minbizeed_get_credits($uid);
    echo json_encode($arr);
}

function minbizeed_get_highest_bid($pid)
{
    global $wpdb;
    //$s = "select bid, uid from " . $wpdb->prefix . "penny_bids where pid='$pid' order by bid desc limit 1";
    //$r = $wpdb->get_results($s);
    // MongoDB Changes
    $filter = ['pid'=>$pid];
    $options = ['sort' => ['bid' => -1],'limit'=>1];        
    $query=new MongoDB\Driver\Query($filter, $options);
    $s = $GLOBALS['mongodb_manager']->executeQuery('minbizeed.pennybids', $query);
    $r=$s->toArray();
    // MongoDB Changes

    if (count($r) == 0)
        return get_post_meta($pid, 'start_price', true);


    $r = $r[0];
    return $r->bid;
}

function minbizeed_get_highest_bid2($pid)
{
    global $wpdb;
    //$s = "select bid, uid from " . $wpdb->prefix . "penny_bids where pid='$pid' order by bid desc limit 1";
    //$r = $wpdb->get_results($s);
    // MongoDB Changes
    $filter = ['pid'=>$pid];
    $options = ['sort' => ['bid' => -1],'limit'=>1];        
    $query=new MongoDB\Driver\Query($filter, $options);
    $s = $GLOBALS['mongodb_manager']->executeQuery('minbizeed.pennybids', $query);
    $r=$s->toArray();
    // MongoDB Changes

    if (count($r) == 0)
        return get_post_meta($pid, 'start_price', true);


    $r = $r[0];
    return $r;
}

function minbizeed_get_highest_bid_owner($pid)
{
    global $wpdb;
    //$s = "select * from " . $wpdb->prefix . "penny_bids where pid='$pid' order by bid desc limit 1";
    //$r = $wpdb->get_results($s);
    // MongoDB Changes
    $filter = ['pid'=>$pid];
    $options = ['sort' => ['bid' => -1],'limit'=>1];        
    $query=new MongoDB\Driver\Query($filter, $options);
    $s = $GLOBALS['mongodb_manager']->executeQuery('minbizeed.pennybids', $query);
    $r=$s->toArray();
    // MongoDB Changes

    if (count($r) == 0)
        return false;

    $r = $r[0];
    return $r->uid;
}

function minbizeed_get_highest_bid_owner_obj($pid)
{
    global $wpdb;
   // $s = "select * from " . $wpdb->prefix . "penny_bids where pid='$pid' order by bid desc limit 1";
    //$r = $wpdb->get_results($s);
    // MongoDB Changes
    $filter = ['pid'=>$pid];
    $options = ['sort' => ['bid' => -1],'limit'=>1];        
    $query=new MongoDB\Driver\Query($filter, $options);
    $s = $GLOBALS['mongodb_manager']->executeQuery('minbizeed.pennybids', $query);
    $r=$s->toArray();
    // MongoDB Changes
    if (count($r) == 0)
        return false;

    $r = $r[0];
    return $r;
}

function minbizeed_get_credits($uid)
{
    $c = get_user_meta($uid, 'user_credits', true);
    if (empty($c)) {
        update_user_meta($uid, 'user_credits', "0");
        return 0;
    }

    return $c;
}

function minbizeed_prepare_seconds_to_words($seconds)
{
    $res = minbizeed_seconds_to_words_new($seconds);
    if ($res == "Expired")
        return __('Expired', 'minbizeed');

    if ($res[0] == 0)
        return sprintf(__("%s hours, %s min, %s sec", 'minbizeed'), $res[1], $res[2], $res[3]);
    if ($res[0] == 1) {

        $plural = $res[1] > 1 ? __('days', 'minbizeed') : __('day', 'minbizeed');
        return sprintf(__("%s %s, %s hours, %s min", 'minbizeed'), $res[1], $plural, $res[2], $res[3]);
    }
}

function minbizeed_seconds_to_words_new($seconds)
{
    if ($seconds < 0)
        return 'Expired';

    /*     * * number of days ** */
    $days = (int)($seconds / 86400);
    /*     * * if more than one day ** */
    $plural = $days > 1 ? 'days' : 'day';
    /*     * * number of hours ** */
    $hours = (int)(($seconds - ($days * 86400)) / 3600);
    /*     * * number of mins ** */
    $mins = (int)(($seconds - $days * 86400 - $hours * 3600) / 60);
    /*     * * number of seconds ** */
    $secs = (int)($seconds - ($days * 86400) - ($hours * 3600) - ($mins * 60));
    /*     * * return the string ** */
    if ($days == 0 || $days < 0) {
        $arr[0] = 0;
        $arr[1] = $hours;
        $arr[2] = $mins;
        $arr[3] = $secs;
        return $arr; //sprintf("%d hours, %d min, %d sec", $hours, $mins, $secs);
    } else {
        $arr[0] = 1;
        $arr[1] = $days;
        $arr[2] = $hours;
        $arr[3] = $mins;

        return $arr; //sprintf("%d $plural, %d hours, %d min", $days, $hours, $mins);
    }
}

function minbizeed_get_show_price($price, $cents = 2)
{
    $minbizeed_currency_position = get_option('minbizeed_currency_position');
    if ($minbizeed_currency_position == "front")
        return minbizeed_get_currency() . "" . minbizeed_formats($price, $cents);
    return minbizeed_formats($price, $cents) . "" . minbizeed_get_currency();
}

function minbizeed_formats_special($number, $cents = 1)
{ // cents: 0=never, 1=if needed, 2=always
    $dec_sep = '.';
    $tho_sep = ',';

//dec,thou

    if (is_numeric($number)) { // a number
        if (!$number) { // zero
            $money = ($cents == 2 ? '0' . $dec_sep . '00' : '0'); // output zero
        } else { // value
            if (floor($number) == $number) { // whole number
                $money = number_format($number, ($cents == 2 ? 2 : 0), $dec_sep, ''); // format
            } else { // cents
                $money = number_format(round($number, 2), ($cents == 0 ? 0 : 2), $dec_sep, ''); // format
            } // integer or decimal
        } // value
        return $money;
    } // numeric
}

function minbizeed_formats($number, $cents = 1)
{ // cents: 0=never, 1=if needed, 2=always
    $dec_sep = get_option('minbizeed_decimal_sum_separator');
    if (empty($dec_sep))
        $dec_sep = '.';

    $tho_sep = get_option('minbizeed_thousands_sum_separator');
    if (empty($tho_sep))
        $tho_sep = ',';

//dec,thou

    if (is_numeric($number)) { // a number
        if (!$number) { // zero
            $money = ($cents == 2 ? '0' . $dec_sep . '00' : '0'); // output zero
        } else { // value
            if (floor($number) == $number) { // whole number
                $money = number_format($number, ($cents == 2 ? 2 : 0), $dec_sep, $tho_sep); // format
            } else { // cents
                $money = number_format(round($number, 2), ($cents == 0 ? 0 : 2), $dec_sep, $tho_sep); // format
            } // integer or decimal
        } // value
        return $money;
    } // numeric
}

function minbizeed_get_currency()
{
    $c = trim(get_option('minbizeed_currency_symbol'));
    if (empty($c))
        return get_option('minbizeed_currency');
    return $c;
}


function minbizeed_currency()
{
    return minbizeed_get_currency();
}

function minbizeed_get_avatar($uid, $w = 25, $h = 25)
{
    $av = get_user_meta($uid, 'avatar', true);
    if (empty($av))
        return get_bloginfo('template_url') . "/images/noav.jpg";
    else
        return minbizeed_generate_thumb($av, $w, $h);
}

function minbizeed_clear_sums_of_cash($cash)
{
    $cash = str_replace(" ", "", $cash);
    $cash = str_replace(",", "", $cash);
//$cash = str_replace(".","",$cash);

    return strip_tags($cash);
}

function minbizeed_curPageURL()
{
    $pageURL = 'http';
    if ($_SERVER["HTTPS"] == "on") {
        $pageURL .= "s";
    }
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}

function minbizeed_get_post_images($pid, $limit = -1)
{

//---------------------
// build the exclude list
    $exclude = array();

    $args = array(
        'order' => 'ASC',
        'post_type' => 'attachment',
        'post_parent' => get_the_ID(),
        'meta_key' => 'another_reserved1',
        'meta_value' => '1',
        'numberposts' => -1,
        'post_status' => null,
    );
    $attachments = get_posts($args);
    if ($attachments) {
        foreach ($attachments as $attachment) {
            $url = $attachment->ID;
            array_push($exclude, $url);
        }
    }

//-----------------


    $arr = array();

    $args = array(
        'order' => 'ASC',
        'orderby' => 'post_date',
        'post_type' => 'attachment',
        'post_parent' => $pid,
        'exclude' => $exclude,
        'post_mime_type' => 'image',
        'numberposts' => $limit,
    );
    $i = 0;

    $attachments = get_posts($args);
    if ($attachments) {

        foreach ($attachments as $attachment) {

            $url = wp_get_attachment_url($attachment->ID);
            array_push($arr, $url);
        }
        return $arr;
    }
    return false;
}

function minbizeed_my_account_link()
{
    return get_permalink(get_option('minbizeed_my_account_page_id'));
}

function minbizeed_sm_replace_me($s)
{
    return urlencode($s);
}

function minbizeed_curPageURL_me()
{
    $pageURL = 'http';
    if ($_SERVER["HTTPS"] == "on") {
        $pageURL .= "s";
    }
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}

function minbizeed_using_permalinks()
{
    global $wp_rewrite;
    if ($wp_rewrite->using_permalinks())
        return true;
    else
        return false;
}

function minbizeed__curl_get_data($url)
{
    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}