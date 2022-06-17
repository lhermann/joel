<?php

namespace Tonik\Theme\App\Setup;
use function Tonik\Theme\App\template;
use function Tonik\Theme\App\config;
use function Tonik\Theme\App\asset_path;
use function Tonik\Theme\App\template_path;

/*
|-----------------------------------------------------------
| Theme Actions
|-----------------------------------------------------------
|
| This file purpose is to include your theme custom
|
*/


/**
 * Locale
 */
add_action('init', 'Tonik\Theme\App\Setup\set_locale');

function set_locale() {
  setlocale(LC_TIME, get_locale());
}


/**
 * Include the TGM_Plugin_Activation class.
 *
 * Depending on your implementation, you may want to change the include call:
 *
 * Parent Theme:
 * require_once get_template_directory() . '/path/to/class-tgm-plugin-activation.php';
 *
 * Child Theme:
 * require_once get_stylesheet_directory() . '/path/to/class-tgm-plugin-activation.php';
 *
 * Plugin:
 * require_once dirname( __FILE__ ) . '/path/to/class-tgm-plugin-activation.php';
 */
require_once get_template_directory() . '/third_party/TGM-Plugin-Activation-2.6.1/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'Tonik\Theme\App\Setup\register_required_plugins' );

function register_required_plugins() {

  $plugins = array(
    array(
      'name'              => 'Advanced Custom Fields PRO',
      'slug'              => 'advanced-custom-fields-pro',
      'required'          => true,
      'version'           => '5.7.7',
      'force_activation'  => true,
    ),
    array(
      'name'              => 'ACF to REST API',
      'slug'              => 'acf-to-rest-api',
      'required'          => true,
      'version'           => '3.1.0',
      'force_activation'  => true,
    ),
    array(
      'name'              => 'WordPress Popular Posts',
      'slug'              => 'wordpress-popular-posts',
      'required'          => true,
      'version'           => '4.2.0',
      'force_activation'  => true,
    )
  );

  if(config('livestream')['enabled']) {
    $plugins[] = array(
      'name'              => 'Event Organiser',
      'slug'              => 'event-organiser',
      'required'          => true,
      'version'           => '3.7.4',
      'force_activation'  => true,
    );
  }

  $config = array(
    'id'           => 'joel',                  // Unique ID for hashing notices for multiple instances of TGMPA.
    'default_path' => '',                      // Default absolute path to bundled plugins.
    'menu'         => 'tgmpa-install-plugins', // Menu slug.
    'parent_slug'  => 'themes.php',            // Parent menu slug.
    'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
    'has_notices'  => true,                    // Show admin notices or not.
    'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
    'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
    'is_automatic' => false,                   // Automatically activate plugins after installation or not.
    'message'      => '',                      // Message to output right before the plugins table.

    /*
    'strings'      => array(
      'page_title'                      => __( 'Install Required Plugins', 'textdomain' ),
      'menu_title'                      => __( 'Install Plugins', 'textdomain' ),
      /* translators: %s: plugin name. * /
      'installing'                      => __( 'Installing Plugin: %s', 'textdomain' ),
      /* translators: %s: plugin name. * /
      'updating'                        => __( 'Updating Plugin: %s', 'textdomain' ),
      'oops'                            => __( 'Something went wrong with the plugin API.', 'textdomain' ),
      'notice_can_install_required'     => _n_noop(
        /* translators: 1: plugin name(s). * /
        'This theme requires the following plugin: %1$s.',
        'This theme requires the following plugins: %1$s.',
        'textdomain'
      ),
      'notice_can_install_recommended'  => _n_noop(
        /* translators: 1: plugin name(s). * /
        'This theme recommends the following plugin: %1$s.',
        'This theme recommends the following plugins: %1$s.',
        'textdomain'
      ),
      'notice_ask_to_update'            => _n_noop(
        /* translators: 1: plugin name(s). * /
        'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.',
        'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.',
        'textdomain'
      ),
      'notice_ask_to_update_maybe'      => _n_noop(
        /* translators: 1: plugin name(s). * /
        'There is an update available for: %1$s.',
        'There are updates available for the following plugins: %1$s.',
        'textdomain'
      ),
      'notice_can_activate_required'    => _n_noop(
        /* translators: 1: plugin name(s). * /
        'The following required plugin is currently inactive: %1$s.',
        'The following required plugins are currently inactive: %1$s.',
        'textdomain'
      ),
      'notice_can_activate_recommended' => _n_noop(
        /* translators: 1: plugin name(s). * /
        'The following recommended plugin is currently inactive: %1$s.',
        'The following recommended plugins are currently inactive: %1$s.',
        'textdomain'
      ),
      'install_link'                    => _n_noop(
        'Begin installing plugin',
        'Begin installing plugins',
        'textdomain'
      ),
      'update_link'                     => _n_noop(
        'Begin updating plugin',
        'Begin updating plugins',
        'textdomain'
      ),
      'activate_link'                   => _n_noop(
        'Begin activating plugin',
        'Begin activating plugins',
        'textdomain'
      ),
      'return'                          => __( 'Return to Required Plugins Installer', 'textdomain' ),
      'plugin_activated'                => __( 'Plugin activated successfully.', 'textdomain' ),
      'activated_successfully'          => __( 'The following plugin was activated successfully:', 'textdomain' ),
      /* translators: 1: plugin name. * /
      'plugin_already_active'           => __( 'No action taken. Plugin %1$s was already active.', 'textdomain' ),
      /* translators: 1: plugin name. * /
      'plugin_needs_higher_version'     => __( 'Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.', 'textdomain' ),
      /* translators: 1: dashboard link. * /
      'complete'                        => __( 'All plugins installed and activated successfully. %1$s', 'textdomain' ),
      'dismiss'                         => __( 'Dismiss this notice', 'textdomain' ),
      'notice_cannot_install_activate'  => __( 'There are one or more required or recommended plugins to install, update or activate.', 'textdomain' ),
      'contact_admin'                   => __( 'Please contact the administrator of this site for help.', 'textdomain' ),

      'nag_type'                        => '', // Determines admin notice type - can only be one of the typical WP notice classes, such as 'updated', 'update-nag', 'notice-warning', 'notice-info' or 'error'. Some of which may not work as expected in older WP versions.
    ),
    */
  );

  tgmpa( $plugins, $config );
}


