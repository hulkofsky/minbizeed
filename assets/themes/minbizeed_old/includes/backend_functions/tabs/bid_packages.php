<?php
function minbizeed_bid_packages()
{
    ?>

    <div class="admin_stats container">
        <h3>Define A New Package</h3>
        <div class="my_pkg_cell_new multisections" id="my_pkg_cell_new">

            <div class="form-group">
                <label>Package Name</label>
                <input id="new_package_name" class="form-control" type="text" value=""/>
            </div>

            <div class="form-group">
                <label>Package cost</label>
                <input id="new_package_cost" class="form-control" type="text" value=""/>
            </div>

            <div class="form-group">
                <label>Package bids</label>
                <input id="new_package_bid" class="form-control" type="text" value=""/>
            </div>

            <div class="form-group">
                <?php
                $create_package_nonce = wp_create_nonce("minbizeed_new_package");
                $create_package_link = admin_url('admin-ajax.php?action=minbizeed_new_package');
                ?>
                <a href="#"
                   id="new_package_action"
                   rel=""
                   data-nonce="<?php echo $create_package_nonce; ?>"
                   data-ajax_url="<?php echo $create_package_link; ?>"
                   class="btn btn-success">Add New Package</a>
            </div>

            <div class="form-group ajax_loader">
                <img src="<?php echo get_template_directory_uri(); ?>/images/ajax_loader.gif" alt="loader"/>
            </div>

            <div class="form-group return_message">
                <p></p>
            </div>

        </div>

        <h3>Current Defined Packages</h3>
        <div id="my_packages_stuff">

            <?php
            global $wpdb;
            $s = "select * from " . $wpdb->prefix . "penny_packages order by cost asc";
            $r = $wpdb->get_results($s);

            foreach ($r as $row) {

                $update_package_nonce = wp_create_nonce("minbizeed_update_package");
                $update_package_link = admin_url('admin-ajax.php?action=minbizeed_update_package');

                $delete_package_nonce = wp_create_nonce("minbizeed_delete_package");
                $delete_package_link = admin_url('admin-ajax.php?action=minbizeed_delete_package');

                ?>
                <div class="col-lg-4 col-md-6 col-sm-12 packages" id="my_pkg_cell<?php echo $row->id; ?>">

                    <div class="form-group">
                        <label>Package Name</label>
                        <input type="text" name="new_package_name_cell<?php echo $row->id; ?>"
                               id="new_package_name_cell<?php echo $row->id; ?>"
                               class="form-control"
                               value="<?php echo $row->package_name; ?>"/>
                    </div>

                    <div class="form-group">
                        <label>Package cost</label>
                        <input type="number" name="new_package_cost_cell<?php echo $row->id; ?>"
                               id="new_package_cost_cell<?php echo $row->id; ?>"
                               class="form-control"
                               value="<?php echo $row->cost; ?>"/>
                    </div>

                    <div class="form-group">
                        <label>Package bids</label>
                        <input name="new_package_bid_cell<?php echo $row->id; ?>" type="number"
                               id="new_package_bid_cell<?php echo $row->id; ?>"
                               class="form-control"
                               value="<?php echo $row->bids; ?>"/>
                    </div>

                    <div class="form-group ajax_btns">
                        <a href="#" rel="<?php echo $row->id; ?>" data-nonce="<?php echo $update_package_nonce; ?>"
                           data-ajax_url="<?php echo $update_package_link; ?>" class="update_package btn btn-success">
                            Update Package
                        </a>
                        <a href="#" rel="<?php echo $row->id; ?>"
                           data-nonce="<?php echo $delete_package_nonce; ?>"
                           data-ajax_url="<?php echo $delete_package_link; ?>"
                           class="delete_package btn btn-danger">Delete
                            Package</a>
                    </div>

                    <div class="form-group ajax_loader">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/ajax_loader.gif" alt="loader"/>
                    </div>

                    <div class="form-group return_message">
                        <p></p>
                    </div>

                </div>

                <?php
            }
            ?>
        </div>
    </div>

    <?php
}

