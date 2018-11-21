<?php
function minbizeed_payments_stats()
{
    ?>
    <div class="admin_stats stats_payments container">
        <h3>
            Payments Stats
        </h3>
        <div class="form-group col-lg-12 col-md-12 col-sm-12">
            <h4>Select dates to filter</h4>
        </div>

        <div class="form-group col-lg-12 col-md-12 col-sm-12">
            <form action="<?php echo getCurrentURL(); ?>" method="post">
                <?php
                $from = $_POST['from_1'];
                $to = $_POST['to_1'];
                ?>
                <div class="form-group col-lg-4">
                    <label for="form_1">From:</label>
                    <input type="text" name="from_1"
                           class="from_1 set_date"<?php if (isset($from)): echo " value='$from'"; endif; ?> />
                </div>
                <div class="form-group col-lg-4">
                    <label for="to_1">To:</label>
                    <input type="text" name="to_1"
                           class="to_1 set_date"<?php if (isset($to)): echo " value='$to'"; endif; ?>/>
                </div>
                <div class="form-group col-lg-4">
                    <input type="hidden" name="submit_flag" value="1"/>
                    <input type="submit" name="submit" class="btn btn-primary" value="Submit"/>
                    <a class="clear btn btn-danger" href="#">Clear</a>
                </div>
            </form>
        </div>

        <div class="panel-body holder">

            <?php
            global $wpdb;

            $rows_per_page = 20;

            if (isset($_GET['pj'])) {
                $pageno = $_GET['pj'];
            } else {
                $pageno = 1;
            }

            if (isset($_POST['submit_flag'])):

                ?>
                <div class="form-group col-lg-12 col-md-12 col-sm-12">
                    <h4>Results from <b><?php echo $from; ?></b> to <b><?php echo $to; ?></b>:</h4>
                </div>
                <?php

                $s1 = "select id from " . $wpdb->prefix . "penny_payment_transactions where datemade >= '" . $_POST['from_1'] . " 00:00:00 am' and datemade <= '" . $_POST['to_1'] . " 23:59:59 pm' and status=0 and concat('',status * 1) = status order by id desc ";
                $s = "select * from " . $wpdb->prefix . "penny_payment_transactions JOIN `" . $wpdb->prefix . "penny_packages` ON `" . $wpdb->prefix . "penny_payment_transactions`.tp=`" . $wpdb->prefix . "penny_packages`.`id` where datemade >= '" . $_POST['from_1'] . " 00:00:00 am' and datemade <= '" . $_POST['to_1'] . " 23:59:59 pm' and status=0 and concat('',status * 1) = status order by " . $wpdb->prefix . "penny_payment_transactions.id desc ";

                $sum_amount = "SELECT SUM(amount) AS amount FROM " . $wpdb->prefix . "penny_payment_transactions where datemade >= '" . $_POST['from_1'] . " 00:00:00 am' and datemade <= '" . $_POST['to_1'] . " 23:59:59 pm' and status=0 and concat('',status * 1) = status";
                $sum_amount_r = $wpdb->get_results($sum_amount);

                $sum_bids = "SELECT SUM(bids) as total_bids FROM `" . $wpdb->prefix . "penny_packages` JOIN `" . $wpdb->prefix . "penny_payment_transactions` ON `" . $wpdb->prefix . "penny_packages`.id=`" . $wpdb->prefix . "penny_payment_transactions`.tp WHERE `" . $wpdb->prefix . "penny_payment_transactions`.datemade >= '" . $_POST['from_1'] . " 00:00:00 am' and `" . $wpdb->prefix . "penny_payment_transactions`.datemade <= '" . $_POST['to_1'] . " 23:59:59 pm' and `" . $wpdb->prefix . "penny_payment_transactions`.status=0 and concat('',`" . $wpdb->prefix . "penny_payment_transactions`.status * 1) = `" . $wpdb->prefix . "penny_payment_transactions`.status";
                $sum_bids_r = $wpdb->get_results($sum_bids);

                $r = $wpdb->get_results($s1);

                $nr = count($r);

                if ($nr > 0) :
                    ?>
                    <div class="form-group col-lg-12 col-md-12 col-sm-12">
                        <h4>Total purchases amount from <b><?php echo $_POST['from_1']; ?></b> to
                            <b><?php echo $_POST['to_1']; ?></b>:
                            <mark><?php echo $sum_amount_r[0]->amount; ?> $</mark></h4>
                    </div>
                    <div class="form-group col-lg-12 col-md-12 col-sm-12">
                        <h4>Total bids purchased from <b><?php echo $_POST['from_1']; ?></b> to
                            <b><?php echo $_POST['to_1']; ?></b>:
                            <mark><?php echo $sum_bids_r[0]->total_bids; ?> Bids</mark></h4>
                    </div>
                    <?php
                endif;
            else:

                ?>
                <div class="form-group col-lg-12 col-md-12 col-sm-12">
                    <h4>Global results:</h4>
                </div>
                <?php

                $s1 = "select id from " . $wpdb->prefix . "penny_payment_transactions where status=0 and concat('',status * 1) = status order by id desc ";
                $s = "select * from " . $wpdb->prefix . "penny_payment_transactions JOIN `" . $wpdb->prefix . "penny_packages` ON `" . $wpdb->prefix . "penny_payment_transactions`.tp=`" . $wpdb->prefix . "penny_packages`.`id` where status=0 and concat('',status * 1) = status order by " . $wpdb->prefix . "penny_payment_transactions.id desc ";

                $sum_amount = "SELECT SUM(amount) AS amount FROM " . $wpdb->prefix . "penny_payment_transactions where status=0 and concat('',status * 1) = status";
                $sum_amount_r = $wpdb->get_results($sum_amount);

                $sum_bids = "SELECT SUM(bids) as total_bids FROM `" . $wpdb->prefix . "penny_packages` JOIN `" . $wpdb->prefix . "penny_payment_transactions` ON `" . $wpdb->prefix . "penny_packages`.id=`" . $wpdb->prefix . "penny_payment_transactions`.tp WHERE `" . $wpdb->prefix . "penny_payment_transactions`.status=0 and concat('',`" . $wpdb->prefix . "penny_payment_transactions`.status * 1) = `" . $wpdb->prefix . "penny_payment_transactions`.status";
                $sum_bids_r = $wpdb->get_results($sum_bids);

                $r = $wpdb->get_results($s1);

                $nr = count($r);

                if ($nr > 0) :
                    ?>
                    <div class="form-group col-lg-12 col-md-12 col-sm-12">
                        <h4>Total purchases amount: <mark><?php echo $sum_amount_r[0]->amount; ?> $</mark></h4>
                    </div>
                    <div class="form-group col-lg-12 col-md-12 col-sm-12">
                        <h4>Total purchased bids: <mark><?php echo $sum_bids_r[0]->total_bids; ?> Bids</mark></h4>
                    </div>
                    <?php
                endif;
            endif;

            $limit = 'LIMIT ' . ($pageno - 1) * $rows_per_page . ',' . $rows_per_page;

            $lastpage = ceil($nr / $rows_per_page);

            $r = $wpdb->get_results($s . $limit);

            if ($nr > 0) {
                ?>
                <table class="table table-bordered" id="table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Date Made</th>
                        <th>Amount</th>
                        <th>Bids</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($r as $row) {
                        $user = get_userdata($row->uid);
                        ?>
                        <tr>
                            <td>
                                <?php echo $row->id; ?>
                            </td>
                            <td>
                                <?php echo $user->user_login; ?>
                            </td>
                            <td>
                                <?php
                                echo $row->datemade;
                                ?>
                            </td>
                            <td>
                                <?php echo $row->amount; ?>
                            </td>
                            <td>
                                <?php echo $row->bids; ?> Bids
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
                            <a href="<?php echo get_bloginfo('siteurl'); ?>/wp-admin/admin.php?page=payments-stats&pj=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
                <?php
            } else {
                _e('Sorry no transactions found.', 'minbizeed');
            }
            ?>
        </div>

        <script type="text/javascript">
            jQuery('.set_date').pickadate({
                format: 'dd/mm/yyyy',
                max: true,
            });
            jQuery(document).on('click', '.clear', function () {
                jQuery('.from_1').val("");
                jQuery('.to_1').val("");
                location.href="/wp-admin/admin.php?page=bids-stats";
            });
        </script>
    </div>
    <?php
}