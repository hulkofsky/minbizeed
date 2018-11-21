/**
 * Plugin JS
 *
 * @package  mm-dashboard-sharing
 * @developer  Maroun Melhem <http://maroun.me>
 * @version 2.11
 *
 */
jQuery(document).ready(function ($) {
    /*Get plugin URL from DOM*/
    var plugin_url = plugin_obj.plugin_url;

    /*Switchery init*/
    var elem_global = document.querySelector('.mmds_switch_disable');
    if (elem_global) {
        var init_global = new Switchery(elem_global, {
            size: 'default',
            color: '#C92432'
        });
    }

    var elem_pages = document.querySelector('.mmds_switch_disable_pages');
    if (elem_pages) {
        var init_pages = new Switchery(elem_pages, {
            size: 'default',
            color: '#fc5765'
        });
    }

    var elem_fb = document.querySelector('.mmds_switch_disable_fb');
    if (elem_fb) {
        var init_fb = new Switchery(elem_fb, {
            size: 'default',
            color: '#3b5998'
        });
    }

    var elem_tw = document.querySelector('.mmds_switch_disable_tw');
    if (elem_tw) {
        var init_tw = new Switchery(elem_tw, {
            size: 'default',
            color: '#00aced'
        });
    }

    var elem_gp = document.querySelector('.mmds_switch_disable_gp');
    if (elem_gp) {
        var init_gp = new Switchery(elem_gp, {
            size: 'default',
            color: '#d34836'
        });
    }

    $('.mmds_to_set_val').change(function () {
        if ($(this).is(':checked')) {
            $(this).val(1);
        } else {
            $(this).val(0);
        }
    });

    $('input[name="disable_mmds"]').change(function () {
        if ($(this).is(':checked')) {
            $('.mmds_to_hide').closest('tr').slideUp();
        } else {
            $('.mmds_to_hide').closest('tr').slideDown();
        }
    });

    $('.mmds_hidden').each(function () {
        $(this).closest('tr').addClass('mmds_hidden');
        $(this).removeClass('.mmds_hidden');
    });

});