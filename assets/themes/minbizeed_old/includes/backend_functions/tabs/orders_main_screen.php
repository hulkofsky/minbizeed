<?php
function minbizeed_orders_main_screen()
{


    ?>
    <div class="admin_stats container">
        <h3>
            Orders
        </h3>
        <ul class="nav nav-tabs items_menu">
            <li class="active">
                <a href="#tabs1" data-toggle="tab"> Not Paid Orders </a>
            </li>
            <li>
                <a href="#tabs2" data-toggle="tab"> Paid & Not Shipped Orders </a>
            </li>
            <li>
                <a href="#tabs3" data-toggle="tab"> Paid & Shipped Orders </a>
            </li>
        </ul>

        <div class="tab-content">

            <div id="tabs1" class="tab-pane fade in active">

                <?php
                global $current_user;
                get_currentuserinfo();
                $uid = $current_user->ID;

                global $wp_query;
                $query_vars = $wp_query->query_vars;
                $nrpostsPage = 8;

                $page = $_GET['pj'];
                if (empty($page))
                    $page = 1;

                //---------------------------------


                global $wpdb;
                $querystr2 = "
					SELECT distinct wposts.ID , bids.id bid_id, bids.uid winner_id, bids.bid bid, bids.date_choosen date_choosen
					FROM $wpdb->posts wposts, " . $wpdb->prefix . "penny_bids bids
					WHERE

					wposts.ID=bids.pid AND

					bids.winner='1' AND
					bids.shipped='0' AND
					bids.paid='0' ";


                $pageposts2 = $wpdb->get_results($querystr2, OBJECT);
                $total_count = count($pageposts2);
                $my_page = $page;
                $pages_curent = $page;
                //-----------------------------------------------------------------------

                $totalPages = ($total_count > 0 ? ceil($total_count / $nrpostsPage) : 0);
                $pagess = $totalPages;


                $querystr = "
					SELECT distinct wposts.* , bids.id bid_id, bids.uid winner_id, bids.bid bid, bids.date_made date_made
					FROM $wpdb->posts wposts, " . $wpdb->prefix . "penny_bids bids
					WHERE

					wposts.ID=bids.pid AND

					bids.winner='1' AND
					bids.shipped='0' AND
					bids.paid='0'

					ORDER BY wposts.post_date DESC LIMIT " . ($nrpostsPage * ($page - 1)) . "," . $nrpostsPage;


                $pageposts = $wpdb->get_results($querystr, OBJECT);
                $posts_per = 7;
                ?>

                <?php $i = 0;
                if ($pageposts):?>

                    <table class="table table-bordered" id="table">
                        <thead>
                        <tr>
                            <th>Auction Title</th>
                            <th>Seller</th>
                            <th>Buyer/Winner</th>
                            <th>Winning Bid</th>

                            <th>Shipping Cost</th>
                            <th>Total Cost</th>
                            <th>Purchased On</th>
                            <th>Options</th>
                        </tr>
                        </thead>
                        <tbody>


                        <?php global $post; ?>
                        <?php foreach ($pageposts as $post): ?>
                            <?php setup_postdata($post); ?>
                            <?php
                            $shp = get_post_meta(get_the_ID(), 'shipping', true);
                            if (empty($shp))
                                $shp = 0;
                            $shp1 = $shp;

                            $seller = get_userdata($post->post_author);
                            $winner = get_userdata($post->winner_id);
                            $bid = minbizeed_get_show_price($post->bid);
                            $date_choosen = date_i18n('d-m-Y H:i:s', $post->date_made);
                            $shp = minbizeed_get_show_price($shp);

                            $ttl = minbizeed_get_show_price($shp1 + $post->bid);
                            ?>

                            <tr>
                                <td><a href="<?php echo get_permalink(get_the_ID()); ?>"
                                       target="_blank"><?php the_title(); ?></a></td>
                                <td><?php echo $seller->user_login; ?></td>
                                <td><?php echo $winner->user_login; ?></td>
                                <td><?php echo $bid; ?></td>

                                <td><?php echo $shp; ?></td>
                                <td><?php echo $ttl; ?></td>
                                <td><?php echo $date_choosen; ?></td>
                                <td>
                                    <a href="<?php echo get_admin_url() ?>/admin.php?page=orders&mark_paid=<?php echo $post->bid_id; ?>">Mark
                                        as Paid</a></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>


                    <ul class="pagination">
                        <?php
                        $batch = 10; //ceil($page / $nrpostsPage );
                        $end = $batch * $nrpostsPage;


                        if ($end > $pagess) {
                            $end = $pagess;
                        }
                        $start = $end - $nrpostsPage + 1;

                        if ($start < 1)
                            $start = 1;

                        $links = '';


                        $raport = ceil($my_page / $batch) - 1;
                        if ($raport < 0)
                            $raport = 0;

                        $start = $raport * $batch + 1;
                        $end = $start + $batch - 1;
                        $end_me = $end + 1;
                        $start_me = $start - 1;

                        if ($end > $totalPages)
                            $end = $totalPages;
                        if ($end_me > $totalPages)
                            $end_me = $totalPages;

                        if ($start_me <= 0)
                            $start_me = 1;

                        $previous_pg = $page - 1;
                        if ($previous_pg <= 0)
                            $previous_pg = 1;

                        $next_pg = $pages_curent + 1;
                        if ($next_pg > $totalPages)
                            $next_pg = 1;


                        if ($my_page > 1) {
                            echo '<li><a href="' . get_bloginfo('siteurl') . '/wp-admin/admin.php?page=orders&pj=' . $previous_pg . '"><< ' . __('Previous', 'minbizeed') . '</a></li>';
                            echo '<li><a href="' . get_bloginfo('siteurl') . '/wp-admin/admin.php?page=orders&pj=' . $start_me . '"><<</a></li>';
                        }
                        //------------------------
                        //echo $start." ".$end;
                        for ($i = $start; $i <= $end; $i++) {
                            if ($i == $pages_curent) {
                                echo '<li><a class="activee" href="#" style="padding:0 20px 0 0;">' . $i . '</a></li>';
                            } else {

                                echo '<li><a style="padding:0 20px 0 0;" href="' . get_bloginfo('siteurl') . '/wp-admin/admin.php?page=orders&pj=' . $i . '">' . $i . '</a></li>';
                            }
                        }

                        //----------------------

                        if ($totalPages > $my_page)
                            echo '<li><a href="' . get_bloginfo('siteurl') . '/wp-admin/admin.php?page=orders&pj=' . $end_me . '">>></a></li>';

                        if ($page < $totalPages)
                            echo '<li><a href="' . get_bloginfo('siteurl') . '/wp-admin/admin.php?page=orders&pj=' . $next_pg . '">' . __('Next', 'minbizeed') . ' >></li></a>';
                        ?>
                    </ul>


                <?php else: ?>
                    <p class="no_items">
                        <?php _e('There are no items yet', 'minbizeed'); ?>
                    </p>
                <?php endif; ?>


                <?php
                wp_reset_query();
                ?>
            </div>

            <div id="tabs2" class="tab-pane fade in">

                <?php
                global $current_user;
                get_currentuserinfo();
                $uid = $current_user->ID;

                global $wp_query;
                $query_vars = $wp_query->query_vars;
                $nrpostsPage = 8;

                $page = $_GET['pj'];
                if (empty($page))
                    $page = 1;

                //---------------------------------


                global $wpdb;
                $querystr2 = "
					SELECT distinct wposts.ID , bids.id bid_id, bids.uid winner_id, bids.bid bid, bids.date_made date_made
					FROM $wpdb->posts wposts, " . $wpdb->prefix . "penny_bids bids
					WHERE

					wposts.ID=bids.pid AND

					bids.winner='1' AND
					bids.shipped='0' AND
					bids.paid='1' ";


                $pageposts2 = $wpdb->get_results($querystr2, OBJECT);
                $total_count = count($pageposts2);
                $my_page = $page;
                $pages_curent = $page;
                //-----------------------------------------------------------------------

                $totalPages = ($total_count > 0 ? ceil($total_count / $nrpostsPage) : 0);
                $pagess = $totalPages;


                $querystr = "
					SELECT distinct wposts.* , bids.id bid_id, bids.uid winner_id, bids.bid bid, bids.date_made date_made
					FROM $wpdb->posts wposts, " . $wpdb->prefix . "penny_bids bids
					WHERE

					wposts.ID=bids.pid AND

					bids.winner='1' AND
					bids.shipped='0' AND
					bids.paid='1'

					ORDER BY wposts.post_date DESC LIMIT " . ($nrpostsPage * ($page - 1)) . "," . $nrpostsPage;


                $pageposts = $wpdb->get_results($querystr, OBJECT);
                $posts_per = 7;
                ?>

                <?php $i = 0;
                if ($pageposts): ?>

                    <table class="table table-bordered" id="table">
                        <thead>
                        <tr>
                            <th>Auction Title</th>
                            <th>Seller</th>
                            <th>Buyer/Winner</th>
                            <th>Winning Bid</th>
                            <th>Shipping Cost</th>
                            <th>Total Cost</th>
                            <th>Purchased On</th>
                            <th>Paid On</th>
                            <th>Options</th>
                        </tr>
                        </thead>
                        <tbody>


                        <?php global $post; ?>
                        <?php foreach ($pageposts as $post): ?>
                            <?php setup_postdata($post); ?>
                            <?php
                            $seller = get_userdata($post->post_author);
                            $winner = get_userdata($post->winner_id);
                            $bid = minbizeed_get_show_price($post->bid);
                            $date_choosen = date_i18n('d-M-Y H:i:s', $post->date_made);
                            $date_paid = date_i18n('d-M-Y H:i:s', get_post_meta(get_the_ID(), 'paid_on_' . $post->bid_id, true));

                            $shp = get_post_meta(get_the_ID(), 'shipping', true);
                            if (empty($shp))
                                $shp = 0;
                            $shp = minbizeed_get_show_price($shp);

                            $ttl = minbizeed_get_show_price(get_post_meta(get_the_ID(), 'shipping', true) + $post->bid * $post->quant);
                            ?>

                            <tr>
                                <td><a href="<?php echo get_permalink(get_the_ID()); ?>"
                                       target="_blank"><?php the_title(); ?></a></td>
                                <td><?php echo $seller->user_login; ?></td>
                                <td><?php echo $winner->user_login; ?></td>
                                <td><?php echo $bid; ?></td>
                                <td><?php echo $shp; ?></td>
                                <td><?php echo $ttl; ?></td>
                                <td><?php echo $date_choosen; ?></td>
                                <dt><?php echo $date_paid; ?></dt>
                                <td>
                                    <a href="<?php echo get_admin_url() ?>/admin.php?page=orders&mark_shipped=<?php echo $post->bid_id; ?>">Mark
                                        as Shipped</a></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>


                    <ul class="pagination">
                        <?php
                        $batch = 10; //ceil($page / $nrpostsPage );
                        $end = $batch * $nrpostsPage;


                        if ($end > $pagess) {
                            $end = $pagess;
                        }
                        $start = $end - $nrpostsPage + 1;

                        if ($start < 1)
                            $start = 1;

                        $links = '';


                        $raport = ceil($my_page / $batch) - 1;
                        if ($raport < 0)
                            $raport = 0;

                        $start = $raport * $batch + 1;
                        $end = $start + $batch - 1;
                        $end_me = $end + 1;
                        $start_me = $start - 1;

                        if ($end > $totalPages)
                            $end = $totalPages;
                        if ($end_me > $totalPages)
                            $end_me = $totalPages;

                        if ($start_me <= 0)
                            $start_me = 1;

                        $previous_pg = $page - 1;
                        if ($previous_pg <= 0)
                            $previous_pg = 1;

                        $next_pg = $pages_curent + 1;
                        if ($next_pg > $totalPages)
                            $next_pg = 1;

                        if ($my_page > 1) {
                            echo '<li><a href="' . get_bloginfo('siteurl') . '/wp-admin/admin.php?page=orders&pj=' . $previous_pg . '"><< ' . __('Previous', 'minbizeed') . '</li></a>';
                            echo '<li><a href="' . get_bloginfo('siteurl') . '/wp-admin/admin.php?page=orders&pj=' . $start_me . '"><<</li></a>';
                        }
                        //------------------------
                        //echo $start." ".$end;
                        for ($i = $start; $i <= $end; $i++) {
                            if ($i == $pages_curent) {
                                echo '<li><a class="activee" href="#" style="padding:0 20px 0 0;">' . $i . '</a>';
                            } else {

                                echo '<li><a style="padding:0 20px 0 0;" href="' . get_bloginfo('siteurl') . '/wp-admin/admin.php?page=orders&pj=' . $i . '">' . $i . '</li></a>';
                            }
                        }

                        //----------------------

                        if ($totalPages > $my_page)
                            echo '<li><a href="' . get_bloginfo('siteurl') . '/wp-admin/admin.php?page=orders&pj=' . $end_me . '">>></li></a>';

                        if ($page < $totalPages)
                            echo '<li><a href="' . get_bloginfo('siteurl') . '/wp-admin/admin.php?page=orders&pj=' . $next_pg . '">' . __('Next', 'minbizeed') . ' >></li></a>';
                        ?>
                    </ul>


                <?php else: ?>

                    <p class="no_items">
                        <?php _e('There are no items yet', 'minbizeed'); ?>
                    </p>

                <?php endif; ?>



                <?php
                wp_reset_query();
                ?>
            </div>

            <div id="tabs3" class="tab-pane fade in">


                <?php
                global $current_user;
                get_currentuserinfo();
                $uid = $current_user->ID;

                global $wp_query;
                $query_vars = $wp_query->query_vars;
                $nrpostsPage = 8;

                $page = $_GET['pj'];
                if (empty($page))
                    $page = 1;

                //---------------------------------


                global $wpdb;
                $querystr2 = "
					SELECT distinct wposts.ID , bids.id bid_id, bids.uid winner_id, bids.bid bid, bids.date_made date_made , bids.shipped_on shipped_on
					FROM $wpdb->posts wposts, " . $wpdb->prefix . "penny_bids bids
					WHERE

					wposts.ID=bids.pid AND

					bids.winner='1' AND
					bids.shipped='1' AND
					bids.paid='1' ";


                $pageposts2 = $wpdb->get_results($querystr2, OBJECT);
                $total_count = count($pageposts2);
                $my_page = $page;
                $pages_curent = $page;
                //-----------------------------------------------------------------------

                $totalPages = ($total_count > 0 ? ceil($total_count / $nrpostsPage) : 0);
                $pagess = $totalPages;


                $querystr = "
					SELECT distinct wposts.* , bids.id bid_id, bids.uid winner_id, bids.bid bid, bids.date_made date_made , bids.shipped_on shipped_on
					FROM $wpdb->posts wposts, " . $wpdb->prefix . "penny_bids bids
					WHERE

					wposts.ID=bids.pid AND

					bids.winner='1' AND
					bids.shipped='1' AND
					bids.paid='1'

					ORDER BY wposts.post_date DESC LIMIT " . ($nrpostsPage * ($page - 1)) . "," . $nrpostsPage;


                $pageposts = $wpdb->get_results($querystr, OBJECT);
                $posts_per = 7;
                ?>

                <?php $i = 0;
                if ($pageposts):?>

                    <table class="table table-bordered" id="table">
                        <thead>
                        <tr>
                            <th>Auction Title</th>
                            <th>Seller</th>
                            <th>Buyer/Winner</th>
                            <th>Winning Bid</th>
                            <th>Shipping Cost</th>
                            <th>Total Cost</th>
                            <th>Purchased On</th>
                            <th>Paid On</th>
                            <th>Shipped On</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php global $post; ?>
                        <?php foreach ($pageposts as $post): ?>
                            <?php setup_postdata($post); ?>
                            <?php
                            $seller = get_userdata($post->post_author);
                            $winner = get_userdata($post->winner_id);
                            $bid = minbizeed_get_show_price($post->bid);
                            $date_choosen = date_i18n('d-M-Y H:i:s', $post->date_made);
                            $shipped_on = date_i18n('d-M-Y H:i:s', $post->shipped_on);
                            $date_paid = date_i18n('d-M-Y H:i:s', get_post_meta(get_the_ID(), 'paid_on_' . $post->bid_id, true));
                            $shp = get_post_meta(get_the_ID(), 'shipping', true);
                            if (empty($shp))
                                $shp = 0;
                            $shp = minbizeed_get_show_price($shp);

                            $ttl = minbizeed_get_show_price(get_post_meta(get_the_ID(), 'shipping', true) + $post->bid);
                            ?>

                            <tr>
                            <td><a href="<?php echo get_permalink(get_the_ID()); ?>"
                                   target="_blank"><?php the_title(); ?></a></td>
                            <td><?php echo $seller->user_login; ?></td>
                            <td><?php echo $winner->user_login; ?></td>
                            <td><?php echo $bid; ?></td>
                            <td><?php echo $shp; ?></td>
                            <td><?php echo $ttl; ?></td>
                            <td><?php echo $date_choosen; ?></td>
                            <td><?php echo $date_paid; ?></td>
                            <td><?php echo $shipped_on; ?></td>
                            </tr><?php endforeach; ?>
                        </tbody>
                    </table>


                    <ul class="pagination">
                        <?php
                        $batch = 10; //ceil($page / $nrpostsPage );
                        $end = $batch * $nrpostsPage;


                        if ($end > $pagess) {
                            $end = $pagess;
                        }
                        $start = $end - $nrpostsPage + 1;

                        if ($start < 1)
                            $start = 1;

                        $links = '';


                        $raport = ceil($my_page / $batch) - 1;
                        if ($raport < 0)
                            $raport = 0;

                        $start = $raport * $batch + 1;
                        $end = $start + $batch - 1;
                        $end_me = $end + 1;
                        $start_me = $start - 1;

                        if ($end > $totalPages)
                            $end = $totalPages;
                        if ($end_me > $totalPages)
                            $end_me = $totalPages;

                        if ($start_me <= 0)
                            $start_me = 1;

                        $previous_pg = $page - 1;
                        if ($previous_pg <= 0)
                            $previous_pg = 1;

                        $next_pg = $pages_curent + 1;
                        if ($next_pg > $totalPages)
                            $next_pg = 1;


                        if ($my_page > 1) {
                            echo '<li><a href="' . get_bloginfo('siteurl') . '/wp-admin/admin.php?page=orders&pj=' . $previous_pg . '"><< ' . __('Previous', 'minbizeed') . '</li></a>';
                            echo '<li><a href="' . get_bloginfo('siteurl') . '/wp-admin/admin.php?page=orders&pj=' . $start_me . '"><<</li></a>';
                        }
                        //------------------------
                        //echo $start." ".$end;
                        for ($i = $start; $i <= $end; $i++) {
                            if ($i == $pages_curent) {
                                echo '<li><a class="activee" href="#" style="padding:0 20px 0 0;">' . $i . '</a>';
                            } else {

                                echo '<li><a style="padding:0 20px 0 0;" href="' . get_bloginfo('siteurl') . '/wp-admin/admin.php?page=orders&pj=' . $i . '">' . $i . '</li></a>';
                            }
                        }

                        //----------------------

                        if ($totalPages > $my_page)
                            echo '<li><a href="' . get_bloginfo('siteurl') . '/wp-admin/admin.php?page=orders&pj=' . $end_me . '">>></li></a>';

                        if ($page < $totalPages)
                            echo '<li><li href="' . get_bloginfo('siteurl') . '/wp-admin/admin.php?page=orders&pj=' . $next_pg . '">' . __('Next', 'minbizeed') . ' >></li></a>';
                        ?>
                    </ul>


                <?php else: ?>

                    <p class="no_items">
                        <?php _e('There are no items yet', 'minbizeed'); ?>
                    </p>

                <?php endif; ?>



                <?php
                wp_reset_query();
                ?>


            </div>

        </div>
    </div>

    <script type="text/javascript">
        $(function () {
            var hash = window.location.hash;
            hash && $('ul.nav a[href="' + hash + '"]').tab('show');

            $('.nav-tabs a').click(function (e) {
                $(this).tab('show');
                var scrollmem = $('body').scrollTop() || $('html').scrollTop();
                window.location.hash = this.hash;
                $('html,body').scrollTop(scrollmem);
            });
        });
    </script>
    <style type="text/css">
        .no_items{
            text-align: center;
            margin:20px 0;
        }
        .nav li.active{
            border-top: 1px solid #ccc;
            border-right: 1px solid #ccc;
            border-left: 1px solid #ccc;
        }
    </style>

    <?php

    if (isset($_GET['mark_paid'])) {
        global $wpdb;
        $s = "update " . $wpdb->prefix . "penny_bids set paid='1' where id='" . $_GET['mark_paid'] . "'";
        $wpdb->query($s);

        $s1 = "select * from " . $wpdb->prefix . "penny_bids where id='" . $_GET['mark_paid'] . "' ";
        $r1 = $wpdb->get_results($s1);
        $row1 = $r1[0];


        update_post_meta($row1->pid, 'winner_paid', 1);
        update_post_meta($row1->pid, 'paid_on_' . $_GET['mark_paid'], current_time('timestamp', 0));
        echo '<div class="saved_thing">The item was marked as paid.</div>';
    }

    if (isset($_GET['mark_shipped'])) {
        global $wpdb;
        $s = "update " . $wpdb->prefix . "penny_bids set shipped='1', shipped_on='" . current_time('timestamp', 0) . "' where id='" . $_GET['mark_shipped'] . "'";
        $wpdb->query($s);

        $s1 = "select * from " . $wpdb->prefix . "penny_bids where id='" . $_GET['mark_shipped'] . "' ";
        $r1 = $wpdb->get_results($s1);
        $row1 = $r1[0];

        update_post_meta($row1->pid, 'shipped', 1);
        echo '<div class="saved_thing">The item was marked as shipped.</div>';
    }
}