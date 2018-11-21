jQuery(document).ready(function () {


    if (window.location.hash) {
        jQuery(function () {
            jQuery('html, body').animate({
                scrollTop: jQuery(window.location.hash).offset().top - 65 + 'px'
            }, 1000, 'swing');
        });
    }
    //-----------------------

    jQuery(".update_package").on("click", function (e) {
        e.preventDefault();

        var update_package_id = jQuery(this).attr('rel');

        var specific_field = jQuery('#my_pkg_cell' + update_package_id);

        var ajax_loader = specific_field.find('.ajax_loader');

        var ajax_btns = specific_field.find('.ajax_btns');

        var return_message = specific_field.find('.return_message');

        ajax_btns.slideUp();
        ajax_loader.slideDown();
        return_message.find('p').removeClass('success');
        return_message.find('p').removeClass('error');

        var update_package_nonce = jQuery(this).attr('data-nonce');

        var update_package_ajax_url = jQuery(this).attr('data-ajax_url');

        var new_package_name_cell = jQuery("#new_package_name_cell" + update_package_id).val();
        var new_package_cost_cell = jQuery("#new_package_cost_cell" + update_package_id).val();
        var new_package_bid_cell = jQuery("#new_package_bid_cell" + update_package_id).val();

        if (!new_package_name_cell) {

            ajax_btns.slideDown();
            ajax_loader.slideUp();

            return_message.find('p').addClass('error');
            return_message.find('p').html('Please enter a package name');
            return_message.slideDown();

        } else if (isNaN(new_package_cost_cell) || new_package_cost_cell <= 0) {

            ajax_btns.slideDown();
            ajax_loader.slideUp();

            return_message.find('p').addClass('error');
            return_message.find('p').html('Please enter a positive (not null) bids cost number');
            return_message.slideDown();

        } else if (isNaN(new_package_bid_cell) || (new_package_bid_cell <= 0)) {

            ajax_btns.slideDown();
            ajax_loader.slideUp();

            return_message.find('p').addClass('error');
            return_message.find('p').html('Please enter a positive (not null) bids number');
            return_message.slideDown();

        } else {
            jQuery.ajax({
                url: update_package_ajax_url,
                type: "POST",
                dataType: "json",
                data: {
                    nonce: update_package_nonce,
                    action: "minbizeed_update_package",
                    id: update_package_id,
                    new_package_name_cell: new_package_name_cell,
                    new_package_bid_cell: new_package_bid_cell,
                    new_package_cost_cell: new_package_cost_cell
                },
                success: function (response) {
                    if (response.type == "success") {

                        ajax_btns.slideDown();
                        ajax_loader.slideUp();

                        return_message.find('p').addClass('success');
                        return_message.find('p').html('Package successfully updated!');
                        return_message.slideDown();

                        setTimeout(function () {
                            return_message.slideUp();
                        }, 2000);

                    } else if (response.type == "no_changes") {

                        ajax_btns.slideDown();
                        ajax_loader.slideUp();

                        return_message.find('p').addClass('error');
                        return_message.find('p').html('You didn\'t make any changes');
                        return_message.slideDown();

                        setTimeout(function () {
                            return_message.slideUp();
                        }, 2000);
                    } else if (response.type == "data_error") {

                        ajax_btns.slideDown();
                        ajax_loader.slideUp();

                        return_message.find('p').addClass('error');
                        return_message.find('p').html('Please double check your entries and try again');
                        return_message.slideDown();

                        setTimeout(function () {
                            return_message.slideUp();
                        }, 2000);
                    }
                },
                error: function () {

                    ajax_btns.slideDown();
                    ajax_loader.slideUp();

                    return_message.find('p').addClass('error');
                    return_message.find('p').html('An error occured, please refresh and try again');
                    return_message.slideDown();

                    setTimeout(function () {
                        return_message.slideUp();
                    }, 2000);

                }
            });
        }
    });


    //-----------------------

    jQuery(".delete_package").on("click", function (e) {
        e.preventDefault();

        var ask = confirm("Are you absolutely sure?");

        if (ask) {

            jQuery('#my_packages_stuff').slideUp();

            jQuery('.full_loader').slideDown();

            var delete_package_id = jQuery(this).attr('rel');

            var specific_field = jQuery('#my_pkg_cell' + delete_package_id);

            var ajax_loader = specific_field.find('.ajax_loader');

            var ajax_btns = specific_field.find('.ajax_btns');

            var return_message = specific_field.find('.return_message');

            ajax_btns.slideUp();
            ajax_loader.slideDown();
            return_message.find('p').removeClass('success');
            return_message.find('p').removeClass('error');

            var delete_package_nonce = jQuery(this).attr('data-nonce');

            var delete_package_ajax_url = jQuery(this).attr('data-ajax_url');

            if (!delete_package_id) {

                ajax_btns.slideDown();
                ajax_loader.slideUp();

                jQuery('#my_packages_stuff').slideDown();

                jQuery('.full_loader').slideDown();

                return_message.find('p').addClass('error');
                return_message.find('p').html('An error occured before processing your request, please refresh and try again.');
                return_message.slideDown();

            } else {
                jQuery.ajax({
                    url: delete_package_ajax_url,
                    type: "POST",
                    dataType: "json",
                    data: {
                        nonce: delete_package_nonce,
                        action: "minbizeed_delete_package",
                        id: delete_package_id
                    },
                    success: function (response) {
                        if (response.type == "success") {

                            ajax_loader.slideUp();

                            return_message.find('p').addClass('success');
                            return_message.find('p').html('Package successfully deleted!');
                            return_message.slideDown();

                            setTimeout(function () {
                                return_message.slideUp();
                                specific_field.slideUp();
                            }, 2000);

                        } else if (response.type == "error") {

                            ajax_btns.slideDown();
                            ajax_loader.slideUp();

                            return_message.find('p').addClass('error');
                            return_message.find('p').html('An error occured, please refresh and try again');
                            return_message.slideDown();

                            setTimeout(function () {
                                return_message.slideUp();
                            }, 2000);
                        }
                    },
                    error: function () {

                        ajax_btns.slideDown();
                        ajax_loader.slideUp();

                        return_message.find('p').addClass('error');
                        return_message.find('p').html('An error occured, please refresh and try again');
                        return_message.slideDown();

                        setTimeout(function () {
                            return_message.slideUp();
                        }, 2000);

                    }
                });
            }
        } else {
            return false;
        }
    });


    jQuery("#new_package_action").on("click", function (e) {
        e.preventDefault();

        var specific_field = jQuery('#my_pkg_cell_new');

        var ajax_loader = specific_field.find('.ajax_loader');

        var ajax_btns = specific_field.find('.ajax_btns');

        var return_message = specific_field.find('.return_message');

        ajax_btns.slideUp();
        ajax_loader.slideDown();
        return_message.find('p').removeClass('success');
        return_message.find('p').removeClass('error');

        var create_package_nonce = jQuery(this).attr('data-nonce');

        var create_package_ajax_url = jQuery(this).attr('data-ajax_url');

        var new_package_name = jQuery("#new_package_name").val();
        var new_package_cost = jQuery("#new_package_cost").val();
        var new_package_bid = jQuery("#new_package_bid").val();

        if (!new_package_name) {

            ajax_btns.slideDown();
            ajax_loader.slideUp();

            return_message.find('p').addClass('error');
            return_message.find('p').html('Please enter a package name');
            return_message.slideDown();

        } else if (isNaN(new_package_cost) || new_package_cost <= 0) {

            ajax_btns.slideDown();
            ajax_loader.slideUp();

            return_message.find('p').addClass('error');
            return_message.find('p').html('Please enter a positive (not null) bids cost number');
            return_message.slideDown();

        } else if (isNaN(new_package_bid) || (new_package_bid <= 0)) {

            ajax_btns.slideDown();
            ajax_loader.slideUp();

            return_message.find('p').addClass('error');
            return_message.find('p').html('Please enter a positive (not null) bids number');
            return_message.slideDown();

        } else {
            jQuery.ajax({
                url: create_package_ajax_url,
                type: "POST",
                dataType: "json",
                data: {
                    nonce: create_package_nonce,
                    action: "minbizeed_new_package",
                    new_package_name: new_package_name,
                    new_package_cost: new_package_cost,
                    new_package_bid: new_package_bid
                },
                success: function (response) {
                    if (response.type == "success") {

                        var created_id = response.id;

                        ajax_loader.slideUp();

                        return_message.find('p').addClass('success');
                        return_message.find('p').html('Package successfully created, updating in 3 2 1');
                        return_message.slideDown();

                        setTimeout(function () {
                            return_message.slideUp();


                            jQuery("#new_package_name").val("");
                            jQuery("#new_package_cost").val("");
                            jQuery("#new_package_bid").val("");

                            window.location.href = "/wp-admin/admin.php?page=bid_packages#my_pkg_cell" + created_id;
                            location.reload();

                        }, 2000);

                    } else if (response.type == "error") {

                        ajax_btns.slideDown();
                        ajax_loader.slideUp();

                        return_message.find('p').addClass('error');
                        return_message.find('p').html('An error occured, please refresh and try again');
                        return_message.slideDown();

                        setTimeout(function () {
                            return_message.slideUp();
                        }, 2000);
                    }
                },
                error: function () {

                    ajax_btns.slideDown();
                    ajax_loader.slideUp();

                    return_message.find('p').addClass('error');
                    return_message.find('p').html('An error occured, please refresh and try again');
                    return_message.slideDown();

                    setTimeout(function () {
                        return_message.slideUp();
                    }, 2000);

                }
            });
        }
    });

    //-------------------------


});