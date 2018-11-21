/**
 * Main Scripts
 *
 * @package  TriMS
 * @company  Triangle Mena <http://trianglemena.com>
 * @developer  Maroun Melhem <http://maroun.me>
 * @version 1.0
 *
 */

jQuery(document).ready(function ($) {


    var $body = $('body');

    $body.removeClass('folded');

    $body.addClass('TriMS-admin-theme');

    /*send website url to scripts file*/
    var url_images = website_url.template_images_url;

    var tm_logo = url_images + 'head_logo.png';

    /*change yoast logo header*/
    var yoast_to_change = url_images + 'yoast_logo.png';

    jQuery('#wpadminbar #wp-toolbar .ab-item .yoast-logo').removeAttr('style');
    jQuery('#wpadminbar #wp-toolbar .ab-item .yoast-logo').attr('style', 'background-image: url(' + yoast_to_change + ');');

    jQuery('#wpadminbar #wp-toolbar #wp-admin-bar-wpseo-menu.hover .ab-item .yoast-logo').removeAttr('style');
    jQuery('#wpadminbar #wp-toolbar #wp-admin-bar-wpseo-menu.hover .ab-item .yoast-logo').attr('style', 'background-image: url(' + yoast_to_change + ');');

    jQuery('#wpadminbar #wp-toolbar #wp-admin-bar-wpseo-menu.hover .ab-item .yoast-logo').removeAttr('style');
    jQuery('#wpadminbar #wp-toolbar #wp-admin-bar-wpseo-menu.hover .ab-item .yoast-logo').attr('style', 'background-image: url(' + yoast_to_change + ');');

    /*Prepend TriMS Logo Start*/
    var to_prepend =
        '<ul class="custom_tm_logo">' +
        '<li>' +
        '<a href="http://trianglemena.com" target="_blank" title="Triangle Mena" class="head_logo">' +
        '<img src=' + tm_logo + ' alt="Triangle Mena">' +
        '</a>' +
        '</li>' +
        '</ul>';

    $('#wpadminbar #wp-toolbar').prepend(to_prepend);
    /*Prepend TriMS Logo End*/

    /*Change help text html start*/
    var get_template_files_url = website_url.template_files_url;
    var get_website_url = website_url.site_url;
    var template_manual_url = get_template_files_url + "TriMS_manual.pdf";
    jQuery('#wpbody-content #screen-meta-links').append('<div class="add_buttons">' +
        '<a title="Download Manual" target="_blank" href="' + template_manual_url + '" class="button">Download Manual</a>' +
        '<a title="WikiAngle" target="_blank" href="http://wikiangle.com" class="button n">Our Support Website</a>' +
        '<a title="Webmail" target="_blank" href="' + get_website_url + '/webmail" class="button n">Your Webmail</a>' +
        '</div>'
    );

    jQuery('#wpadminbar #wp-toolbar').append('' +
        '<div class="trims_weather">' +
        '<div  id="weather"></div>' +
        '</div>'
    );
    /*Change help text html end*/


    /*Initiate weather start*/

    InitiateWeather();

    function getWeather(gen_location) {
        var weather_url_images = url_images + '/weather/';

        jQuery.simpleWeather({
            location: gen_location,
            woeid: '',
            unit: 'c',
            success: function (weather) {
                html = '<div class="weather_widget">';
                html += '<img src="' + weather_url_images + weather.code + '.png" alt="weather" />' +
                    '<span>' + weather.temp + '&deg;' + weather.units.temp + '</span>' +
                    '<p class="inf">' + weather.city + '</p>';
                html += '</div>';

                jQuery("#weather").html(html);
            },
            error: function (error) {
                jQuery("#weather").html('<p>' + error + '</p>');
            }
        });
    }

//Initiate simple weather according to IP
    function InitiateWeather() {
        getWeather('Beirut');
    }

    /*Initiate weather end*/


    // Move elements inside #post-body-content
    // WordPress Version 4.0 - 4.2
    if ($body.is('.branch-4') || $body.is('.branch-4-0') || $body.is('.branch-4-1') || $body.is('.branch-4-2')) {
        $('.wrap > h2, #screen-meta-links, #screen-meta').prependTo('#post-body-content');

        // Move messages
        if ($('.wrap > .updated, .wrap > .error').length != 0 && $('#post-body-content').length != 0) {
            $('.wrap > .updated, .wrap > .error').insertBefore('#post-body-content h2');
        }

        // Move elements on Tags/Category pages
        if ($('.edit-tags-php #col-right').length != 0) {
            $('.wrap > h2, .wrap > #ajax-response, .wrap > .search-form, .wrap > br').prependTo('#col-right');
        }
    }

    // WordPress Version 4.3
    if ($body.is('.branch-4-3')) {
        $('.wrap > h1, #screen-meta-links, #screen-meta').prependTo('#post-body-content');

        // Move messages
        var $messages = $('.wrap > .updated, .wrap > .error, .wrap > .notice, #wpbody-content > .updated, #wpbody-content > .error, #wpbody-content > .notice, #wpbody-content > .update-nag');
        if ($messages.length != 0 && $('#post-body-content').length != 0) {
            $messages.insertBefore('#post-body-content h1');
        }
    }
    if ($body.is('.edit-tags-php.branch-4-3')) {
        // Move elements on Tags/Category pages
        $('.wrap > h1, .wrap > #ajax-response, .wrap > .search-form, .wrap > br, .wrap > .updated, .wrap > .error, .wrap > .notice, #wpbody-content > .updated, #wpbody-content > .error, #wpbody-content > .notice').prependTo('#col-right .col-wrap');
    }

    // WordPress Version 4.4 or 4.5
    if ($body.is('.branch-4-4') || $body.is('.branch-4-5')) {
        // Move Elements
        $('.wrap > h1, #screen-meta-links, #screen-meta').prependTo('#post-body-content');
    }
    if ($body.is('.edit-tags-php.branch-4-4') || $body.is('.edit-tags-php.branch-4-5')) {
        // Move elements on Tags/Category pages
        $('.wrap > h1, .wrap > #ajax-response, .wrap > .search-form, .wrap > br, .wrap > .updated, .wrap > .error').prependTo('#col-right .col-wrap');
    }

    // WordPress Version 4.6
    if ($body.is('.branch-4-6')) {
        // Move Elements
        $('.wrap > h1, #screen-meta-links, #screen-meta').prependTo('#post-body-content');
    }
    if ($body.is('.edit-tags-php.branch-4-6')) {
        // Move elements on Tags/Category pages
        $('.wrap > h1, .wrap > #ajax-response, .wrap > .search-form, .wrap > br, .wrap > .updated, .wrap > .error').prependTo('#col-right .col-wrap');
    }

    // WordPress Version 4.7
    if ($body.is('.branch-4-7')) {
        // Move Elements
        $('.wrap > h1, #screen-meta-links, #screen-meta').prependTo('#post-body-content');
        // Move elements on Posts page
        $('.page-title-action').appendTo('.wp-heading-inline');
    }
    if ($body.is('.edit-tags-php.branch-4-7')) {
        // Move elements on Tags/Category pages
        $('.wrap > h1, .wrap > #ajax-response, .wrap > .search-form, .wrap > br, .wrap > .updated, .wrap > .error').prependTo('#col-right .col-wrap');
    }

    // Add background divs
    if ($('#poststuff #side-sortables').length != 0 && !$('body').is('.index-php')) {
        $('#side-sortables').before('<div id="side-sortablesback"></div>');
    }
    if ($('.edit-tags-php #col-left').length != 0) {
        $('.edit-tags-php #col-left').before('<div id="col-leftback"></div>');
    }
    if ($('.comment-php #submitdiv').length != 0) {
        $('.comment-php #submitdiv').before('<div id="submitdiv-back"></div>');
    }

    // Move Post State span
    if (($('span.post-state').length != 0) && ($('span.post-state').parent().is('td') == false)) {
        $('span.post-state').each(function () {
            $(this).insertBefore($(this).parent());
        });
    }

    // Add focus to wp editor

//    wp_editor('', 'sedemoeditor', array(
//            'tinymce' = > array(
//            'init_instance_callback' = > 'function(editor) {
//    editor.on("focus", function () {
//        console.log("Editor: " + editor.id + " focus.");
//    });
//}
//')));

});
