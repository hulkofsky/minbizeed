/**
 * Main scripts
 *
 * @package  MinBizeed
 * @company  Triangle Mena <http://trianglemena.com>
 * @developer  Maroun Melhem <http://maroun.me>
 *
 */
jQuery(document).ready(function () {

    var menu_cont = jQuery('.dynamic_menu ul li');
    var pathname = window.location.pathname;

    if ((pathname !== "/") && (pathname.indexOf('category') === -1)) {

        menu_cont.removeClass('active');

        menu_cont.each(function () {
            var elt = $(this);
            var elt_d_menu = elt.attr('data-menu');
            if (elt_d_menu === pathname) {
                elt.addClass('active');
            }
        });
    }

    $('.how_it_works_page .hiw_yt,.single-auction .auction_yt').magnificPopup({
        disableOn: false,
        type: 'iframe',
        mainClass: 'mfp-fade',
        removalDelay: 160,
        preloader: false,
        fixedContentPos: false
    });

    if (window.location.hash) scroll(0, 0);
    setTimeout(function () {
        scroll(0, 0);
    }, 1);
    $(function () {
        if (window.location.hash) {
            $('html, body').animate({
                scrollTop: $(window.location.hash).offset().top - 95 + 'px'
            }, 1000, 'swing');
        }
    });

    jQuery('.single-auction .to_copy').on('click', function (e) {
        e.preventDefault();
        copyToClipboard('#share_link');
    });

    function copyToClipboard(element) {
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($(element).val()).select();
        document.execCommand("copy");
        $temp.remove();

        var notice = $('.other_notice.success');
        var notice_p = $('.other_notice.success p');

        if (!notice.hasClass('showing')) {
            notice.addClass('showing');
            notice.slideUp('fast');
            notice_p.html('<i class="fa fa-link" aria-hidden="true"></i> Auction link copied to your clipboard <i class="fa fa-files-o" aria-hidden="true"></i>');
            notice.slideDown('fast');
            setTimeout(function () {
                notice.slideUp('fast');
                notice_p.html('');
                notice.removeClass('showing');
            }, 2500);
        }
    }


    var windowW = jQuery(window).width();
    if (windowW > 768) {
        //trigger open how it works menu button
//        jQuery('.howitworks_button').on('click', function (e) {
//            e.preventDefault();
//            jQuery(this).addClass('how_it_works_opened');
//            jQuery('.how_it_works_section').slideDown(1000);
//        });

//trigger close how it works menu button
        jQuery('.close_howitworks_menu_but').on('click', function () {
            jQuery('.howitworks_button').removeClass('how_it_works_opened');
            jQuery('.how_it_works_section').slideUp(1000);
        });
    }

//opening the mobile menu
    jQuery('.mobile_button_wrapper span').on('click', function () {
        if (jQuery(this).hasClass('to_open')) {
            jQuery('.top_site_section .middle_menu_bar .center_middle_menu_bar').slideDown(500);
            jQuery('.top_site_section .middle_menu_bar .center_middle_menu_bar ul,.top_site_section .middle_menu_bar .center_middle_menu_bar .mobile_menu')
                .animate(({opacity: 1}), 1500);
            jQuery('.to_open').removeClass('to_open').addClass('to_close');
            jQuery(this).html('<i class="fa fa-times" aria-hidden="true"></i>');
        } else {
            jQuery('.top_site_section .middle_menu_bar .center_middle_menu_bar').slideUp(500);
            jQuery('.top_site_section .middle_menu_bar .center_middle_menu_bar ul,.top_site_section .middle_menu_bar .center_middle_menu_bar .mobile_menu')
                .animate(({opacity: 0}), 1500);
            jQuery(this).removeClass('to_close').addClass('to_open');
            jQuery(this).html('MENU');
        }
    });

//trigger open live auctions category list
    jQuery('.category_filter_wrapper .first_shown_wrap').on('click', function () {
        jQuery('.categories_list').fadeToggle(500);
    });

//on click on category event
    jQuery('.category_filter_wrapper .categories_list li').on('click', function () {
        jQuery('.categories_list').fadeOut(500);
        var selected_cats = jQuery(this).attr('data-to-select');
        jQuery('.category_filter_wrapper .first_shown_wrap .first_shown').attr('data-selected', selected_cats);
        jQuery('.category_filter_wrapper .first_shown_wrap .first_shown').html(selected_cats);
    });

//single product images functionality
    jQuery('.small_images_holder .small_image').on('click', function () {
        var image_src = jQuery(this).attr('src');
        jQuery('.large_image_holder .large_image').fadeOut('slow', function () {
            jQuery('.large_image_holder .large_image').attr('src', image_src);
        });
        jQuery('.large_image_holder .large_image').fadeIn('slow');
    });

//scroll up and down for read more single product
    jQuery('.tech_specs_wrapper .read_more_button').on('click', function () {
        if (jQuery(this).find('.button_button').hasClass('show_more')) {
            var wrapper_height = jQuery('.all_specs_holder').height();
            var p_height = jQuery('.all_specs_holder p').height();
            var total = wrapper_height - p_height - 40;
            jQuery(this).find('.button_button').removeClass('show_more').addClass('show_less');
            jQuery('.all_specs_holder p').animate({margin: total + 'px 0 0'}, 1000, function () {
                jQuery('.read_more_button span:nth-child(1)').html('less');
                jQuery('.read_more_button .dropdown_arrow').css('transform', 'rotate(225deg)');
            });
        } else {
            jQuery(this).find('.button_button').removeClass('show_less').addClass('show_more');
            jQuery('.all_specs_holder p').animate({margin: '25px 0 0'}, 1000, function () {
                jQuery('.read_more_button span:nth-child(1)').html('more');
                jQuery('.read_more_button .dropdown_arrow').css('transform', 'rotate(45deg)');
            });
        }
    });

//on click buy bids button
    jQuery('.footer .outer_buy_bids_button .buy_bids_init').on('click', function (e) {
        e.preventDefault();
        jQuery('.bids_wrapper').slideDown(1000);
        jQuery('.footer .footer_buy_part .bottom_arrow').fadeOut();
        jQuery('.outer_buy_bids_button').css('background-color', 'rgba(30, 10, 16, 0.95)');
        jQuery('.outer_buy_bids_button a').css('opacity', '0');
        jQuery('.page_overlay').fadeIn(500);
    });
    jQuery('.footer .inner_buy_bids_button .buy_bids_init').on('click', function (e) {
        e.preventDefault();
        jQuery('.footer .footer_buy_part .bottom_arrow').fadeIn();
        jQuery('.outer_buy_bids_button').css('background-color', '#d4416d');
        jQuery('.outer_buy_bids_button a').css('opacity', '1');
        jQuery('.bids_wrapper').slideUp(1000);
        jQuery('.page_overlay').fadeOut(500);
    });

    //if buy bids button clicked in the header
    jQuery('.buy_bids_header').on('click', function (e) {
        e.preventDefault();
        jQuery('html,body').animate({
            scrollTop: jQuery(".footer").offset().top
        }, 500);
        jQuery('.footer .outer_buy_bids_button .buy_bids_init').click();
    });

    //winners show more button
    jQuery('.single_winner_wrap .auction_won_holder .show_more_holder').on('click', function () {
        jQuery(this).parent().find('.auction_wrap .hidden_wrapper').slideDown(1000);
        jQuery(this).closest('.auction_won_holder').find('.show_more_holder').fadeOut();
        jQuery(this).closest('.auction_won_holder').find('.product_title img.winner_arrow_bottom').animate({height: '0px'}, 750, function () {
            jQuery(this).closest('.auction_won_holder').find('.product_title img.winner_arrow').animate({height: '10px'}, 750);
        });
        jQuery('html,body').animate({
            scrollTop: jQuery(".image_holder").offset().top
        }, 500);
    });
    jQuery('.single_winner_wrap .auction_won_holder .auction_wrap .product_title img.winner_arrow').on('click', function () {
        jQuery(this).closest('.auction_won_holder').find('.auction_wrap .hidden_wrapper').slideUp(1000);
        jQuery(this).closest('.auction_won_holder').find('.product_title img.winner_arrow').animate({height: '0px'}, 750, function () {
            jQuery(this).closest('.auction_won_holder').find('.product_title img.winner_arrow_bottom').animate({height: '16px'}, 750);
        });
        jQuery(this).closest('.auction_won_holder').find('.show_more_holder').fadeIn();
    });

    jQuery('.category_filter_wrapper .categories_list li').on('click', function (e) {
        e.preventDefault();
        var to = $(this).attr('data-to-select-link');
        window.location.replace(to);
    });

});
