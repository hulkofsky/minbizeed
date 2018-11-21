<?php
/*
 * Template Name: MBZ Profile
 *
 */
if (!is_user_logged_in()) {
    wp_redirect('/');
    exit;
}
get_header();
$current_user = wp_get_current_user();
$user_login = $current_user->user_login;
$user_id = $current_user->ID;
$user_email = $current_user->user_email;
$user_fname = $current_user->first_name;
$user_lname = $current_user->last_name;
$user_credits = get_user_meta($user_id, 'user_credits', true);
$user_phone = get_user_meta($user_id, '_phonenumber_', true);
$user_country = get_user_meta($user_id, '_country_', true);
$user_city = get_user_meta($user_id, '_city_', true);
$user_state = get_user_meta($user_id, '_state_', true);
$user_address = get_user_meta($user_id, '_address_', true);
?>
    <div class="profile_page">
        <div class="container">
            <div class="ajax_loader_cont" style="display: block">
                <img alt="Loader" class="ajax_loader"
                     src="<?php echo get_template_directory_uri(); ?>/images/ajax_loader.gif"/>
            </div>

            <?php
            if ($_GET['bids_msg'] == "sig_error") {
                ?>
                <div class="alert alert-danger">
                    Something went wrong before sending the payment to the bank, please try paying for the item again.
                </div>
                <?php
            }
            ?>

            <div class="col-lg-12 col-md-12 col-sm12 col-xs-12 personal_info_wrapper to_show" style="display: none;">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 personal_left_part">
                    <div class="name_wrapper">
                        <h1><?php echo $user_login; ?></h1>
                        <span>Your<br>MBZ Name</span>
                        <div class="clear"></div>
                    </div>
                    <div class='country_wrapper'>

                        <?php
                        if ($user_country) {
                            ?>
                            <h3>
                                <span class="bfh-countries f1" data-country="<?php echo $user_country; ?>"
                                      data-flags="true"></span>
                                <span class="bfh-countries f2" data-country="<?php echo $user_country; ?>"
                                      data-flags="true"></span>
                            </h3>
                            <?php
                        }
                        ?>
                        <span><a href="#" class="change_country">edit</a></span>
                        <div class="clear"></div>
                    </div>
                    <!--                    <div class="prizes_wrapper">-->
                    <!--                        <div class='bades_wrap'>-->
                    <!--                            <img class="prize_badge" alt="winner_badge"-->
                    <!--                                 src="-->
                    <?php //echo get_template_directory_uri(); ?><!--/images/badge_green1.png">-->
                    <!--                            <img class="prize_badge" alt="winner_badge"-->
                    <!--                                 src="-->
                    <?php //echo get_template_directory_uri(); ?><!--/images/badge_grey1.png">-->
                    <!--                            <img class="prize_badge" alt="winner_badge"-->
                    <!--                                 src="-->
                    <?php //echo get_template_directory_uri(); ?><!--/images/badge_grey1.png">-->
                    <!--                            <img class="prize_badge" alt="winner_badge"-->
                    <!--                                 src="-->
                    <?php //echo get_template_directory_uri(); ?><!--/images/badge_grey1.png">-->
                    <!--                        </div>-->
                    <!--                        <h5>Win 4 auctions<br>and get <span>100 FREE BIDS</span></h5>-->
                    <!--                        <div class="clear"></div>-->
                    <!--                    </div>-->
                </div>
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 personal_right_part">
                    <div class="image_holder">
                        <!--<img class="profile_bg" alt="bids" src="images/profile_bg.png">-->
                        <div class="image_wrap">
                            <div class="wrapper_one">
                                <?php
                                $gravatar_img = get_gravatar_url($user_email);

                                if ($gravatar_img!="http://gravatar.com/avatar/75bcf051619c09dfa394e873dd00b849") {

                                    ?>
                                    <img class="profile_image" alt="Gravatar image" src="<?php echo $gravatar_img; ?>" />
                                    <?php

                                } else {
                                    $profile_id = get_user_meta($user_id, 'mb_272727023023_user_avatar')[0];
                                    if ($profile_id) {
                                        ?>
                                        <img class="profile_image" alt="<?php echo $user_login; ?>_image"
                                             src="<?php echo wp_get_attachment_image_src($profile_id, 'users_profile')[0]; ?>">
                                        <?php
                                    } else {
                                        ?>
                                        <img class="profile_image" alt="<?php echo $user_login; ?>_image"
                                             src="<?php echo get_template_directory_uri(); ?>/images/default-avatar.png">
                                        <?php
                                    }
                                }
                                ?>
                                <div class="gr_bg">
                                    <a href="#" title="Change profile picture" class="upload_img">
                                        <i class="fa fa-camera" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="bids_info_wrapper">
                                <div class="inner_wrap">
                                    <span class='balance_label'>Your<br>Balance</span>
                                    <h1>
                                        <?php
                                        echo($user_credits ? $user_credits : "0");
                                        ?>
                                        <span>BIDS</span>
                                    </h1>
                                    <img class="bids_img" alt="bids"
                                         src="<?php echo get_template_directory_uri(); ?>/images/bids/profile_bids.png">
                                </div>
                            </div>
                        </div>
                        <div class="buy_bids_wrapper">
                            <a class='buy_bids_now buy_bids_header' href='#'><span>BUY NOW</span></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 image_uploader"
                     style="display: none">
                    <?php
                    echo do_shortcode('[avatar_upload]');
                    ?>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 personal_change_fields">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 personal_info_identifier">
                    <h1>PERSONAL INFO<span>Your Personal Info <br> make sure to enter a valid email</span></h1>
                    <input class="input_fields info_change_fname" placeholder="First Name"
                           type="text" <?php if ($user_fname): echo "value='$user_fname'"; endif; ?>>
                    <input class="input_fields info_change_lname" placeholder="Last Name"
                           type="text" <?php if ($user_lname): echo "value='$user_lname'"; endif; ?>>
                    <input class="input_fields info_change_email" placeholder="E-mail*" required="required"
                           type="email" <?php if ($user_email): echo "value='$user_email'"; endif; ?>>
                    <input class="input_fields info_change_phonenumber" placeholder="Phone Number"
                           type="text" <?php if ($user_phone): echo "value='$user_phone'"; endif; ?>>
                    <div class="submit_wrapper">
                        <?php
                        $ajax_nonce_0 = wp_create_nonce("update_personal_info");
                        $ajax_link_0 = admin_url('admin-ajax.php?action=update_personal_info');
                        ?>
                        <img class="ajax_loader" style="display: none;"
                             src="<?php echo get_template_directory_uri(); ?>/images/ajax_loader.gif"
                             alt="Loader"/>
                        <a href="#" class="submit_button info_change" data-link="<?php echo $ajax_link_0; ?>"
                           data-nonce="<?php echo $ajax_nonce_0; ?>">SAVE</a>
                        <div class="alert alert-success custom_alerts_personal_info">
                            <p class="return_msg"></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 password_change_identifier"
                     id="password_change_identifier">
                    <h1>CHANGE PASSWORD<span>Your MBZ Password. <br> Be Creative</span></h1>
                    <input class="input_fields pw_org pw_fields" placeholder="Old Password" type="password">
                    <input class="input_fields pw_1 pw_fields" placeholder="New Password" type="password">
                    <input class="input_fields pw_2 pw_fields" placeholder="Repeat New Password" type="password">
                    <div class="submit_wrapper">
                        <?php
                        $ajax_nonce_1 = wp_create_nonce("update_pw");
                        $ajax_link_1 = admin_url('admin-ajax.php?action=update_pw');
                        ?>
                        <img class="ajax_loader" style="display: none;"
                             src="<?php echo get_template_directory_uri(); ?>/images/ajax_loader.gif"
                             alt="Loader"/>
                        <a class="submit_button pw_change" data-link="<?php echo $ajax_link_1; ?>"
                           data-nonce="<?php echo $ajax_nonce_1; ?>">CHANGE</a>
                        <div class="alert alert-success custom_alerts_pw">
                            <p class="return_msg"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 auctions_history'>
                <div class='page_identifier'>
                    <h1>AUCTIONS HISTORY<span>Your bidding <br> history</span></h1>
                </div>

                <ul class="nav nav-tabs items_menu">
                    <li class="active">
                        <a href="#won-items" data-toggle="tab"> Won Items </a>
                    </li>
                    <li>
                        <a href="#not-shipped-items" data-toggle="tab"> Not Shipped Items </a>
                    </li>
                    <li>
                        <a href="#shipped-items" data-toggle="tab"> Shipped Items </a>
                    </li>
                </ul>

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 all_auctions_wrapper" id="trophy_room">
                    <?php

                    if ($_GET['msg'] == "sig_error") {
                        ?>
                        <div class="alert alert-danger">
                            Something went wrong before sending the payment to the bank, please try paying for the item
                            again.
                        </div>
                        <?php
                    }
                    ?>
                    <div class="tab-content">
                        <div id="won-items" class="tab-pane fade in active">
                            <?php
                            $winner_0 = array(
                                'key' => 'winner',
                                'value' => $user_id,
                                'compare' => '='
                            );

                            $paid_0 = array(
                                'key' => 'winner_paid',
                                'value' => "0",
                                'compare' => '='
                            );

                            $args_0 = array(
                                'post_type' => 'auction',
                                'order' => 'DESC',
                                'orderby' => 'meta_value_num',
                                'meta_key' => 'closed_date',
                                'posts_per_page' => 15,
                                'meta_query' => array($winner_0, $paid_0)
                            );

                            $query_0 = new WP_Query($args_0);
                            $i = 0;
                            if ($query_0->have_posts()):
                                while ($query_0->have_posts()):
                                    $query_0->the_post();

                                    minbizeed_get_closed_auctions(get_the_ID(), 1);

                                    $i++;
                                endwhile;
                            else:
                                ?>
                                <p class="no_content">No won auctions yet</p>
                                <?php
                            endif;
                            ?>
                        </div>

                        <div id="not-shipped-items" class="tab-pane fade in">
                            <?php
                            $winner_2 = array(
                                'key' => 'winner',
                                'value' => $user_id,
                                'compare' => '='
                            );

                            $paid_2 = array(
                                'key' => 'winner_paid',
                                'value' => "1",
                                'compare' => '='
                            );


                            $shipped_2 = array(
                                'key' => 'shipped',
                                'value' => "0",
                                'compare' => '='
                            );


                            $args_2 = array(
                                'post_type' => 'auction',
                                'order' => 'DESC',
                                'orderby' => 'meta_value_num',
                                'meta_key' => 'closed_date',
                                'posts_per_page' => 15,
                                'meta_query' => array($winner_2, $paid_2, $shipped_2)
                            );
                            $query_2 = new WP_Query($args_2);
                            $i = 0;
                            if ($query_2->have_posts()):
                                while ($query_2->have_posts()):
                                    $query_2->the_post();

                                    minbizeed_get_closed_auctions(get_the_ID());

                                    $i++;
                                endwhile;
                            else:
                                ?>
                                <p class="no_content">No not shipped items yet</p>
                                <?php
                            endif;
                            ?>
                        </div>

                        <div id="shipped-items" class="tab-pane fade in">
                            <?php
                            $winner = array(
                                'key' => 'winner',
                                'value' => $user_id,
                                'compare' => '='
                            );

                            $paid = array(
                                'key' => 'winner_paid',
                                'value' => "1",
                                'compare' => '='
                            );


                            $shipped = array(
                                'key' => 'shipped',
                                'value' => "1",
                                'compare' => '='
                            );

                            $args = array(
                                'post_type' => 'auction',
                                'order' => 'DESC',
                                'orderby' => 'meta_value_num',
                                'meta_key' => 'closed_date',
                                'posts_per_page' => 15,
                                'meta_query' => array($winner, $paid, $shipped)
                            );

                            $query = new WP_Query($args);
                            $i = 0;
                            if ($query->have_posts()):
                                while ($query->have_posts()):
                                    $query->the_post();

                                    minbizeed_get_closed_auctions(get_the_ID(), 0);

                                    $i++;
                                endwhile;
                            else:
                                ?>
                                <p class="no_content">No shipped items yet</p>
                                <?php
                            endif;
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="page_overlay"></div>
    </div>
    <div class="profile_inside">
        <div class="edit_country_pp">
            <h2>Update your address details</h2>
            <div class="form-group">
                <input class="input_fields info_state form-control" placeholder="State*" type="text"
                       value="<?php if ($user_state): echo $user_state; endif; ?>">
            </div>
            <div class="form-group">
                <input class="input_fields info_city form-control" placeholder="City*" type="text"
                       value="<?php if ($user_city): echo $user_city; endif; ?>">
            </div>
            <div class="form-group">
                <textarea class="input_fields info_address form-control" placeholder="Address*"
                          type="text"><?php if ($user_address): echo $user_address; endif; ?></textarea>
            </div>
            <div class="form-group">
                <div class="bfh-selectbox bfh-countries" data-country="<?php if ($user_country): echo "$user_country";
                else: echo "LB"; endif; ?>" data-flags="true">
                    <input type="hidden" value="">
                    <a class="bfh-selectbox-toggle" role="button" data-toggle="bfh-selectbox" href="#">
                        <span class="bfh-selectbox-option input-medium" data-option=""></span>
                        <b class="caret"></b>
                    </a>

                    <div class="bfh-selectbox-options">
                        <input type="text" class="bfh-selectbox-filter">
                        <div role="listbox">
                            <ul role="option">
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <?php
                $ajax_nonce = wp_create_nonce("update_country");
                $ajax_link = admin_url('admin-ajax.php?action=update_country');
                ?>
                <a href="#" data-link="<?php echo $ajax_link; ?>" data-nonce="<?php echo $ajax_nonce; ?>"
                   class="update_country btn btn-primary">Submit</a>
                <img class="ajax_loader" style="display: none;"
                     src="<?php echo get_template_directory_uri(); ?>/images/ajax_loader.gif"
                     alt="Loader"/>
                <p class="return_msg"></p>
            </div>
        </div>
    </div>
