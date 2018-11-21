<?php
/**
 * Plugin options
 *
 * @package  TriMS
 * @company  Triangle Mena <http://trianglemena.com>
 * @developer  Maroun Melhem <http://maroun.me>
 * @version 1.0
 *
 */

/*Plugin options start*/
include(plugin_dir_path(__FILE__) . '/src-functions.php');
/*Plugin options end*/

/*Adding page to submenu start*/
function TriMS_register_options_page()
{
    add_menu_page("TriMS", "TriMS", "manage_options", "trims-options", "trims_options", "dashicons-analytics", null, 99);
}

add_action("admin_menu", "TriMS_register_options_page");
/*Adding page to submenu end*/

/*Register settings page start*/
function enable_arabic_theme()
{
    ?>
    <input type="checkbox" class="enable_arabic_theme" name="enable_arabic_theme"
           value="1" <?php checked(1, get_option('enable_arabic_theme'), true); ?> />
    <?php
}

function disable_onesignal()
{
    ?>
    <input type="checkbox" class="disable_onesignal" name="disable_onesignal"
           value="1" <?php checked(1, get_option('disable_onesignal'), true); ?> />
    <?php
}

function disable_trims_chat()
{
    ?>
    <input type="checkbox" class="disable_trims_chat" name="disable_trims_chat"
           value="1" <?php checked(1, get_option('disable_trims_chat'), true); ?> />
    <?php
}

function trims_chat_dev()
{
    ?>
    <select
        class="trims_chat_dev trims_to_hide<?php if (get_option('disable_trims_chat')): echo " trims_hidden"; endif; ?>"
        name="trims_chat_dev">
        <?php
        if (get_option('trims_chat_dev')) {
            ?>
            <option selected="selected" value="<?php echo get_option('trims_chat_dev'); ?>">
                <?php
                echo ucfirst(get_option('trims_chat_dev'));
                ?>
            </option>
            <?php
        } else {
            ?>
            <option selected="selected" value="">Select Developer</option>
            <?php
        }
        $vals_arr = [
            'maroun',
            'marc',
            'jean',
        ];
        foreach ($vals_arr as $val_arr) {
            if ($val_arr != get_option('trims_chat_dev')) {
                ?>
                <option value="<?php echo $val_arr; ?>">
                    <?php
                    echo ucfirst($val_arr);
                    ?>
                </option>
                <?php
            }
        }
        ?>
    </select>
    <?php
}

function trims_settings_fields()
{
    add_settings_section("trims-section", "Plugin Settings", null, "trims-options");

    add_settings_field("disable_trims_chat", "Disable Chat support?", "disable_trims_chat", "trims-options", "trims-section");

    add_settings_field("trims_chat_dev", "Select project developer", "trims_chat_dev", "trims-options", "trims-section");

    add_settings_field("disable_onesignal", "Disable OneSignal web notifications?", "disable_onesignal", "trims-options", "trims-section");

    add_settings_field("enable_arabic_theme", "Enable arabic theme?", "enable_arabic_theme", "trims-options", "trims-section");

    register_setting("trims-section", "disable_trims_chat");
    register_setting("trims-section", "trims_chat_dev");
    register_setting("trims-section", "disable_onesignal");
    register_setting("trims-section", "enable_arabic_theme");
}

add_action("admin_init", "trims_settings_fields");
/*Register settings page end*/


/*Plugin page start*/
function trims_options()
{
    ?>
    <div class="wrap trims_plugin_options">
        <h1>TriMS Options</h1>
        <?php settings_errors(); ?>
        <form method="POST" action="options.php" class="settings_form">
            <?php
            settings_fields("trims-section");
            do_settings_sections("trims-options");
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

/*Plugin page end*/


/*Adding page to submenu start*/
function TriMS_mourning_register_options_page()
{
    add_menu_page("Mourning Theme", "Mourning Theme", "manage_options", "trims-mourning-theme-options", "trims_mourning_theme_options", "dashicons-admin-customizer", null, 99);
}

add_action("admin_menu", "TriMS_mourning_register_options_page");
/*Adding page to submenu end*/


/*Register settings page start*/
function enable_trims_mourning_theme()
{
    ?>
    <input type="checkbox" class="disable_trims_chat" name="enable_trims_mourning_theme"
           value="1" <?php checked(1, get_option('enable_trims_mourning_theme'), true); ?> />
    <?php
}

function trims_mourning_theme_fields()
{
    add_settings_section("trims-mourning-section", "Mourning theme (Add a black n white theme to all website pages)", null, "trims-mourning-options");

    add_settings_field("enable_trims_mourning_theme", "Enable?", "enable_trims_mourning_theme", "trims-mourning-options", "trims-mourning-section");

    register_setting("trims-mourning-section", "enable_trims_mourning_theme");

}

add_action("admin_init", "trims_mourning_theme_fields");
/*Register settings page end*/

/*Mourning Plugin page start*/
function trims_mourning_theme_options()
{
    ?>
    <div class="wrap trims_plugin_options">
        <?php settings_errors(); ?>
        <form method="POST" action="options.php" class="settings_form">
            <?php
            settings_fields("trims-mourning-section");
            do_settings_sections("trims-mourning-options");
            submit_button();
            ?>
        </form>
    </div>
    <?php
}
/*Mourning Plugin page end*/

