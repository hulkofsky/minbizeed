<?php
/**
 * Custom posts
 *
 * @package  MinBizeed
 * @company  Triangle Mena <http://trianglemena.com>
 * @developer  Maroun Melhem <http://maroun.me>
 *
 */
add_action('init', 'minbizeed_create_post_type');
function minbizeed_create_post_type()
{
    $icn = get_bloginfo('template_url') . "/images/dashboard_icons/mbz-a.png";
    register_post_type('auction', array(
            'labels' => array(
                'name' => __('Auctions', 'minbizeed'),
                'singular_name' => __('Auction', 'minbizeed'),
                'add_new' => __('Add New Auction', 'minbizeed'),
                'new_item' => __('New Auction', 'minbizeed'),
                'edit_item' => __('Edit Auction', 'minbizeed'),
                'add_new_item' => __('Add New Auction', 'minbizeed'),
                'search_items' => __('Search Auctions', 'minbizeed'),
            ),
            'public' => true,
            'menu_position' => 5,
            'register_meta_box_cb' => 'minbizeed_set_metaboxes',
            'has_archive' => "auction-list",
            'rewrite' => true, // array('slug'=>"auctions/%auction_cat%",'with_front'=>true),
            'supports' => array(
                'title',
                'editor',
                'thumbnail',
            ),
            '_builtin' => false,
            'menu_icon' => $icn,
            'publicly_queryable' => true,
            'hierarchical' => false,
            'taxonomies' => array('category'),
        )
    );
    flush_rewrite_rules();
}


add_action('save_post', 'minbizeed_save_custom_fields');
function minbizeed_save_custom_fields($pid)
{

    if (isset($_POST['fromadmin'])) {

        update_post_meta($pid, "minimum_price", trim($_POST['minimum_price']));
        update_post_meta($pid, "time_increase", $_POST['time_increase']);
        update_post_meta($pid, "price_increase", $_POST['price_increase']);

        update_post_meta($pid, "ending", strtotime(trim($_POST['ending']), current_time('timestamp', 0)));

        update_post_meta($pid, "start_price", minbizeed_clear_sums_of_cash($_POST['start_price']));

        if ($_POST['closed'] == '1') {
            update_post_meta($pid, "closed", '1');
        } else {
            update_post_meta($pid, "closed", '0');
        }

        $ggcbd = get_post_meta($pid, "current_bid", true);

        if (empty($ggcbd)) {
            update_post_meta($pid, "current_bid", $_POST['start_price']);
        }
    }
}

//Set Default Meta Value
function set_default_meta_new_post($post_ID)
{
    $winner_field_value = get_post_meta($post_ID, 'winner', true);
    $winner_default_meta = '0';
    if ($winner_field_value == '' && !wp_is_post_revision($post_ID)) {
        add_post_meta($post_ID, 'winner', $winner_default_meta, true);
    }

    $winner_paid_field_value = get_post_meta($post_ID, 'winner_paid', true);
    $winner_paid_default_meta = '0';
    if ($winner_paid_field_value == '' && !wp_is_post_revision($post_ID)) {
        add_post_meta($post_ID, 'winner_paid', $winner_paid_default_meta, true);
    }

    $closed_date_field_value = get_post_meta($post_ID, 'closed_date', true);
    $closed_date_default_meta = '0';
    if ($closed_date_field_value == '' && !wp_is_post_revision($post_ID)) {
        add_post_meta($post_ID, 'closed_date', $closed_date_default_meta, true);
    }

    $shipped_field_value = get_post_meta($post_ID, 'shipped', true);
    $shipped_default_meta = '0';
    if ($shipped_field_value == '' && !wp_is_post_revision($post_ID)) {
        add_post_meta($post_ID, 'shipped', $shipped_default_meta, true);
    }

    $shipped_date_field_value = get_post_meta($post_ID, 'shipped_on', true);
    $shipped_date_default_meta = '0';
    if ($shipped_date_field_value == '' && !wp_is_post_revision($post_ID)) {
        add_post_meta($post_ID, 'shipped_on', $shipped_date_default_meta, true);
    }
    return $post_ID;
}

add_action('wp_insert_post', 'set_default_meta_new_post');


