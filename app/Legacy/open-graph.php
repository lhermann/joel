<?php

namespace Tonik\Theme\App\Legacy;

/*
|-----------------------------------------------------------
| Generate Open Graph
|-----------------------------------------------------------
*/

use function Tonik\Theme\App\config;
use function Tonik\Theme\App\asset_path;

/**
 * Display meta and open graph information in the header
 * @since Joel Media 1.2
 */
function print_open_graph() {

    /*
     * Creating the Meta and Open Graph Information
     * <title>              - Page Title | Site Name | Extensions
     * description          - video and audio: description of series; sprecher, serien and articles use their own description; pages use an excerpt; everything else uses the page tagline
     * author               - the name of the author; default is Christopher Kramp
     * og:title             - Only the current title, no auxilary information
     * og:site_name         - Joel Media Ministry e.V.
     * og:url               - the full permalink
     * og:description       - same as name="description"
     * og:image             - varies depending on the content (e.g. article thumbnail, video thumbnail, sprecher thumbnail, frontpage screenshot)
     * fb:app_id            - The unique ID that lets Facebook know the identity of your site. This is crucial for Facebook Insights to work properly. Please see our Insights documentation to learn more.
     * og:type              - either 'website', 'article, 'video'
     * og:locale            - de_DE
     * article:author       - same as name="author"
     * article:publisher    - JMM Facebook Account
     */

    global $post, $wp_query, $page, $paged;

    /*
     * Ope Graph Title
     */
    $og_title = substr( strtok( wp_title( '¥', false, 'right' ) , '¥' ), 0, -1);
    if(!$og_title) {
        $uri = array_filter(explode("/", $_SERVER['REQUEST_URI']));
        $og_title = ucfirst(end($uri));
    }

    /*
     * URL
     */
    $canonical_url = get_bloginfo( 'url' ).esc_url( $_SERVER['REQUEST_URI'] );

    /*
     * Description
     */
    $description = get_bloginfo('description', 'display');
    if ( is_single() ) {
        switch ( $post->post_type ) {
            case 'recordings':
                $series = wp_get_post_terms( $post->ID, 'series');
                $description = $series[0]->description ?: $description;
                break;
            case 'post':
                $description = $post->post_content;
                break;
        }
    } elseif ( is_archive() && $wp_query->queried_object ) {
        $description = $wp_query->queried_object->description ?: $description;
    } elseif ( is_page() && !is_front_page() ) {
        $description = $post->post_content;
    }
    // cut length
    if ( strlen($description) >= 330 ) {
        preg_match('/^.{1,330}\b/su', $description, $match);
        $description = $match[0].' ...';
    }
    // Strip all the html tags and obscure email
    $description = str_replace ( '@' , '[at]' , strip_tags( $description ) );

    /*
     * Author
     */
    $author = 'Joel Media Ministry e.V.';
    if(is_single()) {
        switch ($post->post_type) {
            case 'recordings':
                $speakers = wp_get_post_terms( $post->ID, 'speakers' );
                $author = $speakers[0]->name;
                break;
            case 'post':
                $author = $post->post_author;
                break;
        }
    }

    /*
     * Image
     */
    $image_url = asset_path('images/dummy-720p.jpg');
    if ( is_single() ) {
        switch ( $post->post_type ) {
            case 'recordings':
                $image_url = wp_get_attachment_image_src(get_field('thumbnail'), '720p')[0] ?: $image_url;
                break;
            case 'post':
                $image_url = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), '720p')[0] ?: $image_url;
                break;
        }
    } elseif ( is_archive() && $wp_query->queried_object ) {
        $object = $wp_query->queried_object;
        switch ($object->taxonomy) {
            case 'series':
                $id = get_field( 'image', 'series_'.$object->term_id);
                $res = '720p';
                break;
            case 'speakers':
                $id = get_field( 'image', 'speakers_'.$object->term_id);
                $res = 'square640';
                break;
            default:
                $id = null;
        }
        if($id) $image_url = wp_get_attachment_image_src($id, $res)[0] ?: $image_url;
    }

    ?>
    <!-- <title><?php wp_title( '&laquo;', true, 'right' ); bloginfo( 'name' ); echo $title_extension; ?></title> -->
    <meta name="description"                content="<?= htmlspecialchars( $description ) ?>">
    <meta name="author"                     content="<?= $author ?>">
    <meta property="og:title"               content="<?= $og_title; ?>" />
    <meta property="og:site_name"           content="<?php bloginfo( 'name' ); ?>" />
    <meta property="og:url"                 content="<?= $canonical_url ?>" />
    <meta property="og:description"         content="<?= htmlspecialchars( $description ) ?>" />
    <meta property="og:image"               content="<?= $image_url ?>" />
    <meta property="og:type"                content="website" />
    <meta property="og:locale"              content="<?php bloginfo('language') ?>" />
    <meta property="article:author"         content="<?= $author ?>" />
    <meta property="article:publisher"      content="https://www.facebook.com/groups/370087426377000/" />
    <meta property="article:published_time" content="<?php the_time( 'c' ); ?>" />
    <meta property="article:modified_time"  content="<?php the_modified_time( 'c' ); ?>" />
    <?php
}
