<?php

namespace Tonik\Theme\App\Setup;

/*
|-----------------------------------------------------------
| Theme Filters
|-----------------------------------------------------------
|
| This file purpose is to include your theme various
| filters hooks, which changes output or behaviour
| of diffrent parts of WordPress functions.
|
*/

// use function Tonik\Theme\App\config;
use function Tonik\Theme\App\template_path;
use function Tonik\Theme\App\Legacy\get_video_length;


/**
 * Modify the path where algolia looks for its templates
 */
function jeol_algolia_template_locations( array $locations, $file ) {
    if ( $file === 'autocomplete.php' ) {
        $locations[] = template_path('algolia/autocomplete');
    } elseif ( $file === 'instantsearch.php' ) {
        $locations[] = template_path('algolia/instantsearch');
    }

    return $locations;
}
add_filter( 'algolia_template_locations', 'Tonik\Theme\App\Setup\jeol_algolia_template_locations', 10, 2 );


/**
 * Exclude post types from algolia indexing
 */
function post_type_blacklist( array $blacklist ) {
    $blacklist[] = 'event';
    $blacklist[] = 'slides';
    $blacklist[] = 'page';

    return $blacklist;
}

add_filter('algolia_post_types_blacklist', 'Tonik\Theme\App\Setup\post_type_blacklist');


function searchable_post_types(array $post_types) {
    return ['recordings', 'posts'];
}
add_filter('algolia_searchable_post_types', 'Tonik\Theme\App\Setup\searchable_post_types');

// function should_index_post( $should_index, \WP_Post $post ) {

//     if( in_array($post->post_type, ['event', 'slides', 'page']) ) {
//         return false ;
//     }

//     return $should_index;
// }
// add_filter('algolia_should_index_post',
//     'Tonik\Theme\App\Setup\should_index_post', 10, 2);
// add_filter('algolia_should_index_searchable_post',
//     'Tonik\Theme\App\Setup\should_index_post', 10, 2);



/**
 * Shared attributes for post-type 'recordings'
 */
function recordings_shared_attributes( array $shared_attributes, \WP_Post $post) {
    $attr = [
        'type' => 'video',
        'length' => get_video_length($post->ID),
        'views' => (int) wpp_get_views($post->ID, null, false),
        'thumbnail' => wp_get_attachment_image_src(get_field('thumbnail', $post->ID), '108p')[0],
        'date_human' => esc_attr(get_the_date('j. F Y', $post->ID)),
        'speakers' => get_the_term_list($post->ID, 'speakers')
    ];

    return array_merge($shared_attributes, $attr);
}
add_filter( 'algolia_post_recordings_shared_attributes',
    'Tonik\Theme\App\Setup\recordings_shared_attributes', 10, 2 );
add_filter( 'algolia_searchable_post_recordings_shared_attributes',
    'Tonik\Theme\App\Setup\recordings_shared_attributes', 10, 2 );



/**
 * Shared attributes for terms 'speakers' & 'series'
 */
function taxonomy_record(array $record, \WP_Term $term) {
    $attr = [
        'type' => $term->taxonomy,
        'thumbnail' => wp_get_attachment_image_src(
            get_field( 'image', $term->taxonomy.'_'.$term->term_id ),
            $term->taxonomy === 'series' ? '144p' : 'square160'
        )[0]
    ];

    return array_merge($record, $attr);
}
add_filter( 'algolia_term_series_record',
    'Tonik\Theme\App\Setup\taxonomy_record', 10, 2 );
add_filter( 'algolia_term_speakers_record',
    'Tonik\Theme\App\Setup\taxonomy_record', 10, 2 );
add_filter( 'algolia_term_topics_record',
    'Tonik\Theme\App\Setup\taxonomy_record', 10, 2 );


/**
 * Search indexes for post-type 'recordings'
 */
function recordings_index_settings( array $settings ) {

    $settings['attributesToIndex'] = [
        'unordered(post_title)'
    ];

    $settings['customRanking'] = [
        'desc(views)',
        'desc(post_date)'
    ];

    $settings['attributesForFaceting'] = [
        'post_type_label',
        'taxonomies.speakers',
        'taxonomies.series',
        'taxonomies.topics',
        'taxonomies.podcasts'
    ];

    $settings['attributesToSnippet'] = [
        'post_title:90',
        'taxonomies.speakers',
        'taxonomies.series'
    ];

    return $settings;
}
add_filter('algolia_posts_recordings_index_settings',
    'Tonik\Theme\App\Setup\recordings_index_settings');


/**
 * Search indexes for terms 'speakers' & 'series'
 */
function terms_index_settings( array $settings ) {

    $settings['attributesToSnippet'] = [
        'name:30',
        'description:30'
    ];

    return $settings;
}
add_filter('algolia_terms_speakers_index_settings', 'Tonik\Theme\App\Setup\terms_index_settings');
add_filter('algolia_terms_series_index_settings', 'Tonik\Theme\App\Setup\terms_index_settings');