add_action('query_vars', 'minbizeed_add_query_vars');
function minbizeed_add_query_vars($public_query_vars)
{
    $public_query_vars[] = 'a_action';
    $public_query_vars[] = 'bid_id';
    $public_query_vars[] = 'step';
    $public_query_vars[] = 'my_second_page';
    $public_query_vars[] = 'third_page';
    $public_query_vars[] = 'username';
    $public_query_vars[] = 'pid';
    $public_query_vars[] = 'term_search';
    $public_query_vars[] = 'method';
    $public_query_vars[] = 'post_author';
    $public_query_vars[] = 'page';
    $public_query_vars[] = 'rid';

    return $public_query_vars;
}

function minbizeed_update_credits($uid, $am)
{
    update_user_meta($uid, 'user_credits', $am);
}

add_filter("manage_edit-auction_columns", "minbizeed_my_auctions_columns");
function minbizeed_my_auctions_columns($columns)
{ //this function display the columns headings
    $columns["cb"] = "<input type=\"checkbox\" />";
    $columns["title"] = __("Auction Title", "minbizeed");
    $columns["author"] = __("Author", "minbizeed");
    $columns["posted"] = __("Posted On", "minbizeed");
    $columns["price"] = __("Price", "minbizeed");
    $columns["exp"] = __("Expires in", "minbizeed");
    $columns["closed"] = __("Closed", "minbizeed");
    $columns["options"] = __("Options", "minbizeed");
    return $columns;
}

add_action("manage_posts_custom_column", "minbizeed_my_custom_columns");
function minbizeed_my_custom_columns($column)
{
    global $post;
    if ("ID" == $column)
        echo $post->ID; //displays title
    elseif ("description" == $column)
        echo $post->ID; //displays the content excerpt
    elseif ("posted" == $column)
        echo date('jS \of F, Y \<\b\r\/\>H:i:s', strtotime($post->post_date)); //displays the content excerpt
    elseif ("author" == $column) {
        echo $post->post_author;
    } elseif ("closed" == $column) {
        $f = get_post_meta($post->ID, 'closed', true);
        if ($f == "1")
            echo __("Yes", "minbizeed");
        else
            echo __("No", "minbizeed");
    } elseif ("price" == $column) {
        echo minbizeed_get_show_price(get_post_meta($post->ID, 'current_bid', true));
    } elseif ("exp" == $column) {
        $ending = get_post_meta($post->ID, 'ending', true);
        echo minbizeed_prepare_seconds_to_words($ending - current_time('timestamp', 0));
    } elseif ("options" == $column) {
        echo '<div style="padding-top:20px">';
        echo '<a class="awesome" href="' . get_bloginfo('siteurl') . '/wp-admin/post.php?post=' . $post->ID . '&action=edit">Edit</a> ';
        echo '<a class="awesome" href="' . get_permalink($post->ID) . '" target="_blank">View</a> ';
        echo '<a class="trash" href="' . get_delete_post_link($post->ID) . '">Trash</a> ';
        echo '</div>';
    }
}

function minbizeed_set_metaboxes()
{
//    add_meta_box('penny_bids', 'Auction Bids', 'minbizeed_theme_penny_bids', 'auction', 'advanced', 'high');
    add_meta_box('winner', 'Winner', 'minbizeed_winner', 'auction', 'advanced', 'high');
    add_meta_box('users_bids', 'Users Bids', 'minbizeed_users_bids', 'auction', 'advanced', 'high');
    add_meta_box('auction_dets', 'Bidding info', 'minbizeed_theme_auction_dts', 'auction', 'side', 'high');
}


