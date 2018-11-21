<?php
function minbizeed_not_fullfilled_refunds()
{
    ?>
    <div class="admin_stats container">
        <h3>Not fulfilled refunds</h3>
        <div class="elt srch">
            <form method="get" action="<?php echo get_bloginfo('siteurl'); ?>/wp-admin/admin.php">
                <input type="hidden" name="page" value="trans-refunds"/>
                Search Transactions: <input type="text" size="35" value="<?php echo $_GET['src_usr']; ?>"
                                            name="src_usr" placeholder="Enter username to search"/>
                <input type="submit" value="Submit" name="" class="btn btn-primary"/>
                <a href="/wp-admin/admin.php?page=trans-refunds" class="btn btn-warning">Clear</a>
            </form>
        </div>
        <div class="panel-body holder">
            <div class="panel-body holder">

                <?php
                $rows_per_page = 10;

                if (isset($_GET['pj']))
                    $pageno = $_GET['pj'];
                else
                    $pageno = 1;

                global $wpdb;

                if (!empty($_GET['src_usr'])) {

                    $src_usr = $_GET['src_usr'];
                    $src_usr = get_user_by('login', $src_usr);
                    if ($src_usr) {
                        $src_usr_id = $src_usr->ID;
                        $s1 = "select id from " . $wpdb->prefix . "auctions_not_fulfilled_refunds where uid = '$src_usr_id' order by uid desc ";
                        $s = "select * from " . $wpdb->prefix . "auctions_not_fulfilled_refunds where uid = '$src_usr_id' order by uid desc ";
                    } else {
                        $src_usr_id = "";
                    }
                } else {
                    $s1 = "select id from " . $wpdb->prefix . "auctions_not_fulfilled_refunds order by id desc ";
                    $s = "select * from " . $wpdb->prefix . "auctions_not_fulfilled_refunds order by id desc ";
                }
                $limit = 'LIMIT ' . ($pageno - 1) * $rows_per_page . ',' . $rows_per_page;
                $r = $wpdb->get_results($s1);

                $nr = count($r);
                $lastpage = ceil($nr / $rows_per_page);

                $r = $wpdb->get_results($s . $limit);

                if ($nr > 0) {
                    ?>
                    <table class="table table-bordered" id="table">
                        <thead>
                        <tr>
                            <th>Auction</th>
                            <th>User</th>
                            <th>Bids</th>
                            <th>Status</th>
                            <th>Time</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($r as $row) {
                            $user = get_userdata($row->uid);
                            $auction_id = $row->pid;

                            if ($row->status == 0) {
                                $sign = '-';
                                $style = 'background-color:red;';
                            } else {
                                $sign = '+';
                                $style = 'background-color:green;';
                            }
                            ?>
                            <tr class="<?php echo $style; ?>">
                                <td class="white_row">
                                    <?php echo get_the_title($auction_id); ?>
                                </td>
                                <td class="white_row">
                                    <?php echo $user->user_login; ?>
                                </td>
                                <td class="white_row">
                                    <?php echo $row->bids; ?>
                                </td>

                                <?php
                                if ($row->status == 0) {
                                    ?>
                                    <td class="white_row"><b>NOT REFUNDED</b></th>
                                    <?php
                                } else {
                                    ?>
                                    <td class="white_row"><b>REFUNDED</b></th>
                                    <?php
                                }
                                ?>
                                <td class="white_row">
                                    <?php
                                    echo date("Y-m-d H:i:s", floor($row->date/1000));
                                    ?>
                                </td>
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
                                <a href="<?php echo get_bloginfo('siteurl'); ?>/wp-admin/admin.php?page=trans-refunds&pj=<?php echo $i; ?>&src_usr=<?php echo $_GET['src_usr']; ?>"><?php echo $i; ?></a>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                    <?php
                } else {
                    if (!empty($_GET['src_usr'])) {
                        _e('No users found.', 'minbizeed');
                    } else {
                        _e('Sorry no refunds yet.', 'minbizeed');
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