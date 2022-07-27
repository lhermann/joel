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
add_action('wp_enqueue_scripts', 'Tonik\Theme\App\Http\register_scripts_and_styles', 10);
function register_scripts_and_styles() {

  //
  // Scripts
  //

  $main_js = get_asset_by_name('vue-main.js');
  if (file_exists($main_js->getPath())) {
    wp_enqueue_script(
      'vue-main',
      $main_js->getUri(),
      [],
      sha1_file($main_js->getPath()),
      true
    );
  }

  $vanilla_main_js = get_asset_by_name('vanilla-main.js');
  if (file_exists($vanilla_main_js->getPath())) {
    wp_enqueue_script(
      'vanilla-main',
      $vanilla_main_js->getUri(),
      [],
      sha1_file($vanilla_main_js->getPath()),
      true
    );
  }

  wp_deregister_script('algolia-instantsearch');
  wp_deregister_script('algolia-autocomplete');

  //
  // Legacy Scripts
  //
  $chunk_vendors_legacy_js = asset('assets/chunk-vendors.legacy.js');
  if (file_exists($chunk_vendors_legacy_js->getPath())) {
    wp_enqueue_script(
      'chunk-vendors-legacy',
      $chunk_vendors_legacy_js->getUri(),
      [],
      sha1_file($chunk_vendors_legacy_js->getPath()),
      true
    );
  }

  $vanilla_legacy_js = asset('assets/vanilla.legacy.js');
  if (file_exists($vanilla_legacy_js->getPath())) {
    wp_enqueue_script(
      'vanilla-legacy',
      $vanilla_legacy_js->getUri(),
      ['chunk-vendors-legacy'],
      sha1_file($vanilla_legacy_js->getPath()),
      true
    );
  }

  //
  // Styles
  //

  $main_css = get_asset_by_name('main.css');
  if (file_exists($main_css->getPath())) {
    wp_enqueue_style('main', $main_css->getUri(), [], sha1_file($main_css->getPath()));
  }

  wp_deregister_style('algolia-instantsearch');
  wp_deregister_style('algolia-autocomplete');

}

/**
 * Registers editor stylesheets.
 *
 * @return void
 */
add_action('admin_init', 'Tonik\Theme\App\Http\register_editor_stylesheets');
function register_editor_stylesheets() {
  $main_css = get_asset_by_name('main.css');
  if (file_exists($main_css->getPath())) {
    add_editor_style($main_css->getPath());
  }
}

/**
 * Registers admin scripts and stylesheets.
 *
 * @return void
 */
add_action('admin_enqueue_scripts', 'Tonik\Theme\App\Http\register_admin_scripts_and_styles');
function register_admin_scripts_and_styles() {
  /*
   * Javascript
   */
  wp_enqueue_script(
    'chartist',
    '//cdn.jsdelivr.net/chartist.js/latest/chartist.min.js',
    [],
    '0.11.0',
    false
  );

  // $admin_js = asset('js/admin.js');
  $admin_js = get_asset_by_name('vue-admin.js');
  if (file_exists($admin_js->getPath())) {
    wp_enqueue_script(
      'vue-admin',
      $admin_js->getUri(),
      [],
      sha1_file($admin_js->getPath()),
      true
    );
  }

  // TEMP DISABLED
  $vanilla_admin_js = get_asset_by_name('vanilla-admin.js');
  if (file_exists($vanilla_admin_js->getPath())) {
    wp_enqueue_script(
      'vanilla-admin',
      $vanilla_admin_js->getUri(),
      ['chartist'],
      sha1_file($vanilla_admin_js->getPath()),
      true
    );
  }

  /*
   * CSS
   */
  $admin_css = get_asset_by_name('admin.css');
  if (file_exists($admin_css->getPath())) {
    wp_enqueue_style('admin', $admin_css->getUri(), [], sha1_file($admin_css->getPath()));
  }
};

/**
 * Moves front-end jQuery script to the footer.
 *
 * @param  \WP_Scripts $wp_scripts
 * @return void
 */
add_action('wp_default_scripts', 'Tonik\Theme\App\Http\move_jquery_to_the_footer');
function move_jquery_to_the_footer ($wp_scripts) {
  if( is_admin() ) return;
  $wp_scripts->add_data( 'jquery', 'group', 1 );
  $wp_scripts->add_data( 'jquery-core', 'group', 1 );
  $wp_scripts->add_data( 'jquery-migrate', 'group', 1 );
}

/**
 * Add 'async' and 'defer' to <script> tags
 *
 * Use 'async' in development because a ressource with timeout
 * can block the whole website otherwise
 *
 * User 'defer' in production otherwise jquery-dependencies throw errors
 */
add_filter('script_loader_tag', 'Tonik\Theme\App\Http\add_async_defer', 10, 2);
function add_async_defer ($tag, $handle) {
  if(is_admin()) return $tag;
  return str_replace( ' src', ' defer src', $tag );
}


/**
 * Add type="module" to scripts
 */
add_filter('script_loader_tag', 'Tonik\Theme\App\Http\add_module_to_script', 10, 3);
function add_module_to_script ($tag, $handle, $src) {
  if (in_array($handle, ['vue-main', 'vue-admin'])) {
    $tag = str_replace('text/javascript', 'module', $tag);
  }

  return $tag;
}

//
// Helper Functions
//

/**
 * Return the first matching asset
 * Example:
 *   - $asset_name = 'vue-admin.js'
 *   - returns 'dist/assets/vue-main.4ac13b6a.js'
 * @param  [string] $asset_name
 * @return [Asset]
 */
function get_asset_by_name ($asset_name) {
  $dir = scandir(dirname(dirname(__DIR__)) . '/dist/assets');
  $name_parts = explode('.', $asset_name);
  $regex = '/^' . $name_parts[0] . '.+\.' . end($name_parts) . '$/im';

  foreach ($dir as $file) {
    if (preg_match($regex, $file)) {
      return asset('assets/' . $file);
    }
  }

}
