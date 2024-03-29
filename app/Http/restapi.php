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
use function Tonik\Theme\App\Legacy\get_video_files;
use Tonik\Theme\App\Helper\Google_API;


/**
 * Add 'lastname' to the orderby options for speakers
 */
function speakers_add_orderby_params( $params ) {
  $params['orderby']['enum'][] = 'lastname';
  return $params;
}
add_filter('rest_speakers_collection_params', 'Tonik\Theme\App\Http\speakers_add_orderby_params', 10, 1);


/**
 * Manipulate the query for [orderby]=lastname to that all terms are fetched from the
 * database. We will sort and slice them alter
 */
function speakers_lastname_query( $prepared_args ) {
  if($prepared_args['orderby'] === 'lastname') {
    $prepared_args['number'] = 999;
    $prepared_args['offset'] = 0;
  }
  return $prepared_args;
}
add_filter('rest_speakers_query', 'Tonik\Theme\App\Http\speakers_lastname_query', 10, 1);


/**
 * Catch the result before it is sent by the rest API and modify it so that the
 * 'lastname' sorting actually works for speakers
 */
function speakers_catch_response($result, $server, $request) {
  $params = $request->get_params();
  if(array_key_exists('orderby', $params) && $params['orderby'] === 'lastname') {
    setlocale(LC_COLLATE, get_locale());
    if(function_exists("transliterator_transliterate")) { // php-intl has to be installed
      usort($result->data, function($a, $b) {
        $transliterator = 'Any-Latin; Latin-ASCII; Lower()';
        $a2 = transliterator_transliterate($transliterator, $a['lastname']);
        $b2 = transliterator_transliterate($transliterator, $b['lastname']);
        return strcoll($a2, $b2);
      });
    };
    $count = count($result->data);
    $offset = ($params['page'] - 1) * $params['per_page'];
    $length = $params['per_page'];
    $result->data = array_slice($result->data, $offset, $length);
    $result->headers['X-WP-TotalPages'] = ceil($count / $length);
  }
  return $result;
}
add_action('rest_post_dispatch', 'Tonik\Theme\App\Http\speakers_catch_response', 10, 3);