add_action('wp_ajax_minbizeed_update_package', 'minbizeed_update_package');
function minbizeed_update_package()
{

    if (!wp_verify_nonce($_REQUEST['nonce'], "minbizeed_update_package")) {
        exit("You think you are smart?");
    }

    if ($_POST['action'] == "minbizeed_update_package") {
        $new_package_name_cell = $_POST['new_package_name_cell'];
        $new_package_cost_cell = $_POST['new_package_cost_cell'];
        $new_package_bid_cell = $_POST['new_package_bid_cell'];
        $id = $_POST['id'];

        global $wpdb;

        if ($new_package_name_cell && $new_package_cost_cell && $new_package_bid_cell) {
            $s = "update " . $wpdb->prefix . "penny_packages set package_name='$new_package_name_cell', bids='$new_package_bid_cell'
		, cost='$new_package_cost_cell' where id='$id'";

            $q = $wpdb->query($s);

            if ($q) {
                $result['type'] = "success";
            } else {
                $result['type'] = "no_changes";
            }
        } else {
            $result['type'] = "data_error";
        }

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $result = json_encode($result);
            echo $result;
        } else {
            header("Location: " . $_SERVER["HTTP_REFERER"]);
        }
        wp_die();
    } else {
        exit("You think you are smart?");
    }
}

add_action('wp_ajax_minbizeed_delete_package', 'minbizeed_delete_package');
function minbizeed_delete_package()
{


    if (!wp_verify_nonce($_REQUEST['nonce'], "minbizeed_delete_package")) {
        exit("You think you are smart?");
    }

    if ($_POST['action'] == "minbizeed_delete_package") {


        $id = $_POST['id'];

        global $wpdb;

        if ($id) {
            $s = "delete from " . $wpdb->prefix . "penny_packages where id='$id'";

            $q = $wpdb->query($s);

            if ($q) {
                $result['type'] = "success";
            } else {
                $result['type'] = "error";
            }
        } else {
            $result['type'] = "error";
        }

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $result = json_encode($result);
            echo $result;
        } else {
            header("Location: " . $_SERVER["HTTP_REFERER"]);
        }
        wp_die();
    } else {
        exit("You think you are smart?");
    }
}


add_action('wp_ajax_minbizeed_new_package', 'minbizeed_new_package');
function minbizeed_new_package()
{

    if (!wp_verify_nonce($_REQUEST['nonce'], "minbizeed_new_package")) {
        exit("You think you are smart?");
    }

    if ($_POST['action'] == "minbizeed_new_package") {

        $new_package_name = $_POST['new_package_name'];
        $new_package_cost = $_POST['new_package_cost'];
        $new_package_bid = $_POST['new_package_bid'];

        global $wpdb;

        if ($new_package_name && $new_package_cost && $new_package_bid) {
            $s = "insert into " . $wpdb->prefix . "penny_packages (package_name, cost, bids) values('$new_package_name', '$new_package_cost', '$new_package_bid')";
            $q = $wpdb->query($s);

            if ($q) {

                $s = "select id from " . $wpdb->prefix . "penny_packages where package_name='$new_package_name' and cost='$new_package_cost' and bids='$new_package_bid'";
                $r = $wpdb->get_results($s);
                $row = $r[0];

                if ($row) {

                    $result['type'] = "success";

                    $result['new_package_name'] = $new_package_name;
                    $result['new_package_cost'] = $new_package_cost;
                    $result['new_package_bid'] = $new_package_bid;
                    $result['id'] = $row->id;

                } else {
                    $result['type'] = "error";
                }
            } else {
                $result['type'] = "error";
            }
        } else {
            $result['type'] = "error";
        }

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $result = json_encode($result);
            echo $result;
        } else {
            header("Location: " . $_SERVER["HTTP_REFERER"]);
        }
        wp_die();
    } else {
        exit("You think you are smart?");
    }
}