/**
 * Renders post thumbnail by its formats.
 *
 * @see do_action('theme/index/post/thumbnail')
 * @uses resources/templates/partials/post/thumbnail-{format}.tpl.php
 */
function render_post_thumbnail() {
  template(['partials/post/thumbnail', get_post_format()]);
}
add_action('theme/index/post/thumbnail', 'Tonik\Theme\App\Setup\render_post_thumbnail');

/**
 * Renders post contents by its formats.
 *
 * @see do_action('theme/index/post/content')
 * @uses resources/templates/partials/post/content-{format}.tpl.php
 */
function render_post_content() {
  template(['partials/post/content', get_post_format()]);
}
add_action('theme/single/content', 'Tonik\Theme\App\Setup\render_post_content');

/**
 * Renders empty post content where there is no posts.
 *
 * @see do_action('theme/index/content/none')
 * @uses resources/templates/partials/index/content-none.tpl.php
 */
function render_empty_content() {
  template(['partials/index/content', 'none']);
}
add_action('theme/index/content/none', 'Tonik\Theme\App\Setup\render_empty_content');

/**
 * Renders sidebar content.
 *
 * @see do_action('theme/index/sidebar')
 * @see do_action('theme/single/sidebar')
 * @uses resources/templates/partials/sidebar.tpl.php
 */
function render_sidebar() {
  get_sidebar();
}
add_action('theme/index/sidebar', 'Tonik\Theme\App\Setup\render_sidebar');
add_action('theme/single/sidebar', 'Tonik\Theme\App\Setup\render_sidebar');