<?php
if (isset($_GET['updated']) && $_GET['updated'] == 1):
    ?>
    <div class="connection_status_notice success profile_notice">
        <p>Your profile picture was successfully updated <i class="fa fa-check"></i></p>
    </div>
    <script type="text/javascript">
        jQuery('.profile_notice').slideDown();
        setTimeout(function () {
            jQuery('.profile_notice').slideUp();
        }, 5000);
    </script>
    <?php
elseif (isset($_GET['updated']) && $_GET['updated'] == 0):
    ?>
    <div class="connection_status_notice error profile_notice">
        <p>An error occured while updating your profile picture, please <a href="#" class="reload">refresh</a> and try
            again <i class="fa fa-exclamation"></i></p>
    </div>
    <script type="text/javascript">
        jQuery('.connection_status_notice').slideDown();
        setTimeout(function () {
            jQuery('.connection_status_notice').slideUp();
        }, 5000);
    </script>
    <?php
endif;
?>

    <script type="text/javascript">
        jQuery(window).on('load', function () {
            var c_parent = $('.personal_left_part .country_wrapper h3');
            var f1 = c_parent.find('.f1');
            var f2 = c_parent.find('.f2');

            var f1_node = $('.personal_left_part .country_wrapper .f1')[0].childNodes;
            var f2_node = $('.personal_left_part .country_wrapper .f2')[0].childNodes;

            f1_node[0].remove();
            c_parent.append(f1.html());
            f1.remove();

            f2_node[f2_node.length - 1].remove();

            $('.wpua-edit-container #wpua-remove-button-existing button').addClass('btn btn-danger');
            $('.wpua-edit-container #wpua-add-button-existing button').addClass('btn btn-info');
            $('.wpua-edit-container #wpua-file-existing').addClass('btn');
            $('.wpua-edit-container #wpua-upload-existing').addClass('btn');
            $('.wpua-edit .submit input').addClass('btn');

            $('.wpua-edit-container #wpua-edit-attachment-existing').remove();


            $('.wpua-edit-container > h3').html('Edit your profile picture');

            $('.ajax_loader_cont').slideUp();
            $('.to_show').slideDown();
        });

        jQuery(document).ready(function () {

            var pw_fields = $('.pw_fields');

            var img_wrapper = $('.image_holder .wrapper_one');

            $(function () {
                var hash = window.location.hash;
                hash && $('ul.nav a[href="' + hash + '"]').tab('show');

                $('.nav-tabs a').click(function (e) {
                    $(this).tab('show');
                    var scrollmem = $('body').scrollTop() || $('html').scrollTop();
                    window.location.hash = this.hash;
                    $('html,body').scrollTop(scrollmem);
                })
            });

            img_wrapper.mouseenter(function () {
                img_wrapper.find('.gr_bg').fadeIn('fast');
            });

            img_wrapper.mouseleave(function () {
                img_wrapper.find('.gr_bg').fadeOut('fast');
            });

            $(document).on('click', '.gr_bg .upload_img', function (e) {
                e.preventDefault();

                $('.image_uploader').slideDown('fast');

                $('html, body').animate({
                    scrollTop: $(".image_uploader").offset().top
                }, 750);

            });

            $(document).on('click', '.change_country', function (e) {
                e.preventDefault();

                $.magnificPopup.open({
                    items: {
                        src: '.edit_country_pp'
                    },
                    type: 'inline',
                    closeOnBgClick: false,
                    closeMarkup: '<button title="Close (Esc)" type="button" class="mfp-close fa fa-close"></button>',
                });

            });

            $('.edit_country_pp .update_country').click(function (e) {
                e.preventDefault();
                var ajax_link = $(this).attr('data-link');
                var update_country_nonce = $(this).attr('data-nonce');

                var country = $('.edit_country_pp .bfh-countries input').val();
                var state = $('.edit_country_pp .info_state').val();
                var city = $('.edit_country_pp .info_city').val();
                var address = $('.edit_country_pp .info_address').val();

                var return_msg = jQuery('.edit_country_pp .return_msg');

                var loader = jQuery('.edit_country_pp .ajax_loader');

                loader.slideDown();

                if (ajax_link && update_country_nonce && country && state && city && address) {
                    $.ajax({
                        type: "post",
                        dataType: "json",
                        url: ajax_link,
                        data: {
                            update_country_nonce: update_country_nonce,
                            update_country_c: country,
                            update_state_c: state,
                            update_city_c: city,
                            update_address_c: address
                        },
                        success: function (response) {
                            if (response.type == "success") {

                                loader.slideUp();

                                return_msg.addClass('success');
                                return_msg.html('Address details updated!');

                                setTimeout(function () {
                                    window.location.href = "/profile";
                                }, 2000);

                                return_msg.slideDown();

                            } else if (response.type == "error") {

                                loader.slideUp();
                                return_msg.addClass('error');
                                return_msg.html('No info were changed');
                                return_msg.slideDown();

                            } else {
                                loader.slideUp();
                                return_msg.addClass('error');
                                return_msg.html('An error occured, please refresh and try again');
                                return_msg.slideDown();
                            }
                        }
                    });
                } else {
                    loader.slideUp();
                    return_msg.addClass('error');
                    return_msg.html('All fields are required');
                    return_msg.slideDown();
                }
            });

            $('.personal_info_identifier .info_change').click(function (e) {
                e.preventDefault();
                var ajax_link = $(this).attr('data-link');
                var update_personal_info_nonce = $(this).attr('data-nonce');

                var fname = $('.personal_info_identifier .info_change_fname').val();
                var lname = $('.personal_info_identifier .info_change_lname').val();
                var email = $('.personal_info_identifier .info_change_email').val();
                var phone = $('.personal_info_identifier .info_change_phonenumber').val();

                var return_msg = jQuery('.custom_alerts_personal_info');
                return_msg.slideUp();
                return_msg.removeClass('alert-danger');
                return_msg.removeClass('alert-success');

                var loader = jQuery('.personal_info_identifier .ajax_loader');

                loader.slideDown();

                if (ajax_link && update_personal_info_nonce && email) {
                    $.ajax({
                        type: "post",
                        dataType: "json",
                        url: ajax_link,
                        data: {
                            update_personal_info_nonce: update_personal_info_nonce,
                            fname: fname,
                            lname: lname,
                            email: email,
                            phone: phone
                        },
                        success: function (response) {
                            if (response.type == "success") {

                                loader.slideUp();

                                return_msg.addClass('alert-success');
                                return_msg.html('Address details updated!');

                                return_msg.slideDown();

                            } else if (response.type == "error") {

                                loader.slideUp();
                                return_msg.addClass('alert-danger');
                                return_msg.html('No info were changed');
                                return_msg.slideDown();

                            } else if (response.type == "email_error") {

                                loader.slideUp();
                                return_msg.addClass('alert-danger');
                                return_msg.html('The email you entered is not valid');
                                return_msg.slideDown();

                            } else if (response.type == "same_error") {

                                loader.slideUp();
                                return_msg.addClass('alert-danger');
                                return_msg.html('No info were changed');
                                return_msg.slideDown();

                            } else {
                                loader.slideUp();
                                return_msg.addClass('alert-danger');
                                return_msg.html('An error occured, please refresh and try again');
                                return_msg.slideDown();
                            }

                        }
                    });
                } else {
                    loader.slideUp();
                    return_msg.addClass('alert-danger');
                    return_msg.html('Email field is required');
                    return_msg.slideDown();
                }
            });


            $('.password_change_identifier .pw_change').click(function (e) {
                e.preventDefault();
                var ajax_link = $(this).attr('data-link');
                var update_pw_nonce = $(this).attr('data-nonce');

                var pw_org = $('.password_change_identifier .pw_org').val();
                var pw_1 = $('.password_change_identifier .pw_1').val();
                var pw_2 = $('.password_change_identifier .pw_2').val();

                var return_msg = jQuery('.custom_alerts_pw');
                return_msg.slideUp();
                return_msg.removeClass('alert-danger');
                return_msg.removeClass('alert-success');

                var loader = jQuery('.password_change_identifier .ajax_loader');

                loader.slideDown();

                if (ajax_link && update_pw_nonce && pw_org && pw_1 && pw_2) {
                    $.ajax({
                        type: "post",
                        dataType: "json",
                        url: ajax_link,
                        data: {
                            update_pw_nonce: update_pw_nonce,
                            pw_org: pw_org,
                            pw_1: pw_1,
                            pw_2: pw_2
                        },
                        success: function (response) {
                            if (response.type == "success") {

                                loader.slideUp();

                                return_msg.addClass('alert-success');
                                return_msg.html('Password successfully changed!<br> You will be redirected to login again');
                                pw_fields.val('');
                                return_msg.slideDown();

                                setTimeout(function () {
                                    window.location.href = "/login";
                                }, 3000);


                            } else if (response.type == "enter_error") {

                                loader.slideUp();
                                return_msg.addClass('alert-danger');
                                return_msg.html('All fields are required');
                                pw_fields.val('');
                                return_msg.slideDown();

                            } else if (response.type == "access_error") {

                                loader.slideUp();
                                return_msg.addClass('alert-danger');
                                return_msg.html('Your old password is incorrect');
                                pw_fields.val('');
                                return_msg.slideDown();

                            } else if (response.type == "match_error") {

                                loader.slideUp();
                                return_msg.addClass('alert-danger');
                                return_msg.html('Passwords does not match');
                                pw_fields.val('');
                                return_msg.slideDown();

                            } else if (response.type == "length_error") {

                                loader.slideUp();
                                return_msg.addClass('alert-danger');
                                return_msg.html('Min password length: 6 characters');
                                pw_fields.val('');
                                return_msg.slideDown();

                            } else {
                                loader.slideUp();
                                return_msg.addClass('alert-danger');
                                return_msg.html('An error occured, please refresh and try again');
                                pw_fields.val('');
                                return_msg.slideDown();
                            }

                        }
                    });
                } else {
                    loader.slideUp();
                    return_msg.addClass('alert-danger');
                    return_msg.html('All fields are required');
                    pw_fields.val('');
                    return_msg.slideDown();
                }
            });
        });
    </script>
<?php
get_footer();
