<?php
/**
 * Options Functions
 *
 * @package  mm-dashboard-sharing
 * @developer  Maroun Melhem <http://maroun.me>
 * @version 2.11
 *
 */

/*Adding page to submenu start*/
function mm_dashboard_sharing_register_options_page()
{
    add_menu_page("MM Dashboard Sharing", "MM Dashboard Sharing", "manage_options", "mm-dashboard-sharing", "mm_dashboard_sharing_settings", "dashicons-share", null, 99);
}

add_action("admin_menu", "mm_dashboard_sharing_register_options_page");
/*Adding page to submenu end*/


/*Register settings page start*/

function disable_mmds_fcts()
{
    ?>
    <input class="mmds_switch_disable" type="checkbox" name="disable_mmds"
           value="1" <?php checked(1, get_option('disable_mmds'), true); ?> />
    <?php
}

function disable_mmds_fcts_pages()
{
    ?>
    <input class="mmds_switch_disable_pages mmds_to_hide<?php if(get_option('disable_mmds')): echo " mmds_hidden"; endif;?>" type="checkbox" name="disable_mmds_pages"
           value="1" <?php checked(1, get_option('disable_mmds_pages'), true); ?> />
    <?php
}

function disable_mmds_fcts_fb()
{
    ?>
    <input class="mmds_switch_disable_fb mmds_to_hide<?php if(get_option('disable_mmds')): echo " mmds_hidden"; endif;?>" type="checkbox" name="disable_mmds_fb"
           value="1" <?php checked(1, get_option('disable_mmds_fb'), true); ?> />
    <?php
}

function disable_mmds_fcts_tw()
{
    ?>
    <input class="mmds_switch_disable_tw mmds_to_hide<?php if(get_option('disable_mmds')): echo " mmds_hidden"; endif;?>" type="checkbox" name="disable_mmds_tw"
           value="1" <?php checked(1, get_option('disable_mmds_tw'), true); ?> />
    <?php
}

function disable_mmds_fcts_gp()
{
    ?>
    <input class="mmds_switch_disable_gp mmds_to_hide<?php if(get_option('disable_mmds')): echo " mmds_hidden"; endif;?>" type="checkbox" name="disable_mmds_gp"
           value="1" <?php checked(1, get_option('disable_mmds_gp'), true); ?> />
    <?php
}

function mmds_settings_fields()
{
    add_settings_section("mmds-section", "Plugin Settings", null, "mmds-options");

    add_settings_field("disable_mmds", "Disable All Buttons?", "disable_mmds_fcts", "mmds-options", "mmds-section");

    add_settings_field("disable_mmds_pages", "Disable In Pages?", "disable_mmds_fcts_pages", "mmds-options", "mmds-section");

    add_settings_field("disable_mmds_fb", "Disable Facebook button?", "disable_mmds_fcts_fb", "mmds-options", "mmds-section");

    add_settings_field("disable_mmds_tw", "Disable Twitter button?", "disable_mmds_fcts_tw", "mmds-options", "mmds-section");

    add_settings_field("disable_mmds_gp", "Disable Google+ button?", "disable_mmds_fcts_gp", "mmds-options", "mmds-section");

    register_setting("mmds-section", "disable_mmds");
    register_setting("mmds-section", "disable_mmds_pages");
    register_setting("mmds-section", "disable_mmds_fb");
    register_setting("mmds-section", "disable_mmds_tw");
    register_setting("mmds-section", "disable_mmds_gp");
}

add_action("admin_init", "mmds_settings_fields");
/*Register settings page end*/


/*Plugin page start*/
function mm_dashboard_sharing_settings()
{
    ?>
    <div class="wrap mmds_plugin_options">
        <h1>MM Dashboard Sharing Options</h1>

        <p class="desc">MM dashboard sharing adds social media sharing buttons within your admin panel</p>

        <div class="credits">
            <p>Developed by
                <<a href="http://maroun.me" target="_blank">Maroun Melhem</a>>
            </p>
            <p>
                Hire me / Suggest an update <a href="http://maroun.me/#contact" target="_blank">here</a>
            </p>
            <p>
                Report a bug or an issue <a href="https://wordpress.org/support/plugin/mm-dashboard-customizer" target="_blank">here</a>
            </p>
            <p>
                If you enjoy using the plugin, please add a <a href="https://wordpress.org/support/plugin/mm-dashboard-customizer/reviews/?rate=5#new-post" target="_blank">
                    <span class="dashicons dashicons-star-filled"></span>
                    <span class="dashicons dashicons-star-filled"></span>
                    <span class="dashicons dashicons-star-filled"></span>
                    <span class="dashicons dashicons-star-filled"></span>
                    <span class="dashicons dashicons-star-filled"></span>
                    review
                </a>
            </p>
        </div>
        <?php settings_errors(); ?>
        <form method="POST" action="options.php" class="settings_form">
            <?php
            settings_fields("mmds-section");
            do_settings_sections("mmds-options");
            submit_button();
            ?>
        </form>
    </div>
    <?php
}
/*Plugin page end*/

