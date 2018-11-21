<?php
function minbizeed_display_my_account_home_fncs() {

    ob_start();

    global $current_user;
    get_currentuserinfo();
    $uid = $current_user->ID;
    ?>


    <div id="content">
        <div class="my_box3">
            <div class="padd10">

                <div class="box_title"><?php _e("My Account Home", 'minbizeed');?></div>
                <div class="box_content">
    <?php
    $info = get_user_meta($uid, 'personal_info', true);
    if(empty($info))
        _e("No personal info defined.", 'minbizeed');
    else
        echo $info;
    ?>
                </div>
            </div>
        </div>

        <div class="clear10"></div>


        <div class="my_box3">
            <div class="padd10">

                <div class="box_title"><?php _e("Latest bids", 'minbizeed');?></div>
                <div class="box_content">


    <?php
    global $wp_query;
    $query_vars = $wp_query->query_vars;
    $post_per_page = 5;

    $closed = array(
            'key' => 'closed',
            'value' => "0",
            //'type' => 'numeric',
            'compare' => '='
    );

    $bidded_auction = array(
            'key' => 'bidded_auction',
            'value' => $uid,
            //'type' => 'numeric',
            'compare' => '='
    );



    $args = array('posts_per_page' => 5, 'paged' => 1, 'post_type' => 'auction',
            'order' => 'DESC', 'meta_query' => array($closed, $bidded_auction), 'orderby' => 'ID');
    $the_query = new WP_Query($args);



    if($the_query->have_posts()):
        while($the_query->have_posts()) : $the_query->the_post();

            minbizeed_get_post_big();


        endwhile;



    else:

        _e("There are no auctions yet.", 'minbizeed');

    endif;

    wp_reset_query();
    ?>


                </div>
            </div>
        </div>




    </div> <!-- end div content -->


    <?php
    echo minbizeed_get_users_links();

    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}
?>