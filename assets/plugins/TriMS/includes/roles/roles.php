<?php
/**
 * Roles
 *
 * @package  TriMS
 * @company  Triangle Mena <http://trianglemena.com>
 * @developer  Maroun Melhem <http://maroun.me>
 * @version 1.0
 *
 */

/*Adding manage_options cap to edtiors start*/
//$role_object = get_role('editor');
//$role_object->add_cap('manage_options');
/*Adding manage_options cap to edtiors end*/


/*Allowing editors to edit users start*/
function isa_editor_manage_users()
{

    if (get_option('isa_add_cap_editor_once') != 'done') {

        // let editor manage users

        $edit_editor = get_role('editor'); // Get the user role
        $edit_editor->add_cap('edit_users');
        $edit_editor->add_cap('list_users');
        $edit_editor->add_cap('promote_users');
        $edit_editor->add_cap('create_users');
        $edit_editor->add_cap('add_users');
        $edit_editor->add_cap('delete_users');

        update_option('isa_add_cap_editor_once', 'done');
    }

}

add_action('init', 'isa_editor_manage_users');
/*Allowing editors to edit users end*/


/*prevent editor from deleting, editing, or creating an administrator start*/

class ISA_User_Caps
{
    // Add our filters
    function ISA_User_Caps()
    {
        add_filter('editable_roles', array(&$this, 'editable_roles'));
        add_filter('map_meta_cap', array(&$this, 'map_meta_cap'), 10, 4);
    }

    // Remove 'Administrator' from the list of roles if the current user is not an admin
    function editable_roles($roles)
    {
        if (isset($roles['administrator']) && !current_user_can('administrator')) {
            unset($roles['administrator']);
        }
        return $roles;
    }
    // If someone is trying to edit or delete an
    // admin and that user isn't an admin, don't allow it
    function map_meta_cap($caps, $cap, $user_id, $args)
    {
        switch ($cap) {
            case 'edit_user':
            case 'remove_user':
            case 'promote_user':
                if (isset($args[0]) && $args[0] == $user_id)
                    break;
                elseif (!isset($args[0]))
                    $caps[] = 'do_not_allow';
                $other = new WP_User(absint($args[0]));
                if ($other->has_cap('administrator')) {
                    if (!current_user_can('administrator')) {
                        $caps[] = 'do_not_allow';
                    }
                }
                break;
            case 'delete_user':
            case 'delete_users':
                if (!isset($args[0]))
                    break;
                $other = new WP_User(absint($args[0]));
                if ($other->has_cap('administrator')) {
                    if (!current_user_can('administrator')) {
                        $caps[] = 'do_not_allow';
                    }
                }
                break;
            default:
                break;
        }
        return $caps;
    }

}

$isa_user_caps = new ISA_User_Caps();
/*prevent editor from deleting, editing, or creating an administrator end*/

/*Hide admin from user list start*/
add_action('pre_user_query', 'isa_pre_user_query');
function isa_pre_user_query($user_search)
{
    $user = wp_get_current_user();
    if (!current_user_can('manage_options')) {
        global $wpdb;
        $user_search->query_where =
            str_replace('WHERE 1=1',
                "WHERE 1=1 AND {$wpdb->users}.ID IN (
                 SELECT {$wpdb->usermeta}.user_id FROM $wpdb->usermeta
                    WHERE {$wpdb->usermeta}.meta_key = '{$wpdb->prefix}capabilities'
                    AND {$wpdb->usermeta}.meta_value NOT LIKE '%administrator%')",
                $user_search->query_where
            );
    }
}
/*Hide admin from user list end*/


