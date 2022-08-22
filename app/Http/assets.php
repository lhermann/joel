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

use function Tonik\Theme\App\config;
use function Tonik\Theme\App\asset;
use function Tonik\Theme\App\asset_path;
use function Tonik\Theme\App\vite_dev_proxy;

/**
 * Make sure it works with vite proxy
 */
if (vite_dev_proxy()) {
  config()->set('directories', [
      'languages' => 'resources/languages',
      'templates' => 'resources/templates',
      'assets' => 'public',
      'public' => 'public',
      'app' => 'app',
  ]);
}

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

  $main_js = get_asset_by_name('assets/vue-main.js');
  if ($main_js) {
    wp_enqueue_script('vue-main', $main_js['uri'], [], $main_js['hash'], true);
  }

  // TODO: refactor main.js
  // $vanilla_main_js = get_asset_by_name('vanilla-main.js');
  // if ($vanilla_main_js) {
  //   wp_enqueue_script('vanilla-main', $vanilla_main_js['uri'], [], $vanilla_main_js['hash'], true);
  // }

  wp_deregister_script('algolia-instantsearch');
  wp_deregister_script('algolia-autocomplete');

  //
  // Legacy Scripts
  //
  $chunk_vendors_legacy_js = asset('assets/chunk-vendors.legacy.js');
  if ($chunk_vendors_legacy_js && file_exists($chunk_vendors_legacy_js->getPath())) {
    wp_enqueue_script(
      'chunk-vendors-legacy',
      $chunk_vendors_legacy_js->getUri(),
      [],
      sha1_file($chunk_vendors_legacy_js->getPath()),
      true
    );
  }

  $vanilla_legacy_js = asset('assets/vanilla.legacy.js');
  if ($vanilla_legacy_js && file_exists($vanilla_legacy_js->getPath())) {
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

  $main_css = get_asset_by_name('assets/main.css');
  if ($main_css) {
    wp_enqueue_style('main-style', $main_css['uri'], [], $main_css['hash']);
  }

  $fonts_css = get_asset_by_name('fonts/style.css');
  if ($fonts_css) {
    wp_enqueue_style('font-style', $fonts_css['uri'], [], $fonts_css['hash']);
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
  $main_css = get_asset_by_name('assets/main.css');
  if ($main_css) {
    add_editor_style($main_css['path']);
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

  $admin_js = get_asset_by_name('assets/vue-admin.js');
  if ($admin_js) {
    wp_enqueue_script('vue-admin', $admin_js['uri'], [], $admin_js['hash'], true);
  }

  // TEMP DISABLED
  $vanilla_admin_js = get_asset_by_name('assets/vanilla-admin.js');
  if ($vanilla_admin_js) {
    wp_enqueue_script('vanilla-admin', $vanilla_admin_js['uri'], ['chartist'], $vanilla_admin_js['hash'], true);
  }

  /*
   * CSS
   */
  $admin_css = get_asset_by_name('assets/admin.css');
  if ($admin_css) {
    wp_enqueue_style('admin', $admin_css['uri'], [], $admin_css['hash']);
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
    if (strpos($tag, 'text/javascript') !== false) {
      $tag = str_replace('text/javascript', 'module', $tag);
    } else {
      $tag = str_replace('<script', '<script type="module"', $tag);
    }
  }

  return $tag;
}

//
// Helper Functions
//

/**
 * Return the first matching asset
 * Example:
 *   - $asset_path = 'assets/vue-admin.js'
 *   - returns 'dist/assets/vue-main.4ac13b6a.js'
 * @param  [string] $asset_name
 * @return [Asset]
 */
function get_asset_by_name ($asset_path) {
  // dev environment
  if (vite_dev_proxy()) {
    $name_map = [
      'assets/vue-main.js' => '/src-vue/main.js',
      'assets/vue-admin.js' => '/src-vue/admin.js',
      'assets/vanilla-main.js' => '/src-vanilla/main.js',
      'assets/vanilla-admin.js' => '/src-vanilla/admin.js',
      'fonts/style.css' => '/fonts/style.css',
    ];
    if (!array_key_exists($asset_path, $name_map)) return null;
    return [
      'uri' => $name_map[$asset_path],
      'path' => null,
      'hash' => null,
    ];
  }

  // prod environment
  if (strpos($asset_path, 'assets') === 0) {
    $dir = scandir(dirname(dirname(__DIR__)) . '/dist/assets');
    $asset_parts = explode('/', $asset_path);
    $asset_name = end($asset_parts);
    $name_parts = explode('.', $asset_name);
    $regex = '/^' . $name_parts[0] . '.+\.' . end($name_parts) . '$/im';

    foreach ($dir as $file) {
      if (preg_match($regex, $file)) {
        $asset = asset('assets/' . $file);
        return [
          'uri' => $asset->getUri(),
          'path' => $asset->getPath(),
          'hash' => sha1_file($asset->getPath()),
        ];
      }
    }
  }

  $asset = asset($asset_path);
  return [
    'uri' => $asset->getUri(),
    'path' => $asset->getPath(),
    'hash' => sha1_file($asset->getPath()),
  ];
}
