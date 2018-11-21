<?php

function minbizeed_display_my_account_pers_inf_fncs()
{

    ob_start();

    global $current_user;
    get_currentuserinfo();
    $uid = $current_user->ID;
    ?>

    <div id="content">
        <!-- page content here -->


        <div class="my_box3">
            <div class="padd10">


                <div class="box_content">
                    <?php
                    if (isset($_POST['save-info'])) {
                        $personal_info = strip_tags(nl2br($_POST['personal_info']), '<br />');
                        update_user_meta($uid, 'personal_info', $personal_info);

                        $ship_inf = strip_tags(nl2br($_POST['ship_inf']), '<br />');
                        update_user_meta($uid, 'ship_inf', $ship_inf);

                        update_user_meta($uid, 'phone', trim($_POST['phone']));
                        update_user_meta($uid, 'first_name', trim($_POST['first_name']));
                        update_user_meta($uid, 'last_name', trim($_POST['last_name']));
                        update_user_meta($uid, 'state', trim($_POST['state']));
                        update_user_meta($uid, 'city', trim($_POST['city']));


                        update_user_meta($uid, 'zip_code', trim($_POST['zip_code']));
                        update_user_meta($uid, 'country', trim($_POST['country']));


                        if (isset($_POST['password']) && !empty($_POST['password'])) {
                            $p1 = trim($_POST['password']);
                            $p2 = trim($_POST['reppassword']);

                            if ($p1 == $p2) {
                                global $wpdb;
                                $newp = md5($p1);
                                $sq = "update " . $wpdb->prefix . "users set user_pass='$newp' where ID='$uid'";
                                $wpdb->query($sq);
                            } else
                                echo __("Passwords do not match!", "ClassifiedTheme");
                        }


                        //$personal_info = trim($_POST['paypal_email']);
                        //update_user_meta($uid, 'paypal_email', $personal_info);

                        if (!empty($_FILES['avatar']["tmp_name"])) {
                            $avatar = $_FILES['avatar'];

                            $tmp_name = $avatar["tmp_name"];
                            $name = $avatar["name"];

                            $upldir = wp_upload_dir();
                            $path = $upldir['path'];
                            $url = $upldir['url'];

                            $name = str_replace(" ", "", $name);
                            if (getimagesize($tmp_name) > 0) {

                                move_uploaded_file($tmp_name, $path . "/" . $name);
                                update_user_meta($uid, 'avatar', $url . "/" . $name);
                            }
                        }

                        echo '<div class="auction-saved"><div class="padd10">' . __('Your profile information was updated.', 'minbizeed') . '</div></div>';
                        echo '<div class="clear10"></div>';
                        wp_redirect(get_home_url() . '/my-account');
                    }
                    ?>
                    <form method="post" enctype="multipart/form-data">
                        <h2 class='edit_title'>Edit Your Profile</h2>

                        <div class="p_one col-lg-6 col-md-12">
                            <p class="form_title">Account info (All fields are required)</p>
                            <ul class="post-new3">

                                <li id='first_name'>

                                    <p><input placeholder="First Name" type="text"
                                              value="<?php echo get_user_meta($uid, 'first_name', true); ?>"
                                              class="do_input" name="first_name" size="35"/></p>
                                </li>

                                <li id='last_name'>

                                    <p><input placeholder="Last Name" type="text"
                                              value="<?php echo get_user_meta($uid, 'last_name', true); ?>"
                                              class="do_input"
                                              name="last_name" size="35"/></p>
                                </li>

                                <!--                                    <li id='phone_number'>

                                        <p><input placeholder="Contact Number" type="text" value="<?php echo get_user_meta($uid, 'phone', true); ?>" class="do_input" name="phone" size="35" /></p>
                                    </li>-->

                                <li id='new_password'>

                                    <p><input placeholder="New Password" type="password" value="" class="do_input"
                                              name="password" size="35"/></p>
                                </li>


                                <li id='repaeat_password'>

                                    <p><input placeholder="Repeat Password" type="password" value=""
                                              class="do_input"
                                              name="reppassword" size="35"/></p>
                                </li>

                                <li id='profile_description'>

                                    <p><textarea placeholder="Personal Description" type="textarea" cols="40"
                                                 class="do_input" rows="3"
                                                 name="personal_info"><?php echo get_user_meta($uid, 'personal_info', true); ?></textarea>
                                    </p>
                                </li>
                            </ul>
                        </div>
                        <div class="p_two col-lg-6 col-md-12">
                            <p class="form_title two">Address info</p>
                            <ul>

                                <li id='city'>

                                    <p><input placeholder="City" type="text"
                                              value="<?php echo get_user_meta($uid, 'city', true); ?>"
                                              class="do_input"
                                              name="city" size="35"/></p>
                                </li>

                                <li id='state'>

                                    <p><input placeholder="State" type="text"
                                              value="<?php echo get_user_meta($uid, 'state', true); ?>"
                                              class="do_input"
                                              name="state" size="35"/></p>
                                </li>

                                <li id='country'>

                                    <p><input placeholder="Country" type="text"
                                              value="<?php echo get_user_meta($uid, 'country', true); ?>"
                                              class="do_input"
                                              name="country" size="35"/></p>
                                </li>


                                <li id='zip_code'>

                                    <p><input placeholder="Zip Code" type="text"
                                              value="<?php echo get_user_meta($uid, 'zip_code', true); ?>"
                                              class="do_input"
                                              name="zip_code" size="35"/></p>
                                </li>

                                <li id='street_address'>

                                    <p><textarea placeholder="Address Notes" type="textarea" cols="40"
                                                 class="do_input"
                                                 rows="3"
                                                 name="ship_inf"><?php echo get_user_meta($uid, 'ship_inf', true); ?></textarea>
                                    </p>
                                </li>
                            </ul>
                        </div>
                        <li id='botn'>

                            <p><input id='save_btn' type="submit" name="save-info"
                                      value="<?php _e("Save", 'minbizeed'); ?>"/></p>
                        </li>
                        <li id='hidden'>
                            <h2><?php echo __('Profile Avatar', 'PricerrTheme'); ?>:</h2>

                            <p><input type="file" name="avatar"/> <br/>
                                max file size: 1mb. Formats: jpeg, jpg, png, gif
                                <br/>
                                <img width="50" height="50" border="0"
                                     src="<?php echo minbizeed_get_avatar($uid, 50, 50); ?>"/>
                            </p>
                        </li>
                        </ul>
                    </form>
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


function minbizeed_display_my_account_shp_inf_fncs()
{

    ob_start();

    global $current_user;
    get_currentuserinfo();
    $uid = $current_user->ID;
    ?>

    <div id="content">
        <!-- page content here -->


        <div class="my_box3">
            <div class="padd10">


                <div class="box_content">
                    <?php
                    if (isset($_POST['save-info'])) {

                        $ship_inf = strip_tags(nl2br($_POST['ship_inf']), '<br />');
                        update_user_meta($uid, 'ship_inf', $ship_inf);

                        update_user_meta($uid, 'state', trim($_POST['state']));
                        update_user_meta($uid, 'city', trim($_POST['city']));


                        update_user_meta($uid, 'zip_code', trim($_POST['zip_code']));
                        update_user_meta($uid, 'country', trim($_POST['country']));

                        echo '<div class="auction-saved"><div class="padd10">' . __('Your profile information was updated.', 'minbizeed') . '</div></div>';
                        echo '<div class="clear10"></div>';
                        wp_redirect(get_home_url() . '/my-account/trophy-room/');
                    }
                    ?>
                    <form method="post" enctype="multipart/form-data" id="update_info_frm">
                        <h2 class='edit_title'>Shipping Info</h2>

                        <div class="p_two col-lg-6 col-md-12">
                            <p class="form_title">Address info (All fields are required)</p>
                            <ul>

                                <li id='city'>

                                    <p><input placeholder="City" type="text"
                                              value="<?php echo get_user_meta($uid, 'city', true); ?>"
                                              class="do_input is_req"
                                              name="city" size="35"/></p>
                                </li>

                                <li id='state'>

                                    <p><input placeholder="State" type="text"
                                              value="<?php echo get_user_meta($uid, 'state', true); ?>"
                                              class="do_input is_req"
                                              name="state" size="35"/></p>
                                </li>

                                <li id='country'>

                                    <p><input placeholder="Country" type="text"
                                              value="<?php echo get_user_meta($uid, 'country', true); ?>"
                                              class="do_input is_req"
                                              name="country" size="35"/></p>
                                </li>


                                <li id='zip_code'>

                                    <p><input placeholder="Zip Code" type="text"
                                              value="<?php echo get_user_meta($uid, 'zip_code', true); ?>"
                                              class="do_input is_req"
                                              name="zip_code" size="35"/></p>
                                </li>

                                <li id='street_address'>

                                    <p><textarea placeholder="Address Notes" type="textarea" cols="40"
                                                 class="do_input is_req"
                                                 rows="3"
                                                 name="ship_inf"><?php echo get_user_meta($uid, 'ship_inf', true); ?></textarea>
                                        <input type="hidden" name="save-info">
                                    </p>
                                </li>
                                <li id='botn'>

                                    <p>
                                        <a href="#" class="submit_info" id="save_btn">Submit</a>
                                    </p>
                                </li>
                            </ul>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script>
            $(".submit_info").click(function (e) {
                e.preventDefault();
                $('.no_val').removeClass('no_val');
                $('.no_val_span').remove();
                var inputs = $('.p_two .is_req');
                var no_valid;
                inputs.each(function () {
                    if ($(this).val() == "") {
                        $(this).addClass('no_val');
                        $(this).parent().append('<span class="no_val_span">This field is required</span>');
                        $(this).parent().find('.no_val_span').slideDown();
                        no_valid = 1;
                    }
                    return no_valid;
                });
                if (no_valid != 1) {
                    $('#update_info_frm').submit();
                }
            });
        </script>


        <!-- page content here -->
    </div>


    <?php
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}

?>