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
function render_post_thumbnail()
{
    template(['partials/post/thumbnail', get_post_format()]);
}
add_action('theme/index/post/thumbnail', 'AppTheme\Setup\render_post_thumbnail');

/**
 * Renders post contents by its formats.
 *
 * @see do_action('theme/index/post/content')
 * @uses resources/templates/partials/post/content-{format}.tpl.php
 */
function render_post_content()
{
    template(['partials/post/content', get_post_format()]);
}
add_action('theme/single/content', 'AppTheme\Setup\render_post_content');

/**
 * Renders empty post content where there is no posts.
 *
 * @see do_action('theme/index/content/none')
 * @uses resources/templates/partials/index/content-none.tpl.php
 */
function render_empty_content()
{
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
function render_sidebar()
{
    get_sidebar();
}
add_action('theme/index/sidebar', 'AppTheme\Setup\render_sidebar');
add_action('theme/single/sidebar', 'AppTheme\Setup\render_sidebar');

/**
 * Renders [button] shortcode after homepage content.
 *
 * @see do_action('theme/header/end')
 * @uses resources/templates/shortcodes/button.tpl.php
 */
function render_documentation_button()
{
    echo do_shortcode("[button href='https://github.com/tonik/tonik']Checkout documentation →[/button]");
}
add_action('theme/header/end', 'AppTheme\Setup\render_documentation_button');

/**
 * After Theme Switch
 */
function joel_setup_options () {
    //migrate joel db
    global $wpdb;
    $wpdb->query( "UPDATE $wpdb->posts SET post_type = 'media' WHERE post_type = 'video';" );
    $wpdb->query( "UPDATE $wpdb->postmeta SET meta_key = REPLACE(meta_key,'video','media') WHERE meta_key LIKE '%video%';" );
    $wpdb->query( "UPDATE $wpdb->term_taxonomy SET taxonomy = 'media_series' WHERE taxonomy = 'video_serien';" );
    $wpdb->query( "UPDATE $wpdb->term_taxonomy SET taxonomy = 'media_speakers' WHERE taxonomy = 'video_sprecher';" );
    $wpdb->query( "UPDATE $wpdb->term_taxonomy SET taxonomy = 'media_topics' WHERE taxonomy = 'video_themen';" );
    $wpdb->query( "UPDATE $wpdb->term_taxonomy SET taxonomy = 'media_podcasts' WHERE taxonomy = 'podcasts';" );
    $wpdb->query( "UPDATE $wpdb->termmeta SET meta_key = REPLACE(meta_key,'video_serien','media_series') WHERE meta_key LIKE '%video_serien%';" );
    $wpdb->query( "UPDATE $wpdb->termmeta SET meta_key = REPLACE(meta_key,'video_sprecher','media_speakers') WHERE meta_key LIKE '%video_sprecher%';" );
    $wpdb->query( "UPDATE $wpdb->termmeta SET meta_key = REPLACE(meta_key,'video_themen','media_topics') WHERE meta_key LIKE '%video_themen%';" );
}
add_action('after_switch_theme', 'AppTheme\Setup\joel_setup_options');
