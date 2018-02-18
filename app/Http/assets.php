<?php

namespace AppTheme\Http;

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

use function AppTheme\asset_path;
use function AppTheme\asset;

/**
 * Registers theme stylesheet files.
 *
 * @return void
 */
function register_stylesheets() {
    // wp_enqueue_style('foundation', asset_path('css/foundation.css'));
    wp_enqueue_style('app', asset_path('css/app.css'));
}
add_action('wp_enqueue_scripts', 'AppTheme\Http\register_stylesheets');

/**
 * Registers theme script files.
 *
 * @return void
 */
function register_scripts() {
    // wp_enqueue_script('foundation', asset_path('js/foundation.js'), ['jquery'], null, true);
    wp_enqueue_script('app', asset_path('js/app.js'), [], null, true);
}
add_action('wp_enqueue_scripts', 'AppTheme\Http\register_scripts');

/**
 * Registers editor stylesheets.
 *
 * @return void
 */
function register_editor_stylesheets() {
    // add_editor_style(asset_path('css/foundation.css'));
    add_editor_style(asset_path('css/app.css'));
}
add_action('admin_init', 'AppTheme\Http\register_editor_stylesheets');

/**
 * Moves front-end jQuery script to the footer.
 *
 * @param  \WP_Scripts $wp_scripts
 * @return void
 */
function move_jquery_to_the_footer($wp_scripts) {
    if (! is_admin()) {
        $wp_scripts->add_data('jquery', 'group', 1);
        $wp_scripts->add_data('jquery-core', 'group', 1);
        $wp_scripts->add_data('jquery-migrate', 'group', 1);
    }
}
add_action('wp_default_scripts', 'AppTheme\Http\move_jquery_to_the_footer');

/**
 * Registers admin scripts and stylesheets.
 *
 * @return void
 */
function register_admin_scripts_and_styles() {
    wp_enqueue_style( 'admin_css', asset_path('css/admin.css'), false, filemtime(asset('css/admin.css')->getPath()) );
    wp_enqueue_script( 'admin_js', asset_path('js/admin.js'), [], filemtime(asset('js/admin.js')->getPath()), true );
};
add_action( 'admin_enqueue_scripts', 'AppTheme\Http\register_admin_scripts_and_styles' );
