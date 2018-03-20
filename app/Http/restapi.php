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

function rest_api_additions() {

    /**
     * Add a 'type' field
     */
    register_rest_field(
        [ 'recordings', 'series', 'speakers', 'topcis' ],
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
            $res = '108p';
            switch ($object['type']) {
                case 'video':
                case 'audio':
                    $id = $object['acf']['thumbnail'];
                    break;
                case 'series':
                    $id = get_field( 'image', 'series_'.$object['id'] );
                    $res = '144p';
                case 'speakers':
                    break;
                default:
                    return '';
            }
            return wp_get_attachment_image_src( $id, $res )[0];
        } ]
    );

    /**
     * Add the links for the speakers
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
