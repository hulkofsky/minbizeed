<?php
function minbizeed_free_bids_stats()
{
    ?>
    <div class="admin_stats stats_bids container">
        <h3>
            Free Bids
        </h3>
        <div class="alert alert-danger form_errors"></div>
        <div class="panel-body holder">
            <div class="form-group col-lg-12 col-md-12 col-sm-12">
                <h4>Select dates to filter</h4>
            </div>
            <div class="form-group col-lg-12 col-md-12 col-sm-12">
                <form id="from_to_form" action="<?php echo getCurrentURL(); ?>" method="post">
                    <?php
                    $from = $_POST['from_3'];
                    $to = $_POST['to_3'];
                    ?>
                    <div class="form-group col-lg-4">
                        <label for="form_2">From:</label>
                        <input type="text" name="from_3"
                               class="from_3 set_date"<?php if (isset($from)): echo " value='$from'"; endif; ?> />
                    </div>
                    <div class="form-group col-lg-4">
                        <label for="to_3">To:</label>
                        <input type="text" name="to_3"
                               class="to_3 set_date"<?php if (isset($to)): echo " value='$to'"; endif; ?>/>
                    </div>
                    <div class="form-group col-lg-4">
                        <input type="hidden" name="submit_flag" value="1"/>
                        <input type="submit" name="submit" class="btn btn-primary" value="Submit"/>
                        <a class="clear btn btn-danger" href="#">Clear</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="panel-body holder">
            <div class="form-group col-lg-12 col-md-12 col-sm-12">
                <h4>Or search by user</h4>
            </div>
            <div class="form-group col-lg-12 col-md-12 col-sm-12">
                <form id="user_form" action="<?php echo getCurrentURL(); ?>" method="post">
                    <?php
                    $user_s = $_POST['user_s'];
                    ?>
                    <div class="form-group col-lg-4">
                        <label for="form_2">User:</label>
                        <input type="text" name="user_s"
                               class="user_s"<?php if (isset($user_s)): echo " value='$user_s'"; endif; ?> />
                    </div>
                    <div class="form-group col-lg-4">
                        <input type="hidden" name="submit_flag_2" value="1"/>
                        <input type="submit" name="submit" class="btn btn-primary" value="Submit"/>
                        <a class="clear btn btn-danger" href="#">Clear</a>
                    </div>
                </form>
            </div>
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

            $from = $_POST['from_3'];
            $to = $_POST['to_3'];

            if (!empty($_POST['submit_flag']) && !empty($from) && !empty($to)):

                ?>
                <div class="form-group col-lg-12 col-md-12 col-sm-12">
                    <h4>Results from <b><?php echo $from; ?></b> to <b><?php echo $to; ?></b>:</h4>
                </div>
                <?php

                $s1 = "SELECT `id` from `" . $wpdb->prefix . "bids_transfers` WHERE `date` >= '" . $_POST['from_3'] . " 00:00:00 am' AND `date` <= '" . $_POST['to_3'] . " 23:59:59 pm' ORDER BY `id` DESC ";
                $s = "SELECT * from `" . $wpdb->prefix . "bids_transfers` WHERE `date` >= '" . $_POST['from_3'] . " 00:00:00 am' AND `date` <= '" . $_POST['to_3'] . " 23:59:59 pm' ORDER BY `id` DESC ";

                $sum_amount = "SELECT SUM(amount) AS amount FROM `" . $wpdb->prefix . "bids_transfers` where date >= '" . $_POST['from_3'] . " 00:00:00 am' and `date` <= '" . $_POST['to_3'] . " 23:59:59 pm' and `action`=1";
                $sum_amount_r = $wpdb->get_results($sum_amount);

                $sum_amount_2 = "SELECT SUM(amount) AS amount FROM `" . $wpdb->prefix . "bids_transfers` where date >= '" . $_POST['from_3'] . " 00:00:00 am' and `date` <= '" . $_POST['to_3'] . " 23:59:59 pm' and `action`=0";
                $sum_amount_r_2 = $wpdb->get_results($sum_amount_2);

                if (empty($sum_amount_r_2[0]->amount)) {
                    $sum_amount_f_2 = 0;
                } else {
                    $sum_amount_f_2 = $sum_amount_r_2[0]->amount;
                }

                $r = $wpdb->get_results($s1);

                $nr = count($r);

                if ($nr > 0) :
                    ?>
                    <div class="form-group col-lg-12 col-md-12 col-sm-12">
                        <h4>Total given bids from <b><?php echo $_POST['from_3']; ?></b> to
                            <b><?php echo $_POST['to_3']; ?></b>:
                            <mark>+ <?php echo $sum_amount_r[0]->amount; ?> Bids</mark></h4>
                    </div>

                    <div class="form-group col-lg-12 col-md-12 col-sm-12">
                        <h4>Total taken bids from <b><?php echo $_POST['from_3']; ?></b> to
                            <b><?php echo $_POST['to_3']; ?></b>:
                            <mark>- <?php echo $sum_amount_f_2; ?> Bids</mark></h4>
                    </div>
                    <?php
                endif;
            elseif (!empty($_POST['submit_flag_2']) && !empty($_POST['user_s'])):
                $user_s = $_POST['user_s'];
                $user_name_id = get_user_by('login', $user_s)->ID;
                ?>
                <div class="form-group col-lg-12 col-md-12 col-sm-12">
                    <h4>Results for <?php echo $user_s; ?>:</h4>
                </div>
                <?php
                $s1 = "select id from " . $wpdb->prefix . "bids_transfers where to_uid=" . $user_name_id . " order by id desc ";
                $s = "select * from " . $wpdb->prefix . "bids_transfers where to_uid=" . $user_name_id . " order by id desc ";

                $sum_amount = "SELECT SUM(amount) AS amount FROM `" . $wpdb->prefix . "bids_transfers` where to_uid=" . $user_name_id . " and `action`=1";
                $sum_amount_r = $wpdb->get_results($sum_amount);

                $sum_amount_2 = "SELECT SUM(amount) AS amount FROM `" . $wpdb->prefix . "bids_transfers` where where to_uid=" . $user_name_id . " and `action`=0";
                $sum_amount_r_2 = $wpdb->get_results($sum_amount_2);

                if (empty($sum_amount_r_2[0]->amount)) {
                    $sum_amount_f_2 = 0;
                } else {
                    $sum_amount_f_2 = $sum_amount_r_2[0]->amount;
                }

                $r = $wpdb->get_results($s1);

                $nr = count($r);

                if ($nr > 0) :
                    ?>
                    <div class="form-group col-lg-12 col-md-12 col-sm-12">
                        <h4>Total given bids to <b><?php echo $user_s; ?></b>:
                            <mark>+ <?php echo $sum_amount_r[0]->amount; ?> Bids</mark></h4>
                    </div>

                    <div class="form-group col-lg-12 col-md-12 col-sm-12">
                        <h4>Total taken bids to <b><?php echo $user_s; ?></b>:
                            <mark>- <?php echo $sum_amount_f_2; ?> Bids</mark></h4>
                    </div>
                    <?php
                endif;
            else:
                ?>
                <div class="form-group col-lg-12 col-md-12 col-sm-12">
                    <h4>Global results:</h4>
                </div>
                <?php

                $s1 = "select id from " . $wpdb->prefix . "bids_transfers order by id desc ";
                $s = "select * from " . $wpdb->prefix . "bids_transfers order by id desc ";

                $sum_amount = "SELECT SUM(amount) AS amount FROM `" . $wpdb->prefix . "bids_transfers` where `action`=1";
                $sum_amount_r = $wpdb->get_results($sum_amount);

                $sum_amount_2 = "SELECT SUM(amount) AS amount FROM `" . $wpdb->prefix . "bids_transfers` where `action`=0";
                $sum_amount_r_2 = $wpdb->get_results($sum_amount_2);

                if (empty($sum_amount_r_2[0]->amount)) {
                    $sum_amount_f_2 = 0;
                } else {
                    $sum_amount_f_2 = $sum_amount_r_2[0]->amount;
                }

                $r = $wpdb->get_results($s1);

                $nr = count($r);

                if ($nr > 0) :
                    ?>
                    <div class="form-group col-lg-12 col-md-12 col-sm-12">
                        <h4>Total given bids:
                            <mark>+ <?php echo $sum_amount_r[0]->amount; ?> Bids</mark></h4>
                    </div>

                    <div class="form-group col-lg-12 col-md-12 col-sm-12">
                        <h4>Total taken bids:
                            <mark>- <?php echo $sum_amount_f_2; ?> Bids</mark></h4>
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
                        <th>Action</th>
                        <th>Type</th>
                        <th>By User</th>
                        <th>To User</th>
                        <th>Credits before</th>
                        <th>Amount</th>
                        <th>Credits after</th>
                        <th>Bank before</th>
                        <th>Bank after</th>
                        <th>Date</th>
                        <th>IP</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($r as $row) {
                        $by_user = get_userdata($row->by_uid);
                        $to_user = get_userdata($row->to_uid);
                        ?>
                        <tr>
                            <td>
                                <?php echo $row->id; ?>
                            </td>
                            <td>
                                <?php if ($row->action == 1) {
                                    echo "+";
                                } elseif ($row->action == 0) {
                                    echo "-";
                                } ?>
                            </td>
                            <td>
                                <?php echo $row->type; ?>
                            </td>
                            <td>
                                <?php echo $by_user->user_login; ?>
                            </td>
                            <td>
                                <?php echo $to_user->user_login; ?>
                            </td>
                            <td>
                                <?php echo $row->credits_before; ?>
                            </td>
                            <td>
                                <?php echo $row->amount; ?>
                            </td>
                            <td>
                                <?php echo $row->credits_after; ?>
                            </td>
                            <td>
                                <?php echo $row->bank_before; ?>
                            </td>
                            <td>
                                <?php echo $row->bank_after; ?>
                            </td>
                            <td>
                                <?php echo $row->date; ?>
                            </td>
                            <td>
                                <?php echo $row->ip; ?>
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
                            <a href="<?php echo get_bloginfo('siteurl'); ?>/wp-admin/admin.php?page=free-bids-stats&pj=<?php echo $i; ?>"><?php echo $i; ?></a>
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
            jQuery(document).ready(function () {

                var validation = jQuery('.form_errors');

                jQuery('.set_date').pickadate({
                    format: 'mm/dd/yyyy',
                    max: true,
                });
                jQuery(document).on('click', '.clear', function () {
                    jQuery('.from_3').val("");
                    jQuery('.to_3').val("");
                    location.href = "/wp-admin/admin.php?page=free-bids-stats";
                });

                function from_validation(form) {
                    if (form === "from_to_form") {
                        var from = jQuery('.from_3').val();
                        var to = jQuery('.to_3').val();
                        if (from && to) {
                            return true;
                        }
                    } else if (form === "user_form") {
                        var user = jQuery('.user_s').val();
                        if (user) {
                            return true;
                        }
                    }
                }

                jQuery("#from_to_form").submit(function () {
                    validation.slideUp();
                    if (!from_validation("from_to_form")) {
                        validation.html('From and To fields are required');
                        validation.slideDown();
                        return false;
                    }
                });

                jQuery("#user_form").submit(function () {
                    validation.slideUp();
                    if (!from_validation("user_form")) {
                        validation.html('User field is required!');
                        validation.slideDown();
                        return false;
                    }
                });
            });
        </script>
    </div>
    <?php
}