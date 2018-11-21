<?php
function minbizeed_bids_stats()
{
    ?>
    <div class="admin_stats stats_bids container">
        <h3>
            Bids Stats
        </h3>
        <div class="alert alert-danger form_errors"></div>
        <div class="panel-body holder">
            <div class="form-group col-lg-12 col-md-12 col-sm-12">
                <h4>Select dates to filter</h4>
            </div>
            <div class="form-group col-lg-12 col-md-12 col-sm-12">
                <form id="from_to_form" action="<?php echo getCurrentURL(); ?>" method="post">
                    <?php
                    $from = $_POST['from_2'];
                    $to = $_POST['to_2'];
                    ?>
                    <div class="form-group col-lg-4">
                        <label for="form_2">From:</label>
                        <input type="text" name="from_2"
                               class="from_2 set_date"<?php if (isset($from)): echo " value='$from'"; endif; ?> />
                    </div>
                    <div class="form-group col-lg-4">
                        <label for="to_2">To:</label>
                        <input type="text" name="to_2"
                               class="to_2 set_date"<?php if (isset($to)): echo " value='$to'"; endif; ?>/>
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

            $from = $_POST['from_2'];
            $to = $_POST['to_2'];

            if (!empty($_POST['submit_flag']) && !empty($from) && !empty($to)):

                $modded_from = $from . " 00:00:00";
                $from_replace = str_replace('/', '-', $modded_from);
                $from_timestamp = strtotime($from_replace);

                $modded_to = $to . " 23:59:59";
                $to_replace = str_replace('/', '-', $modded_to);
                $to_timestamp = strtotime($to_replace);
                ?>
                <div class="form-group col-lg-12 col-md-12 col-sm-12">
                    <h4>Results from <b><?php echo $from; ?></b> to <b><?php echo $to; ?></b>:</h4>
                </div>
                <?php

                $s1 = "select id from `" . $wpdb->prefix . "penny_bids` WHERE `date_made` BETWEEN " . $from_timestamp . " AND " . $to_timestamp . " order by id desc ";
                $s = "select * from `" . $wpdb->prefix . "penny_bids` WHERE `date_made` BETWEEN " . $from_timestamp . " AND " . $to_timestamp . " order by id desc ";

                $r = $wpdb->get_results($s1);

                $nr = count($r);

                if ($nr > 0) :
                    ?>
                    <div class="form-group col-lg-12 col-md-12 col-sm-12">
                        <h4>Total used bids from <b><?php echo $_POST['from_2']; ?></b> to
                            <b><?php echo $_POST['to_2']; ?></b>:
                            <mark><?php echo $nr; ?> Bids</mark></h4>
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
                $s1 = "select id from " . $wpdb->prefix . "penny_bids where uid=" . $user_name_id . " order by id desc ";
                $s = "select * from " . $wpdb->prefix . "penny_bids where uid=" . $user_name_id . " order by id desc ";

                $r = $wpdb->get_results($s1);

                $nr = count($r);

                if ($nr > 0) :
                    ?>
                    <div class="form-group col-lg-12 col-md-12 col-sm-12">
                        <h4>Total used bids by <b><?php echo $user_s; ?></b>: <mark><?php echo $nr; ?> Bids</mark></h4>
                    </div>
                    <?php
                endif;
            else:
                ?>
                <div class="form-group col-lg-12 col-md-12 col-sm-12">
                    <h4>Global results:</h4>
                </div>
                <?php

                $s1 = "select id from " . $wpdb->prefix . "penny_bids order by id desc ";
                $s = "select * from " . $wpdb->prefix . "penny_bids order by id desc ";

                $r = $wpdb->get_results($s1);

                $nr = count($r);

                if ($nr > 0) :
                    ?>
                    <div class="form-group col-lg-12 col-md-12 col-sm-12">
                        <h4>Total used bids: <mark><?php echo $nr; ?> Bids</mark></h4>
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
                        <th>Auction</th>
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
                                <?php echo get_the_title($row->pid); ?>
                            </td>
                            <td>
                                1 Bid
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
                            <a href="<?php echo get_bloginfo('siteurl'); ?>/wp-admin/admin.php?page=bids-stats&pj=<?php echo $i; ?>"><?php echo $i; ?></a>
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

                var validation=jQuery('.form_errors');

                jQuery('.set_date').pickadate({
                    format: 'dd/mm/yyyy',
                    max: true,
                });
                jQuery(document).on('click', '.clear', function () {
                    jQuery('.from_2').val("");
                    jQuery('.to_2').val("");
                    location.href = "/wp-admin/admin.php?page=bids-stats";
                });

                function from_validation(form) {
                    if(form==="from_to_form"){
                        var from = jQuery('.from_2').val();
                        var to = jQuery('.to_2').val();
                        if (from && to) {
                            return true;
                        }
                    }else if(form==="user_form"){
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