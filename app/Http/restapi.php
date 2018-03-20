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

use function AppTheme\Helper\get_video_length;
use function AppTheme\config;

function rest_api_additions() {

    /**
     * Add a 'length' field showing the length of the recording
     */
    register_rest_field( 'recording', 'length', array(
        'get_callback' => function( $object ) {
            return get_video_length($object['id']);
        }
    ) );

    /**
     * Add the preview thumbnail for recordings
     */
    register_rest_field( 'recording', 'thumbnail', array(
        'get_callback' => function( $object ) {
            return wp_get_attachment_image_src($object['acf']['thumbnail'], '108p')[0];
        }
    ) );

    /**
     * Add the links for the speakers
     */
    register_rest_field( 'recording', 'speakers', array(
        'get_callback' => function( $object ) {
            $speakers = [];
            foreach ( wp_get_post_terms( $object['id'], 'speakers' ) as $i => $term ) {
                $speakers[] = array(
                    'id' => $term->term_id,
                    'name' => $term->name,
                    'link' => get_term_link( $term )
                );
            }
            return $speakers;
        }
    ) );

    /**
     * Add a human readable date (and take the date-burden off js' back)
     */
    register_rest_field( 'recording', 'date_human', array(
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
