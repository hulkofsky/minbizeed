/**
 * Menu Editor Customizer Scripts
 *
 * @package  TriMS
 * @company  Triangle Mena <http://trianglemena.com>
 * @developer  Maroun Melhem <http://maroun.me>
 * @version 1.0
 *
 */

jQuery(document).ready(function ($) {
    function trims_hide_mep() {

        jQuery('.settings_page_menu_editor').prepend('<div class="se-pre-con"></div>');

        $('.settings_page_menu_editor #ws_menu_editor #ws_actor_selector_container #ws_actor_selector li a[data-text="Editor"]').click();

        $('.settings_page_menu_editor .wrap.ame-is-pro-version .nav-tab-wrapper').remove();

        $('.settings_page_menu_editor .wrap.ame-is-pro-version #ws_ame_editor_heading').html('Users Menu Editor');

        $('.settings_page_menu_editor .wrap.ame-is-pro-version #ws_ame_editor_heading').css({
            "float": "none",
            "text-align": "center"
        });

        $('.settings_page_menu_editor #ws_menu_editor #ws_actor_selector_container #ws_actor_selector li:first').remove();

        var a_e_p_menus_tbr = ['#role:administrator', '#role:author', '#role:contributor', '#role:subscriber'];

        $('.settings_page_menu_editor #ws_menu_editor #ws_actor_selector_container #ws_actor_selector li').each(function () {
            if (a_e_p_menus_tbr.includes($(this).find('a').attr('href'))) {
                $(this).remove();
            }
        });

        $('.settings_page_menu_editor #ws_editor_sidebar #ws_reset_menu').remove();
        $('.settings_page_menu_editor #ws_editor_sidebar #ws_load_menu').remove();
        $('.settings_page_menu_editor #ws_editor_sidebar #ws_sidebar_button_separator').remove();
        $('.settings_page_menu_editor #ws_editor_sidebar #ws_edit_global_colors').remove();
        $('.settings_page_menu_editor #ws_editor_sidebar #ws_export_menu').remove();
        $('.settings_page_menu_editor #ws_editor_sidebar #ws_import_menu').remove();
        $('.settings_page_menu_editor #ws_editor_sidebar #ws_save_menu').css({"margin-bottom": "0px"});

        $('.settings_page_menu_editor .ws_basic_container .metabox-holder').remove();

        $('.settings_page_menu_editor .ws_main_container .ws_container').css({
            "background-color": "#2d3c50",
            "border": "1px solid #2d3c50",
            "color": "#fff"
        });

        $('.settings_page_menu_editor .ws_main_container .ws_container.ws_active').css({"background-color": "#2d3c50"});

        $('.settings_page_menu_editor .ws_main_container .ws_container.ws_menu_separator').css({"background-color": "#c92432"});

        function getFirstWord(str) {
            if (str.indexOf(' ') === -1)
                return str;
            else
                return str.substr(0, str.indexOf(' '));
        };

        function getFirstWordaftChar(str) {
            if (str.indexOf('&') === -1)
                return str;
            else
                return str.substr(0, str.indexOf('&'));
        };

        var a_e_p_menus_elt_tbr = ['Settings;', 'Plugins', 'Custom', 'MM', 'Google', 'Comments', 'Tools'];

        $('.settings_page_menu_editor .ws_container .ws_item_head .ws_item_title').each(function () {
            var word=getFirstWord(getFirstWordaftChar($(this).html())).str.replace(/\s+/g, '');
            if (a_e_p_menus_elt_tbr.includes(word)) {
                $(this).closest('.ws_container').remove();
            }
        });

        var a_e_p_menus_elt_tbr_sub = ['Themes', 'Customize', 'Widgets', 'Header', 'Editor'];

        $('.settings_page_menu_editor .ws_main_container #ws_submenu_box .ws_submenu .ws_item_head .ws_item_title').each(function () {
            var word_sub=getFirstWord(getFirstWordaftChar($(this).html())).str.replace(/\s+/g, '');
            if (a_e_p_menus_elt_tbr_sub.includes(word_sub)) {
                $(this).closest('.ws_container').remove();
            }
        });
        $('.settings_page_menu_editor .ui-dialog-titlebar').css({"background-color": "#d92234"});
    }

    trims_hide_mep();

    $('.settings_page_menu_editor #ws_ame_save_visible_users').on('click', function (e) {
        e.preventDefault;
        trims_hide_mep();
    });
});

jQuery(window).load(function () {
    jQuery(".se-pre-con").fadeOut("slow");
});

