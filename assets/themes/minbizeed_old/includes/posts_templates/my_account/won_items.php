<?php
function minbizeed_display_my_account_won_itms_fncs() {

    ob_start();

    global $current_user;
    get_currentuserinfo();
    $uid = $current_user->ID;
    ?>

    <div id="content">
        <!-- page content here -->

        <div class="my_box3">
            <div class="padd10">

                <div class="box_title"><?php _e("Auctions I Won", 'minbizeed');?></div>
                <div class="box_content">


    <?php
    global $current_user;
    get_currentuserinfo();
    $uid = $current_user->ID;


    global $wp_query;
    $query_vars = $wp_query->query_vars;
    $post_per_page = 5;

    $winner = array(
            'key' => 'winner',
            'value' => $uid,
            'compare' => '='
    );

    /*
      $reverse = 	array(
      'key' => 'reverse',
      'value' => "no",
      'compare' => '='
      );
     */



    $args = array('post_type' => 'auction', 'order' => 'DESC', 'orderby' => 'date', 'posts_per_page' => $post_per_page,
            'pages' => $query_vars['paged'], 'meta_query' => array($winner));

    query_posts($args);

    if(have_posts()) :
        while(have_posts()) : the_post();
            minbizeed_get_post_my_account_won();
        endwhile;

        if(function_exists('wp_pagenavi')):
            wp_pagenavi();
        endif;

    else:

        _e("There are no auctions yet.", 'minbizeed');

    endif;

    wp_reset_query();
    ?>


                </div>
            </div>
        </div>

        <!-- page content here -->
    </div>


    <?php
    echo minbizeed_get_users_links();

    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}
?>