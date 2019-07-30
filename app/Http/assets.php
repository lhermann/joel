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

/**
 * Registers theme stylesheet files.
 *
 * @return void
 */
function register_stylesheets() {

    wp_enqueue_style('app', asset_path('css/app.css'), [], sha1_file(asset('css/app.css')->getPath()));
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

    wp_enqueue_script('app', asset_path('js/app.js'), ['jquery'], sha1_file(asset('js/app.js')->getPath()), true);
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
    add_editor_style(asset_path('css/editor-styl.css'));
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

    // Development:
    // wp_enqueue_script( 'vue_js', "https://cdn.jsdelivr.net/npm/vue/dist/vue.js", [], "v2.5.18", false );

    // Production:
    wp_enqueue_script( 'datefns_js', "https://cdnjs.cloudflare.com/ajax/libs/date-fns/1.29.0/date_fns.min.js", [], "", false );
    wp_enqueue_script( 'vue_js', "https://cdn.jsdelivr.net/npm/vue", [], "v2.5.18", false );
    wp_enqueue_script( 'chartist_js', "//cdn.jsdelivr.net/chartist.js/latest/chartist.min.js", [], "0.11.0", false );
    wp_enqueue_script( 'admin_js', asset_path('js/admin.js'), [], sha1_file(asset('js/admin.js')->getPath()), true );

    /*
     * CSS
     */
    // wp_enqueue_style( 'chartist_css', "//cdn.jsdelivr.net/chartist.js/latest/chartist.min.css", [], "0.11.0" ); <-- included in admin.css
    wp_enqueue_style( 'admin_css', asset_path('css/admin.css'), [], sha1_file(asset('css/admin.css')->getPath()) );
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