function rest_api_additions() {

  /**
   * Add a 'type' field
   */
  register_rest_field(
    [ 'series', 'speakers', 'topics' ],
    'type',
    [ 'get_callback' => function( $object ) {
      return $object['taxonomy'];
    } ]
  );

  /**
   * Add a 'mediatype' field to recordings
   */
  register_rest_field(
    [ 'recordings' ],
    'mediatype',
    [ 'get_callback' => function( $object ) {
      return 'video';
    } ]
  );

  /**
   * Add a 'length' field showing the length of the recordings
   */
  register_rest_field(
    'recordings',
    'length',
    [ 'get_callback' => function( $object ) {
        return \Tonik\Theme\App\Legacy\get_video_length($object['id']);
    } ]
  );

  /**
   * Add 'thumbnail' to recordings
   */
  register_rest_field(
    [ 'recordings', 'series', 'speakers' ],
    'thumbnail',
    [ 'get_callback' => function ($payload) {
      // regular thumbnail
      switch ($payload['type']) {
        case 'recordings':
          $id = get_field('thumbnail', $payload['id']);
          $res = '108p';
          break;
        case 'series':
          $id = get_field('image', 'series_'.$payload['id']);
          $res = '144p';
          break;
        case 'speakers':
          $id = get_field('image', 'speakers_'.$payload['id']);
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

  register_rest_field(
    ['recordings'],
    'youtube_id',
    ['get_callback' => function ($payload) {
      // return get_field('youtube_video', $payload['id']);
      $yt_string = get_field('youtube_video', $payload['id']);
      if ($yt_string) {
        preg_match_all(
          '/(?<=embed\/)(?<id>.+?)(?=[\?$])/',
          $yt_string,
          $matches,
          PREG_SET_ORDER,
          0
        );
        return $matches[0]['id'];
      }
      return '';
    }],
  );

  /**
   * Add 'speakers' to series and recordings
   */
  register_rest_field(
    [ 'recordings', 'series' ],
    'speakers',
    [ 'get_callback' => function( $object ) {
      if( $object['type'] === "recordings") {
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
   * Add 'series_count' to speakers
   */
  register_rest_field(
    [ 'speakers' ],
    'series_count',
    [ 'get_callback' => function( $object ) {
      return count_terms_associated_with_term( $object['id'], 'series' );
    } ]
  );

  /**
   * Add 'lastname' to speakers
   */
  register_rest_field(
    [ 'speakers' ],
    'lastname',
    [ 'get_callback' => function( $object ) {
      $name = explode(" ", $object['name']);
      return end($name);
    } ]
  );

  /**
   * Add 'subtopics_count' to topics
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
      return wpp_get_views( $object['id'] );
    } ]
  );

  /**
   * Add a human readable date (and take the date-burden off js' back)
   */
  register_rest_field(
    [ 'recordings' ],
    'date_human',
    [ 'get_callback' => function( $object ) {
      return esc_attr( get_the_date('j. F Y') );
    } ]
  );

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
  register_rest_route(
    config('textdomain').'/v1',
    '/events',
    [
      'methods' => \WP_REST_Server::READABLE,
      'callback' => function (\WP_REST_Request $request) {
        $params = [
          'showpastevents' => false,
          'post_status' => 'publish'
        ];
        $events = eo_get_events(
          array_merge($params, $request->get_params())
        );
        foreach ($events as &$event) {
          $event->today = $event->StartDate == current_time('Y-m-d');
          $event->tomorrow = $event->StartDate == current_time('Y-m-') . (current_time('d') + 1);
          $event->now = $event->today
            && strtotime($event->StartTime) <= current_time('timestamp')
            && current_time('timestamp') <= strtotime($event->FinishTime);
          // $event->thumbnail = get_the_post_thumbnail( $event->ID, '72p', array( 'class' => 'u-rounded u-hidden-until@desktop', 'width' => '80px' ));
          $event->excerpt = get_the_excerpt($event->ID);
          $event->url = get_permalink($event);
          $event->thumbnail = get_the_post_thumbnail(
            $event->ID,
            $request->get_param('thumbnail_size') ?: '72p',
            array( 'class' => '%%class%%' )
          );
          $event->venue = eo_get_venue_name(eo_get_venue($event->ID));
        }
        return $events;
      },
      'permission_callback' => '__return_true',
    ]
  );

}
add_action('rest_api_init', 'Tonik\Theme\App\Http\event_organiser_endpoint');


/**
 * Recording endpoints
 */
function recording_endpoints() {

  /*
   * Recording status
   */
  register_rest_route(
    config('textdomain').'/v1',
    '/recording-status/(?P<id>\d+)',
    [
      'methods' => \WP_REST_Server::READABLE,
      'callback' => function (\WP_REST_Request $request) {
        return $video_files = get_video_files($request['id'], 'raw');
      },
      'permission_callback' => '__return_true',
    ]
  );

}
add_action( 'rest_api_init', 'Tonik\Theme\App\Http\recording_endpoints' );

/**
 * Google API Callback
 */
function google_api_callback() {

  /*
   * Recording status
   */
  register_rest_route(
    config('textdomain').'/v1',
    '/google-api-callback',
    [
      'methods' => \WP_REST_Server::READABLE,
      'callback' => function (\WP_REST_Request $request) {
        if(!$_GET['code']) return 'code missing';
        $client = new Google_API();
        $client->authenticate($_GET['code']);
        wp_redirect(admin_url());
        exit();
      },
      'permission_callback' => '__return_true',
    ]
  );

}
add_action('rest_api_init', 'Tonik\Theme\App\Http\google_api_callback');
