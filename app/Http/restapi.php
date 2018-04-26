<?php

namespace AppTheme\Http;

/*
|-----------------------------------------------------------
| Theme REST API Actions
|-----------------------------------------------------------
|
| Custom additions to Word Press' REST API
|
*/

use function AppTheme\config;
use function AppTheme\Helper\get_video_length;
use function AppTheme\Helper\get_terms_associated_with_term;
use function AppTheme\Helper\count_terms_associated_with_term;
use function AppTheme\Helper\fallback_img;

function rest_api_additions() {

    /**
     * Add a 'type' field
     */
    register_rest_field(
        [ 'recordings', 'series', 'speakers', 'topics' ],
        'type',
        [ 'get_callback' => function( $object ) {
            if( isset($object['type']) ) {
                return 'video';
            } elseif( isset($object['taxonomy']) ) {
                return $object['taxonomy'];
            }
            return '';
        } ]
    );

    /**
     * Add a 'length' field showing the length of the recordings
     */
    register_rest_field( 'recordings', 'length', array(
        'get_callback' => function( $object ) {
            return get_video_length($object['id']);
        }
    ) );

    /**
     * Add the preview thumbnail for recordings
     */
    register_rest_field(
        [ 'recordings', 'series', 'speakers' ],
        'thumbnail',
        [ 'get_callback' => function( $object ) {
            switch ($object['type']) {
                case 'video':
                case 'audio':
                    $id = $object['acf']['thumbnail'];
                    $res = '108p';
                    break;
                case 'series':
                    $id = get_field( 'image', 'series_'.$object['id'] );
                    $res = '144p';
                    break;
                case 'speakers':
                    $id = get_field( 'image', 'speakers_'.$object['id'] );
                    $res = 'square160';
                    break;
                default:
                    $id = null;
                    $res = '160p';
                    return '';
            }
            return fallback_img(
                wp_get_attachment_image_src( $id, $res )[0],
                $res
            );
        } ]
    );

    /**
     * Add the links for the speakers to series and recordings
     */
    register_rest_field(
        [ 'recordings', 'series' ],
        'speakers',
        [ 'get_callback' => function( $object ) {
            if( in_array($object['type'], ['video', 'audio'])) {
                $terms = wp_get_post_terms( $object['id'], 'speakers' );
            } else {
                $terms = get_terms_associated_with_term( $object['id'], 'speakers' );
            }
            $speakers = [];
            foreach ( $terms as $i => $term ) {
                $speakers[] = array(
                    'id' => $term->term_id,
                    'name' => $term->name,
                    'link' => get_term_link( $term )
                );
            }
            return $speakers;
        } ]
    );

    /**
     * Add the count of series to speakers
     */
    register_rest_field(
        [ 'speakers' ],
        'series_count',
        [ 'get_callback' => function( $object ) {
            return count_terms_associated_with_term( $object['id'], 'series' );
        } ]
    );

    /**
     * Add the count of subtopics to topics
     */
    register_rest_field(
        [ 'topics' ],
        'subtopics_count',
        [ 'get_callback' => function( $object ) {
            $terms = get_terms( 'topics', [ 'child_of' => $object['id'] ]);
            return count($terms);
        } ]
    );

    /**
     * Add a human readable date (and take the date-burden off js' back)
     */
    register_rest_field( 'recordings', 'date_human', array(
        'get_callback' => function( $object ) {
            return esc_attr( get_the_date('j. F Y') );
        }
    ) );

    /**
     * Add a route
     */
    // register_rest_route( config('textdomain').'/v1', '/route', array(
    //     'methods' => 'GET',
    //     'callback' => function( $data ) {
    //         $params = $data->get_params();
    //         return $data;
    //     }
    // ) );

};
add_action( 'rest_api_init', 'AppTheme\Http\rest_api_additions' );
