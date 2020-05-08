<?php

namespace Tonik\Theme\App\Http;

/*
|-----------------------------------------------------------------
| Theme Assets
|-----------------------------------------------------------------
|
| This file is for registering your theme stylesheets and scripts.
| In here you should also deregister all unwanted assets which
| can be shiped with various third-parity plugins.
|
*/

use function Tonik\Theme\App\asset;
use function Tonik\Theme\App\asset_path;
use function Tonik\Theme\App\webpack_dev_server;

/**
 * Registers theme stylesheet files.
 *
 * @return void
 */
function register_stylesheets() {

    if (!webpack_dev_server()) {
        $mainCss = asset('css/main.css');
        wp_enqueue_style('app', $mainCss->getUri(), [], sha1_file($mainCss->getPath()));
    }

    wp_deregister_style( 'algolia-instantsearch' );
    wp_deregister_style( 'algolia-autocomplete' );

}
add_action('wp_enqueue_scripts', 'Tonik\Theme\App\Http\register_stylesheets', 10);


/**
 * Registers theme script files.
 *
 * Priority 1 so it is added before jquery
 *
 * @return void
 */
function register_scripts() {

    $chunk_vendors_js = asset('js/chunk-vendors.js');
    wp_enqueue_script(
        'chunk-vendors',
        $chunk_vendors_js->getUri(),
        [],
        sha1_file($chunk_vendors_js->getPath()),
        true
    );

    $main_js = asset('js/main.js');
    wp_enqueue_script(
        'main',
        $main_js->getUri(),
        ['chunk-vendors'],
        sha1_file($main_js->getPath()),
        true
    );

    $vanilla_js = asset('js/vanilla.js');
    wp_enqueue_script(
        'vanilla',
        $vanilla_js->getUri(),
        ['jquery'],
        sha1_file($vanilla_js->getPath()),
        true
    );

    wp_deregister_script( 'algolia-instantsearch' );
    wp_deregister_script( 'algolia-autocomplete' );

}
add_action('wp_enqueue_scripts', 'Tonik\Theme\App\Http\register_scripts', 10);

/**
 * Registers editor stylesheets.
 *
 * @return void
 */
function register_editor_stylesheets() {
    add_editor_style(asset_path('css/main.css'));
}
add_action('admin_init', 'Tonik\Theme\App\Http\register_editor_stylesheets');

/**
 * Moves front-end jQuery script to the footer.
 *
 * @param  \WP_Scripts $wp_scripts
 * @return void
 */
function move_jquery_to_the_footer($wp_scripts) {
    if( is_admin() ) return;
    $wp_scripts->add_data( 'jquery', 'group', 1 );
    $wp_scripts->add_data( 'jquery-core', 'group', 1 );
    $wp_scripts->add_data( 'jquery-migrate', 'group', 1 );
}
add_action('wp_default_scripts', 'Tonik\Theme\App\Http\move_jquery_to_the_footer');

/**
 * Registers admin scripts and stylesheets.
 *
 * @return void
 */
function register_admin_scripts_and_styles() {
    /*
     * Javascript
     */
    wp_enqueue_script(
        'chartist_js',
        '//cdn.jsdelivr.net/chartist.js/latest/chartist.min.js',
        [],
        '0.11.0',
        false
    );

    $chunk_vendors_js = asset('js/chunk-vendors.js');
    wp_enqueue_script(
        'chunk-vendors',
        $chunk_vendors_js->getUri(),
        [],
        sha1_file($chunk_vendors_js->getPath()),
        true
    );

    $admin_js = asset('js/admin.js');
    wp_enqueue_script(
        'admin',
        $admin_js->getUri(),
        ['chunk-vendors'],
        sha1_file($admin_js->getPath()),
        true
    );

    $vanilla_admin_js = asset('js/vanilla-admin.js');
    wp_enqueue_script(
        'vanilla-admin',
        $vanilla_admin_js->getUri(),
        ['chunk-vendors', 'chartist_js'],
        sha1_file($vanilla_admin_js->getPath()),
        true
    );

    /*
     * CSS
     */
    if (!webpack_dev_server()) {
        $admin_css = asset('css/admin.css');
        wp_enqueue_style('app', $admin_css->getUri(), [], sha1_file($admin_css->getPath()));
    }
};
add_action( 'admin_enqueue_scripts', 'Tonik\Theme\App\Http\register_admin_scripts_and_styles' );


/**
 * Add 'async' and 'defer' to <script> tags
 *
 * Use 'async' in development because a ressource with timeout
 * can block the whole website otherwise
 *
 * User 'defer' in production otherwise jquery-dependencies throw errors
 */
function add_async_defer($tag, $handle) {
    if(is_admin()) return $tag;
    return str_replace( ' src', ' defer src', $tag );
}
add_filter('script_loader_tag', 'Tonik\Theme\App\Http\add_async_defer', 10, 2);
