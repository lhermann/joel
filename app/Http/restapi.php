<?php

namespace Tonik\Theme\App\Http;

/*
|-----------------------------------------------------------
| Theme REST API Actions
|-----------------------------------------------------------
|
| Custom additions to Word Press' REST API
|
*/

use function Tonik\Theme\App\config;
use function Tonik\Theme\App\Helper\get_terms_associated_with_term;
use function Tonik\Theme\App\Helper\count_terms_associated_with_term;
use function Tonik\Theme\App\Helper\fallback_img;

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
            return \Tonik\Theme\App\Legacy\get_video_length($object['id']);
        }
    ) );

    /**
     * Add 'thumbnail' to recordings
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
     * Add 'speakers' to series and recordings
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
     * Add the 'series_count' to speakers
     */
    register_rest_field(
        [ 'speakers' ],
        'series_count',
        [ 'get_callback' => function( $object ) {
            return count_terms_associated_with_term( $object['id'], 'series' );
        } ]
    );

    /**
     * Add the 'subtopics_count' to topics
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
     * Add the 'views' to recordings
     */
    register_rest_field(
        [ 'recordings' ],
        'views',
        [ 'get_callback' => function( $object ) {
            return (int) wpp_get_views( $object['id'] );
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
    //         return json_encode( "Hello" );
    //     }
    // ) );

};
add_action( 'rest_api_init', 'Tonik\Theme\App\Http\rest_api_additions' );


/**
 * Add route to get events from event organiser
 */
function event_organiser_endpoint() {
    /**
     * Add a route
     */
    register_rest_route( config('textdomain').'/v1', '/events', array(
        'methods' => \WP_REST_Server::READABLE,
        'callback' => function( \WP_REST_Request $request ) {
            $params = [
                'showpastevents' => false,
                'post_status' => 'publish'
            ];
            $events = eo_get_events(
                array_merge($params, $request->get_params())
            );
            foreach ($events as &$event) {
                $event->today = $event->StartDate == date('Y-m-d');
                $event->now = $event->today
                    && strtotime($event->StartTime) <= current_time('timestamp')
                    && current_time('timestamp') <= strtotime($event->FinishTime);
                $event->thumbnail = get_the_post_thumbnail( $event->ID, '72p', array( 'class' => 'u-rounded u-hidden-until@desktop', 'width' => '80px' ));
            }
            return $events;
        }
    ) );

}
add_action( 'rest_api_init', 'Tonik\Theme\App\Http\event_organiser_endpoint' );
