<?php
function minbizeed_hist_transact()
{
    ?>
    <div class="admin_stats container">
        <h3>Transactions History</h3>
        <div class="elt srch">
            <form method="get" action="<?php echo get_bloginfo('siteurl'); ?>/wp-admin/admin.php">
                <input type="hidden" name="page" value="trans-sites"/>
                Search Transactions: <input type="text" size="35" value="<?php echo $_GET['src_usr']; ?>"
                                            name="src_usr" placeholder="Enter username to search"/>
                <input type="submit" value="Submit" name="" class="btn btn-primary"/>
                <a href="/wp-admin/admin.php?page=trans-sites" class="btn btn-warning">Clear</a>
            </form>
        </div>
        <div class="panel-body holder">
            <div class="panel-body holder">

                <?php
                global $wpdb;
                $stat1 = "select * from " . $wpdb->prefix . "penny_payment_transactions";
                $stat1_r = $wpdb->get_results($stat1);
                $tot_am = 0;
                $all_payments = 0;
                foreach ($stat1_r as $stat1_r_row) {
                    if (is_numeric($stat1_r_row->status)) {
                        if ($stat1_r_row->status == 0) {
                            $all_payments += $stat1_r_row->amount;
                            $timestamp = strtotime($stat1_r_row->datemade);
                            $date = date('d-m-Y', $timestamp);
                            $current_date = date('d-m-Y');
                            if ($current_date == $date) {
                                $tot_am += $stat1_r_row->amount;
                            }
                        }
                    }
                }
                if ($tot_am) {
                    ?>
                    <h4>Payments made today "<?php echo $current_date; ?>" : <?php echo $tot_am; ?> $</h4>
                    <?php
                }
                if ($all_payments) {
                    ?>
                    <h4>Total payments ever made : <?php echo $all_payments; ?> $</h4>
                    <?php
                }
                $rows_per_page = 20;

                if (isset($_GET['pj']))
                    $pageno = $_GET['pj'];
                else
                    $pageno = 1;

                if (!empty($_GET['src_usr'])) {

                    $src_usr = $_GET['src_usr'];
                    $src_usr = get_user_by('login', $src_usr);
                    if ($src_usr) {
                        $src_usr_id = $src_usr->ID;
                        $s1 = "select id from " . $wpdb->prefix . "penny_payment_transactions where uid = '$src_usr_id' order by id desc ";
                        $s = "select * from " . $wpdb->prefix . "penny_payment_transactions where uid = '$src_usr_id' order by id desc ";
                    } else {
                        $src_usr_id = "";
                    }
                } else {
                    $s1 = "select id from " . $wpdb->prefix . "penny_payment_transactions order by id desc ";
                    $s = "select * from " . $wpdb->prefix . "penny_payment_transactions order by id desc ";
                }
                $limit = 'LIMIT ' . ($pageno - 1) * $rows_per_page . ',' . $rows_per_page;

                $r = $wpdb->get_results($s1);

                $nr = count($r);
                $lastpage = ceil($nr / $rows_per_page);

                $r = $wpdb->get_results($s . $limit);

                if ($nr > 0) {
                    ?>
                    <p class="ajax_return" style=""></p>
                    <img class="loader" src="<?php echo get_bloginfo('template_url'); ?>/images/ajax_loader.gif"/>

                    <table class="table table-bordered" id="table">
                        <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Username</th>
                            <th>Bid Added?</th>
                            <th>Comment/Description</th>
                            <th>Date Made</th>
                            <th>Amount</th>
                            <th>Desc</th>
                            <th>Bank transaction Ref#</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($r as $row) {
                            $user = get_userdata($row->uid);

                            if (is_numeric($row->status)) {
                                if ($row->status == 0) {
                                    $sign = '+';
                                    $style = 'background-color:green;';
                                } else {
                                    $sign = '-';
                                    $style = 'background-color:red;';
                                }
                            } else {
                                $sign = '-';
                                $style = 'background-color:red;';
                            }
                            ?>
                            <tr class="<?php echo $style; ?>">
                                <td class="white_row">
                                    <?php echo $user->ID; ?>
                                </td>
                                <td class="white_row">
                                    <?php echo $user->user_login; ?>
                                </td>
                                <td class="white_row">
                                    <?php echo $user->bid_added; ?>
                                </td>
                                <td class="white_row">
                                    <?php echo $user->datemade; ?>
                                </td>
                                <td class="white_row">
                                    <?php echo $sign . minbizeed_get_show_price($row->amount, 2); ?>
                                </td>
                                <td class="white_row">
                                    <?php echo $row->tp; ?>
                                </td>
                                <td class="white_row">
                                    <?php echo $row->uid2; ?>
                                </td>
                                <?php
                                if (is_numeric($row->status)) {
                                    if ($row->status == 0) {
                                        echo '<td class="white_row"><b>PAID</b></td>';
                                    } else {
                                        echo '<td class="white_row"><b>NOT PAID</b></td>';
                                    }
                                } else {
                                    echo '<td class="white_row"><b>NOT PAID</b></td>';
                                }
                                ?>
                            </tr>
                            <?php
                        }
                        ?>

                        </tbody>
                    </table>
                    <ul class="pagination">
                        <?php
                        for ($i = 1; $i <= $lastpage; $i++) {

                            ?>
                            <li>
                                <a href="<?php echo get_bloginfo('siteurl'); ?>/wp-admin/admin.php?page=trans-sites&pj=<?php echo $i; ?>&src_usr=<?php echo $_GET['src_usr']; ?>"><?php echo $i; ?></a>
                            </li>
                            <?php

//                        if ($pageno == $i)
//                            echo $i . " | ";
//                        else
//                            echo '';
                        }
                        ?>
                    </ul>
                    <?php
                } else {
                    if (!empty($_GET['src_usr'])) {
                        _e('No users found.', 'minbizeed');
                    } else {
                        _e('Sorry no transactions yet.', 'minbizeed');
                    }
                }
                ?>
            </div>
        </div>
    </div>
    <style type="text/css">
        .clear {
            clear: both;
        }

        .header {
            margin: 20px 0;
        }

        .header .elt {
            display: inline-block;
        }

        .header .elt h2 {
            margin: 0 0 10px 0;
        }

        .header .elt.srch {
            float: right;
        }

        .header .elt.srch a {
            -webkit-box-shadow: 1.7px 1.7px 1px #787878;
            -moz-box-shadow: 1.7px 1.7px 1px #787878;
            box-shadow: 1.7px 1.7px 1px #787878;
            padding: 5px;
            text-decoration: none;
            border: 1px solid #000;
        }
    </style>
    <?php
}