/**
 * After Theme Switch
 */
function joel_setup_options () {
  //migrate joel db
  global $wpdb;

  // Posts
  $wpdb->query( "UPDATE $wpdb->posts SET post_type = 'recordings' WHERE post_type = 'video';" );

  // Postmeta
  $wpdb->query( "UPDATE $wpdb->postmeta SET meta_key = REPLACE(meta_key,'video','recording') WHERE meta_key LIKE '%video%';" );
  $wpdb->query( "UPDATE $wpdb->postmeta SET meta_key = REPLACE(meta_key,'recording_thumbnail','thumbnail') WHERE meta_key LIKE '%recording_thumbnail';" );
  $wpdb->query( "UPDATE $wpdb->postmeta SET meta_key = REPLACE(meta_key,'sprecher','speakers') WHERE meta_key LIKE '%sprecher';" );
  $wpdb->query( "UPDATE $wpdb->postmeta SET meta_key = REPLACE(meta_key,'serien','series') WHERE meta_key LIKE '%serien';" );
  $wpdb->query( "UPDATE $wpdb->postmeta SET meta_key = REPLACE(meta_key,'themen','topics') WHERE meta_key LIKE '%themen';" );

  // Term_taxonomy
  $wpdb->query( "UPDATE $wpdb->term_taxonomy SET taxonomy = 'series' WHERE taxonomy = 'video_serien';" );
  $wpdb->query( "UPDATE $wpdb->term_taxonomy SET taxonomy = 'speakers' WHERE taxonomy = 'video_sprecher';" );
  $wpdb->query( "UPDATE $wpdb->term_taxonomy SET taxonomy = 'topics' WHERE taxonomy = 'video_themen';" );

  // Termmeta
  $wpdb->query( "UPDATE $wpdb->termmeta SET meta_key = REPLACE(meta_key,'video_sprecher_website','website') WHERE meta_key LIKE '%video_sprecher_website';" );
  $wpdb->query( "UPDATE $wpdb->termmeta SET meta_key = REPLACE(meta_key,'video_sprecher_bild','image') WHERE meta_key LIKE '%video_sprecher_bild';" );
  $wpdb->query( "UPDATE $wpdb->termmeta SET meta_key = REPLACE(meta_key,'video_serien_bild','image') WHERE meta_key LIKE '%video_serien_bild';" );

  $wpdb->query( "UPDATE $wpdb->termmeta SET meta_key = REPLACE(meta_key,'video_serien','series') WHERE meta_key LIKE '%video_serien%';" );
  $wpdb->query( "UPDATE $wpdb->termmeta SET meta_key = REPLACE(meta_key,'video_sprecher','speakers') WHERE meta_key LIKE '%video_sprecher%';" );
  $wpdb->query( "UPDATE $wpdb->termmeta SET meta_key = REPLACE(meta_key,'video_themen','topics') WHERE meta_key LIKE '%video_themen%';" );


  // $wpdb->query( "UPDATE $wpdb->posts SET post_type = 'recordings' WHERE post_type = 'recording';" );
  // $wpdb->query( "UPDATE $wpdb->posts SET post_type = 'slides' WHERE post_type = 'slide';" );
}
add_action('after_switch_theme', 'Tonik\Theme\App\Setup\joel_setup_options');

/**
 * Remove 'Links' from Admin Dashboard
 */
function remove_admin_menu_pages() {
  remove_menu_page('link-manager.php');
}
add_action( 'admin_menu', 'Tonik\Theme\App\Setup\remove_admin_menu_pages' );


/**
 * Add javascript global vars
 */
function jsglobal() {
  print('<script type="text/javascript">');
  printf('window._joel = { templatePath: "%s", assetPath: "%s" }',
    get_template_directory_uri().'/',
    get_template_directory_uri().'/'.config('directories')['public'].'/'
  );
  print('</script>');
}
add_action( 'wp_footer', 'Tonik\Theme\App\Setup\jsglobal' );

