<?php
/**
 * Option pages
 *
 * @package  MinBizeed
 * @company  Triangle Mena <http://trianglemena.com>
 * @developer  Maroun Melhem <http://maroun.me>
 *
 */
if(function_exists('acf_add_options_page')) {
    acf_add_options_page(array(
            'page_title' => 'How It Works',
            'menu_title' => 'How It Works',
            'menu_slug' => 'how_it_works',
            'redirect' => false,
            'icon_url' => 'dashicons-image-filter',
    ));

    acf_add_options_page(array(
        'page_title' => 'Privacy Policy',
        'menu_title' => 'Privacy',
        'menu_slug' => 'privacy',
        'redirect' => false,
        'icon_url' => 'dashicons-feedback',
    ));

    acf_add_options_page(array(
        'page_title' => 'Terms of Use',
        'menu_title' => 'Terms',
        'menu_slug' => 'terms',
        'redirect' => false,
        'icon_url' => 'dashicons-admin-network',
    ));
}

