/**
 * Plugin JS
 *
 * @package  TriMS
 * @company  Triangle Mena <http://trianglemena.com>
 * @developer  Maroun Melhem <http://maroun.me>
 * @version 1.0
 *
 */
jQuery(document).ready(function ($) {
    /*Switchery init*/
    var elem_global = document.querySelector('.disable_trims_chat');
    if (elem_global) {
        var init_global = new Switchery(elem_global, {
            size: 'default',
            color: '#C92432'
        });
    }

    var elem_global = document.querySelector('.disable_onesignal');
    if (elem_global) {
        var init_global = new Switchery(elem_global, {
            size: 'default',
            color: '#3b5998'
        });
    }

    var elem_global = document.querySelector('.enable_arabic_theme');
    if (elem_global) {
        var init_global = new Switchery(elem_global, {
            size: 'default',
            color: '#000'
        });
    }


    $('input[name="disable_trims_chat"]').change(function () {
        if ($(this).is(':checked')) {
            $('.trims_to_hide').closest('tr').slideUp();
            $('.trims_to_hide').closest('tr').addClass('trims_hidden');
        } else {
            $('.trims_to_hide').closest('tr').slideDown();
            $('.trims_to_hide').closest('tr').removeClass('trims_hidden');
        }
    });

    $('.trims_hidden').each(function () {
        $(this).closest('tr').addClass('trims_hidden');
        $(this).removeClass('trims_hidden');
    });

});