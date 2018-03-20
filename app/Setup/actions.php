<?php

namespace AppTheme\Setup;

/*
|-----------------------------------------------------------
| Theme Actions
|-----------------------------------------------------------
|
| This file purpose is to include your theme custom
| actions hooks, which allows to process various
| logic is specifed parts of your system.
|
*/

use function AppTheme\template;

/**
 * Renders post thumbnail by its formats.
 *
 * @see do_action('theme/index/post/thumbnail')
 * @uses resources/templates/partials/post/thumbnail-{format}.tpl.php
 */
function render_post_thumbnail() {
    template(['partials/post/thumbnail', get_post_format()]);
}
add_action('theme/index/post/thumbnail', 'AppTheme\Setup\render_post_thumbnail');

/**
 * Renders post contents by its formats.
 *
 * @see do_action('theme/index/post/content')
 * @uses resources/templates/partials/post/content-{format}.tpl.php
 */
function render_post_content() {
    template(['partials/post/content', get_post_format()]);
}
add_action('theme/single/content', 'AppTheme\Setup\render_post_content');

/**
 * Renders empty post content where there is no posts.
 *
 * @see do_action('theme/index/content/none')
 * @uses resources/templates/partials/index/content-none.tpl.php
 */
function render_empty_content() {
    template(['partials/index/content', 'none']);
}
add_action('theme/index/content/none', 'AppTheme\Setup\render_empty_content');

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
add_action('theme/index/sidebar', 'AppTheme\Setup\render_sidebar');
add_action('theme/single/sidebar', 'AppTheme\Setup\render_sidebar');

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
add_action('after_switch_theme', 'AppTheme\Setup\joel_setup_options');

/**
 * Remove 'Links' from Admin Dashboard
 */
function remove_admin_menu_pages() {
    remove_menu_page('link-manager.php');
}
add_action( 'admin_menu', 'AppTheme\Setup\remove_admin_menu_pages' );
