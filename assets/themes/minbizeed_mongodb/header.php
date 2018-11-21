<?php
/*Disable errors start*/
error_reporting(0);
ini_set("display_errors", "off");
/*Disable errors end*/

/*Login encrypted uid session start*/
is_user_logged_in() ? $_SESSION["uid"] = get_current_user_id() : NULL;
$_SESSION["auction"] = is_single() ? get_the_ID() : "global";
if ($_SERVER['REMOTE_ADDR'] != "127.0.0.1") {
    set_encrypted_session_id_cookie();
}
/*Login encrypted uid session end*/

?>
    <!DOCTYPE html>
    <!--[if IE 7]>
    <html class="ie ie7" <?php language_attributes(); ?>>
    <![endif]-->
    <!--[if IE 8]>
    <html class="ie ie8" <?php language_attributes(); ?>>
    <![endif]-->
    <!--[if !(IE 7) & !(IE 8)]><!-->
<html <?php language_attributes(); ?>>
    <!--<![endif]-->
    <head>

        <!-- Adding WP head start -->
        <?php wp_head(); ?>
        <!-- Adding WP head end -->

        <title><?php echo is_front_page() ? get_bloginfo('name') : wp_title('|', 'false', ''); ?></title>

        <!-- Favicon start -->
        <link rel="shortcut icon" href="<?php echo site_url(); ?>/favicon.ico"/>
        <!-- Favicon end -->

        <!--IE fucking sucks start-->

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <!--IE fucking sucks end-->

        <!-- Change browser header color mobile start -->

        <!-- Chrome, Firefox OS, Opera and Vivaldi -->
        <meta name="theme-color" content="#628FF4">
        <!-- Windows Phone -->
        <meta name="msapplication-navbutton-color" content="#628FF4">
        <!-- iOS Safari -->
        <meta name="apple-mobile-web-app-capable" content="yes"/>
        <meta name="apple-mobile-web-app-status-bar-style" content="#628FF4">

        <!-- Change browser header color mobile end -->


        <!-- Charset start -->
        <meta charset="<?php bloginfo('charset'); ?>">
        <!-- Charset end -->

        <!-- Mobile meta start -->
        <meta name="viewport" content="user-scalable=no,width=device-width,initial-scale=1.0,maximum-scale=1.0"/>
        <meta name="format-detection" content="telephone=no">
        <!-- Mobile meta end -->

        <!-- Profile and pingback meta start -->
        <link rel="profile" href="http://gmpg.org/xfn/11">
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
        <!-- Profile and pingback meta end -->

        <!-- Google Analytics Start -->
        <script>
            (function (i, s, o, g, r, a, m) {
                i['GoogleAnalyticsObject'] = r;
                i[r] = i[r] || function () {
                        (i[r].q = i[r].q || []).push(arguments)
                    }, i[r].l = 1 * new Date();
                a = s.createElement(o),
                    m = s.getElementsByTagName(o)[0];
                a.async = 1;
                a.src = g;
                m.parentNode.insertBefore(a, m)
            })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

            ga('create', 'UA-84835524-1', 'auto');
            ga('send', 'pageview');

        </script>
        <!-- Google Analytics End -->

        <!-- SEO meta start -->
        <meta name="description"
              content="MinBiZeed - Auction website">
        <meta name="keywords"
              content="MinBizeed, min bi zeed, Bidding, Penny Bid, Online bidding">
        <!-- SEO meta end -->

        <!-- Social Media Meta variables start -->
        <meta property="og:type" content="Website"/>
        <meta property="og:site_name" content="minbizeed"/>
        <meta property="og:url" content="/"/>
        <meta property="og:image" content="<?php echo get_template_directory_uri(); ?>/images/logo.png"/>
        <meta property="og:title" content="minbizeed"/>
        <meta property="og:description"
              content="Auction website"/>

        <meta name="twitter:card" content="summary_large_image"/>
        <meta name="twitter:title" content="minbizeed"/>
        <meta name="twitter:description"
              content="Auction website"/>
        <meta name="twitter:image" content="<?php echo get_template_directory_uri(); ?>/images/logo.png"/>
        <!-- Social Media Meta variables end -->

        <?php
        $is_single_id = is_single() ? get_the_ID() : "global";
        if ($is_single_id) {
            ?>
            <script type="text/javascript">
                var auction_id = '<?php echo $is_single_id; ?>';
            </script>
            <?php
        }
        ?>
        <!-- Google recaptcha start-->
        <script src='https://www.google.com/recaptcha/api.js'></script>
        <!-- Google recaptcha end-->
    </head>

