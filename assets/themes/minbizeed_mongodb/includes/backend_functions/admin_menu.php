<?php
/**
 * Admin Menu
 *
 * @package  MinBizeed
 * @company  Triangle Mena <http://trianglemena.com>
 * @developer  Maroun Melhem <http://maroun.me>
 *
 */

function minbizeed_theme_bullet($rn = '')
{
    global $menu_admin_minbizeed_theme_bull;
    $menu_admin_minbizeed_theme_bull = '<a href="#" class="tltp_cls" title="' . $rn . '"><img src="' . get_bloginfo('template_url') . '/images/dashboard_icons/qu_icon.png" /></a>';
    echo $menu_admin_minbizeed_theme_bull;
}

function minbizeed_disp_spcl_cst_pic($pic)
{
    return '<img src="' . get_bloginfo('template_url') . '/images/dashboard_icons/' . $pic . '" /> ';
}

add_action('admin_menu', 'minbizeed_admin_main_menu_scr');
function minbizeed_admin_main_menu_scr()
{
    $icn = get_bloginfo('template_url') . '/images/dashboard_icons/mbz.png';
    $capability = 10;

    add_menu_page(__('Minbizeed'), __('Minbizeed', 'minbizeed'), $capability, "dashboard_settings", 'dashboard_settings', $icn, 0);
    add_submenu_page("dashboard_settings", __('Site Summary', 'minbizeed'),  __('Site Summary', 'minbizeed'), $capability, "dashboard_settings", 'minbizeed_site_summary');

    add_submenu_page("dashboard_settings", __('Bid Packages', 'minbizeed'),  __('Bid Packages', 'minbizeed'), $capability, 'bid_packages', 'minbizeed_bid_packages');

    add_submenu_page('dashboard_settings', __('User Balances', 'minbizeed'), __('User Balances', 'minbizeed'), '10', 'user_balances', 'minbizeed_user_balances');

    add_submenu_page('dashboard_settings', __('Users Stats', 'minbizeed'),  __('User Stats', 'minbizeed'), '10', 'user_stats', 'minbizeed_user_stats');

    add_submenu_page("dashboard_settings", __('InSite Transactions', 'minbizeed'),__('InSite Transactions', 'minbizeed'), $capability, 'trans-sites', 'minbizeed_hist_transact');
    add_submenu_page("dashboard_settings", __('Orders', 'minbizeed'), __('Orders', 'minbizeed'), $capability, 'orders', 'minbizeed_orders_main_screen');
    add_submenu_page("dashboard_settings", __('Not fullfilled refunds', 'minbizeed'), __('Not fullfilled refunds', 'minbizeed'), $capability, 'trans-refunds', 'minbizeed_not_fullfilled_refunds');

    add_submenu_page("dashboard_settings", __('Min price calculator', 'minbizeed'), __('Min price calculator', 'minbizeed'), $capability, 'min-price-calculator', 'minbizeed_min_price_calculator');
}