function minbizeed_theme_auction_dts()
{
    global $post;
    $pid = $post->ID;
    $t = get_post_meta($pid, "closed", true);
    $d = get_post_meta($pid, 'ending', true);
    $s_price = get_post_meta($pid, 'start_price', true);
    $b_increase = get_post_meta($pid, 'price_increase', true);
    $time_increase = get_post_meta($pid, 'time_increase', true);
    ?>

    <div id="post-new4">
        <input name="fromadmin" type="hidden" value="1a"/>

        <div class="form-group">
            <label>Minimum Price* ($)</label>
            <input id="minimum_price" required="required" name="minimum_price" size="10" class="form-control do_input"
                   type="text"
                   value="<?php echo get_post_meta($pid, 'minimum_price', true); ?>"/>
            <p class="info">
                You can use the <a class="min_price_init" href="#min_price_popup">Min Price Calculator</a>
                to calculate it
            </p>
        </div>

        <div class="form-group">
            <label>Start Price* ($)</label>
            <input id="start_price" required="required" name="start_price" size="10" class="form-control do_input"
                   type="text"
                   value="<?php
                   if ($s_price) {
                       echo $s_price;
                   } else {
                       echo 0;
                   }
                   ?>"/>
            <p class="info">
                This is the auction's starting price <b>Default: 0</b>
            </p>
        </div>

        <div class="form-group">
            <label>Bid Increase Price* ($)</label>
            <input id="price_increase" required="required" name="price_increase" size="10" class="form-control do_input"
                   type="text"
                   value="<?php
                   if ($b_increase) {
                       echo $b_increase;
                   } else {
                       echo 0.01;
                   }
                   ?>"/>
            <p class="info">
                This is how much each bid raises the auction price <b>Default: 0.01</b>
            </p>
        </div>

        <div class="form-group">
            <label>Bid Multiply* (x<?php echo $time_increase; ?>)</label>
            <input id="time_increase" required="required" name="time_increase" size="5" class="form-control do_input"
                   type="text"
                   value="<?php echo $time_increase; ?>"/>
            <p class="info">
                How much bids each action will cost the user
            </p>
        </div>

        <div class="form-group">
            <input id="closed" class="form-control" disabled="disabled" type="checkbox" value="1"
                   name="closed" <?php if ($t == '1') echo ' checked="checked" '; ?> />
            <label id="closed_label">Closed</label>
            <p class="info">
                This should be automatically updated
            </p>
        </div>

        <div class="form-group">
            <label>Auction Ending On*</label>
            <input id="ending" required="required" name="ending" class="form-control do_input" type="text"
                   value="<?php
                   if (!empty($d)) {
                       //$r = date('m/d/Y H:i', $d);
                       $r = date('F d,Y H:i', $d);
                       echo $r;
                   }
                   ?>"/>
            <p class="info">
                The auction time is GMT, please adjust hours according to your local time
            </p>
        </div>

        <div class="min_price_popup white-popup-block mfp-hide" id="min_price_popup">
            <div class="admin_stats">
                <h3>
                    Minimum price calculator
                </h3>
                <div class="panel-body">
                    <div class="calculator">
                        <p>
                            <label>Retail Price: </label>
                            <input name="retail_price" type="number"/>
                        </p>
                        <p>
                            <label>Constant: 0.41</label>
                        </p>
                        <p class="min_price">
                            <label>Suggested Minimum Price: <span class="min_setter"></span></label>
                        </p>

                        <p class="div_by">
                            <label>Bid multiplier: </label>
                            <input type="number" name="div_by"/>
                        </p>

                        <p class="fin_price">
                            <label>Final Minimum Price: <span class="fin_setter"></span></label>
                        </p>
                    </div>
                    <div class="results">
                        <p class="r_e error_check"></p>

                        <p class="r_s error_check"></p>

                        <p>
                            <a href="#" id="calc" class="calc btn btn-primary">Calculate</a>
                            <a href="#" id="calc_2" class="calc btn btn-primary">Calculate</a>
                            <a href="#" id="reset" class="calc btn btn-danger">Reset</a>
                            <a href="#" id="set" class="calc btn btn-success">Set</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>


        <script type="text/javascript">
            jQuery(document).ready(function () {
                jQuery('#ending').datetimepicker({
                    showSecond: false
                });

                jQuery('.min_price_init').magnificPopup({
                    type: 'inline',
                    closeOnBgClick: true,
                    closeMarkup: ''
                });

                jQuery(".min_price_popup #calc").click(function (e) {
                    e.preventDefault();
                    var retail_price = jQuery('.min_price_popup input[name="retail_price"]').val();
                    if (retail_price > 0) {
                        var clicks = retail_price / 0.41;
                        var min_price = clicks * 0.01;
                        min_price = min_price.toFixed(2);
                        jQuery('.min_price_popup .r_e').slideUp();
                        jQuery('.min_price_popup .min_setter').html('');
                        jQuery('.min_price_popup .min_setter').attr('id', min_price);
                        jQuery('.min_price_popup .min_setter').prepend(min_price + " $");
                        jQuery('.min_price_popup #calc').hide();
                        jQuery('.min_price_popup .min_price,.min_price_popup .div_by,.min_price_popup #calc_2,.min_price_popup #reset').slideDown();
                    } else {
                        jQuery('.min_price_popup .error_check').html('');
                        jQuery('.min_price_popup .r_e').html('Please add a positive retail price').hide();
                        jQuery('.min_price_popup .r_e').slideDown();
                    }
                });
                jQuery(".min_price_popup #calc_2").click(function (e) {
                    e.preventDefault();
                    var div_by = jQuery('.min_price_popup input[name="div_by"]').val();
                    var min_price = jQuery('.min_price_popup .min_setter').attr('id');
                    if (div_by > 0 && min_price > 0) {
                        var f_price = min_price / div_by;
                        f_price = f_price.toFixed(2);
                        jQuery('.min_price_popup .r_e').slideUp();
                        jQuery('.min_price_popup .fin_setter').html('');
                        jQuery('.min_price_popup .fin_setter').prepend(f_price + " $");
                        jQuery('.min_price_popup #calc_2').hide();
                        jQuery('.min_price_popup #set').attr('data-fin', f_price);
                        jQuery('.min_price_popup #set').attr('data-ti', div_by);
                        jQuery('.min_price_popup .fin_price,.min_price_popup #set').slideDown();
                    } else {
                        jQuery('.min_price_popup .error_check').html('');
                        jQuery('.min_price_popup .r_e').html('Please add a positive divider').hide();
                        jQuery('.min_price_popup .r_e').slideDown();
                    }
                });
                jQuery(".min_price_popup #reset").click(function (e) {
                    e.preventDefault();
                    jQuery('.min_price_popup input[name="retail_price"]').val("");
                    jQuery('.min_price_popup input[name="div_by"]').val("");
                    jQuery('.min_price_popup .min_price,.min_price_popup .div_by,.min_price_popup .fin_price,.min_price_popup #calc_2').fadeOut('fast');
                    jQuery('.min_price_popup #calc').fadeIn('fast');
                });

                jQuery(".min_price_popup #set").click(function (e) {
                    e.preventDefault();
                    var fp = jQuery(this).attr('data-fin');
                    var ti = jQuery(this).attr('data-ti');
                    if (fp) {
                        jQuery('#minimum_price').val(fp);
                        jQuery('#time_increase').val(ti);
                        jQuery.magnificPopup.close();
                    } else {
                        alert('An error occured, please refresh and try again');
                    }
                });

            });
        </script>
        <style type="text/css">

            .min_price_popup .admin_stats {
                width: 100%;
                max-width: 500px;
                display: block;
                margin: 0 auto;
            }

            #closed_label {
                margin: 3px 0 0 3px;
            }

            .info {
                color: grey;
                font-size: 11px;
                margin: 5px 0;
                font-style: italic;
            }

            .min_price_popup #reset, .min_price_popup #set {
                display: none;
            }

            .min_price_popup .min_price, .min_price_popup .div_by, .min_price_popup #calc_2, .min_price_popup .fin_price {
                display: none;
            }

            .min_price_popup .min_price .min_setter, .min_price_popup .fin_price .fin_setter {
                color: green;
                font-size: 14px;
            }

            .min_price_popup .r_e {
                color: red;
            }

            .min_price_popup .r_s {
                color: green;
            }

            .ui-timepicker-div .ui-widget-header {
                margin-bottom: 8px;
            }

            .ui-timepicker-div dl {
                text-align: left;
            }

            .ui-timepicker-div dl dt {
                height: 25px;
                margin-bottom: -25px;
            }

            .ui-timepicker-div dl dd {
                margin: 0 10px 10px 65px;
            }

            .ui-timepicker-div td {
                font-size: 90%;
            }

            .ui-tpicker-grid-label {
                background: none;
                border: none;
                margin: 0;
                padding: 0;
            }

            .ui-timepicker-rtl {
                direction: rtl;
            }

            .ui-timepicker-rtl dl {
                text-align: right;
            }

            .ui-timepicker-rtl dl dd {
                margin: 0 65px 10px 10px;
            }

        </style>
    </div>
    <?php
}