<body <?php body_class(); ?> >

    <!-- FB like btn start-->
    <div id="fb-root"></div>
    <script>(function (d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s);
            js.id = id;
            js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.11&appId=827159877349093';
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>
    <!-- FB like btn end-->

    <div class="other_notice success">
        <p></p>
    </div>
    <div class="connection_status_notice error">
        <p>You have an unstable internet connection, this may affect your bidding experience <i
                    class="fa fa-exclamation"></i></p>
    </div>
    <div class="connection_status_notice success">
        <p>Your internet connection is stable and working again <i class="fa fa-check"></i></p>
    </div>

<?php
if (is_user_logged_in()):
    $current_user = wp_get_current_user();
    $user_login = $current_user->user_login;
    $user_id = $current_user->ID;
    $user_email = $current_user->user_email;
    $user_credits = get_user_meta($user_id, 'user_credits', true);
    $user_country = get_user_meta($user_id, '_country_', true);
    ?>

    <div class="top_site_section signed_in_header">
        <div class="container top_menu_bar">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 left_top_menu_bar signed_in">
                <h5 class="user_info_but">
                    <?php
                    $gravatar_img = get_gravatar_url($user_email);

                    if ($gravatar_img!="http://gravatar.com/avatar/75bcf051619c09dfa394e873dd00b849") {

                        ?>
                        <img class="user_image" alt="Gravatar image" src="<?php echo $gravatar_img; ?>" />
                        <?php

                    } else {
                        $profile_id = get_user_meta($user_id, 'mb_272727023023_user_avatar')[0];
                        if ($profile_id) {
                            ?>
                            <img class="user_image" alt="<?php echo $user_login; ?>_image"
                                 src="<?php echo wp_get_attachment_image_src($profile_id, 'users_profile')[0]; ?>">
                            <?php
                        } else {
                            ?>
                            <img class="user_image" alt="<?php echo $user_login; ?>_image"
                                 src="<?php echo get_template_directory_uri(); ?>/images/default-avatar.png">
                            <?php
                        }
                    }
                    ?>
                    <span>
                        <a href="#"><?php echo $user_login; ?></a>
                    </span>
                </h5>
                <?php
                if ($user_country) {
                    ?>
                    <span class="pointless">&#9679;</span>
                    <h5 class="user_country_info">
                        <span class="bfh-countries" data-country="<?php echo $user_country; ?>"
                              data-flags="true">
                    </span>
                    </h5>
                    <?php
                }
                ?>
                <span class="pointless">&#9679;</span>
                <h5 class="bids_number">
                    <span class="landing_balance"><?php
                    echo($user_credits ? $user_credits : "0");
                    ?></span>
                    Bids
                </h5>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 right_top_menu_bar">
                <h5>
                    <a href="#" class='buy_bids_header'>
                        <span>
                            <img alt="coins_2"
                                 src="<?php echo get_template_directory_uri(); ?>/images/icons/coins_2.png">
                        </span>
                        Buy Bids
                    </a>
                </h5>
                <span class="pointless">&#9679;</span>
                <h5><a href="/profile">Profile</a></h5>
                <span class="pointless">&#9679;</span>
                <h5><?php echo do_shortcode('[wppb-logout text="" redirect_url="/" link_text="Log Out"]') ?></h5>
                <span class="pointless">&#9679;</span>
                <!--                <h5 class="top_menu_active">-->
                <!--                    <a href="#">-->
                <!--                        <span class="language_span this_active">EN</span></a>-->
                <!--                    /<a href="#"><span class="language_span">AR</span></a>-->
                <!--                </h5>-->
            </div>
        </div>
        <div class="top_identity_banner">
            <a href="/">
                <img alt="top_logo" src="<?php echo get_template_directory_uri(); ?>/images/logo4.png">
            </a>
        </div>
        <div class="container middle_menu_bar">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mobile_button_wrapper">
                <span class='to_open'>MENU</span>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 center_middle_menu_bar dynamic_menu">
                <ul>
                    <li class="active" data-menu="/">
                        <img class="top_arrow" alt="arrow"
                             src="<?php echo get_template_directory_uri(); ?>/images/arrows/top_arrow.png">
                        <a href="/">Live Auctions</a>
                    </li>
                    <li data-menu="/closed-auctions/">
                        <img class="top_arrow" alt="arrow"
                             src="<?php echo get_template_directory_uri(); ?>/images/arrows/top_arrow.png">
                        <a href="/closed-auctions">Closed Auctions</a>
                    </li>
                    <li data-menu="/winners/">
                        <img class="top_arrow" alt="arrow"
                             src="<?php echo get_template_directory_uri(); ?>/images/arrows/top_arrow.png">
                        <a href="/winners">Winners</a>
                    </li>
                    <li class="howitworks_button" data-menu="/how-it-works/">
                        <img class="top_arrow" alt="arrow"
                             src="<?php echo get_template_directory_uri(); ?>/images/arrows/top_arrow_purple.png">
                        <a href="/how-it-works">How it works</a>
                        <img class="bottom_arrow" alt="top_logo"
                             src="<?php echo get_template_directory_uri(); ?>/images/arrows/bottom_arrow.png">
                    </li>
                    <li data-menu="/contact/">
                        <img class="top_arrow" alt="arrow"
                             src="<?php echo get_template_directory_uri(); ?>/images/arrows/top_arrow_purple.png">
                        <a href="/contact">Contact us</a>
                    </li>
                </ul>
                <div class='mobile_menu'>
                    <h5>
                        <a href="#">
                            <span>
                                <img alt="coins_2"
                                     src="<?php echo get_template_directory_uri(); ?>/images/icons/coins_2.png">
                            </span>
                            Buy Bids
                        </a>
                    </h5>
                    <span class="pointless">&#9679;</span>
                    <h5><a href="/profile">Profile</a></h5>
                    <span class="pointless">&#9679;</span>
                    <h5><?php echo do_shortcode('[wppb-logout text="" redirect_url="/" link_text="Log Out"]') ?></h5>
                    <span class="pointless">&#9679;</span>
                    <!--                    <h5 class="top_menu_active">-->
                    <!--                        <a href="#">-->
                    <!--                            <span class="language_span this_active">EN</span></a>-->
                    <!--                        / <a href="#"><span class="language_span">AR</span>-->
                    <!--                        </a>-->
                    <!--                    </h5>-->
                </div>
            </div>
        </div>
    </div>

    <?php
else:
    ?>
    <div class="top_site_section not_signed_in_header">
        <div class="container top_menu_bar">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 left_top_menu_bar">
                <h5>Welcome to MinBiZeed, <span><a class="effect-underline"
                                                   href="/register">REGISTER NOW FOR FREE</a></span></h5>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 right_top_menu_bar">
                <h5><a class='buy_bids_header' href="#"><span><img alt="coins_2"
                                                                   src="<?php echo get_template_directory_uri(); ?>/images/icons/coins_2.png"></span>
                        Buy Bids</a></h5>
                <span class="pointless">&#9679;</span>
                <h5><a href="/login">Log In</a></h5>
                <span class="pointless">&#9679;</span>
                <!--                <h5 class="top_menu_active">-->
                <!--                    <a href="#">-->
                <!--                        <span class="language_span this_active">EN</span></a> /-->
                <!--                    /<a href="#"><span class="language_span">AR</span></a>-->
                <!--                </h5>-->
            </div>
        </div>
        <div class="top_identity_banner">
            <a href="/">
                <img alt="top_logo" src="<?php echo get_template_directory_uri(); ?>/images/logo4.png">
            </a>
        </div>
        <div class="container middle_menu_bar">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mobile_button_wrapper">
                <span class='to_open'>MENU</span>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 center_middle_menu_bar">
                <ul>
                    <li class="active">
                        <img class="top_arrow" alt="arrow"
                             src="<?php echo get_template_directory_uri(); ?>/images/arrows/top_arrow.png">
                        <a href="/">Live Auctions</a></li>
                    <li><img class="top_arrow" alt="arrow"
                             src="<?php echo get_template_directory_uri(); ?>/images/arrows/top_arrow.png"><a
                                href="/closed-auctions">Closed Auctions</a></li>
                    <li><img class="top_arrow" alt="arrow"
                             src="<?php echo get_template_directory_uri(); ?>/images/arrows/top_arrow.png"><a
                                href="/winners">Winners</a>
                    </li>
                    <li class="howitworks_button how_it_works_opened">
                        <img class="top_arrow" alt="arrow"
                             src="<?php echo get_template_directory_uri(); ?>/images/arrows/top_arrow_purple.png"/>
                        <a href="/how-it-works">How it works</a>
                        <img class="bottom_arrow" alt="top_logo"
                             src="<?php echo get_template_directory_uri(); ?>/images/arrows/bottom_arrow.png"/>
                    </li>
                    <li>
                        <img class="top_arrow" alt="arrow"
                             src="<?php echo get_template_directory_uri(); ?>/images/arrows/top_arrow_purple.png">
                        <a href="/contact">Contact us</a>
                    </li>
                </ul>
                <div class='mobile_menu'>
                    <h5>
                        <a href="#">
                            <span>
                                <img alt="coins_2"
                                     src="<?php echo get_template_directory_uri(); ?>/images/icons/coins_2.png">
                            </span>
                            Buy Bids
                        </a>
                    </h5>
                    <span class="pointless">&#9679;</span>
                    <h5><a href="/login">Log In</a></h5>
                    <span class="pointless">&#9679;</span>
                    <!--                    <h5 class="top_menu_active">-->
                    <!--                        <a href="#">-->
                    <!--                            <span class="language_span this_active">EN</span></a> / -->
                    <!--                        <a href="#"><span class="language_span">AR</span></a>-->
                    <!--                    </h5>-->
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 how_it_works_section">
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 how_it_works_wrap">
                        <div class="howitworks_inner_wrap">
                            <h1><a href="/register">REGISTER</a></h1>
                            <h5>FOR FREE</h5>
                            <span>01</span>
                        </div>
                        <span class="bottom_white_arrow">
                            <img alt="arrow"
                                 src="<?php echo get_template_directory_uri(); ?>/images/arrows/top_white_arrow.png">
                        </span>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 how_it_works_wrap">
                        <div class="howitworks_inner_wrap">
                            <h1><a href="#" class="buy_bids_header">BUY BIDS</a></h1>
                            <h5>FROM SELECTED PACKAGES</h5>
                            <span>02</span>
                        </div>
                        <span class="bottom_white_arrow">
                            <img alt="arrow"
                                 src="<?php echo get_template_directory_uri(); ?>/images/arrows/top_white_arrow.png">
                        </span>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 how_it_works_wrap">
                        <div class="howitworks_inner_wrap">
                            <h1><a href="/">START BIDDING</a></h1>
                            <h5>IN LIVE AUCTIONS</h5>
                            <span>03</span>
                        </div>
                        <span class="bottom_white_arrow">
                            <img alt="arrow"
                                 src="<?php echo get_template_directory_uri(); ?>/images/arrows/top_white_arrow.png">
                        </span>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 how_it_works_wrap">
                        <div class="howitworks_inner_wrap">
                            <h1><a href="/winners">WIN ITEMS</a></h1>
                            <h5>AND SAVE UP TO 90%</h5>
                            <span>04</span>
                        </div>
                    </div>
                    <div class="close_howitworks_menu_but">
                        <i class="fa fa-times-circle" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
endif;