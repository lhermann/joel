<?php

namespace Tonik\Theme\App\Http;

/*
|-----------------------------------------------------------
| Additional Routes
|-----------------------------------------------------------
|
| Add routes mainly to create archive pages for series, speakers, topics and
| podcasts.
|
*/

use function Tonik\Theme\App\config;


/**
 * Add a query variables
 *  'view'          introduction|question|evaluation
 *  'course-name'   slug
 *  'unit-position' int
 */
function gladtidings_get_variables() {
    add_rewrite_tag('%archive%', '([^&]+)');
    // flush_rewrite_rules();
}
add_action('init', 'Tonik\Theme\App\Http\gladtidings_get_variables', 10, 0);


/**
 * Custom URL Routing
 * Tutorial: http://www.hongkiat.com/blog/wordpress-url-rewrite/
 *
 * NOTE: Standard WP rewrite rules are turned off for each custom post type.
 *       Thus these restful routes here are the only way to reach them
 */
function additional_rewrite_rules() {
    global $wp_rewrite;

    // define new rules
    $new_rules = array(

        _x('series', 'http route', config('textdomain')).'/?$'
            => "index.php?archive=series",

        _x('speakers', 'http route', config('textdomain')).'/?$'
            => "index.php?archive=speakers",

        _x('topics', 'http route', config('textdomain')).'/?$'
            => "index.php?archive=topics",

        _x('podcasts', 'http route', config('textdomain')).'/?$'
            => "index.php?archive=podcasts",
    );

    // Add new rules to existing rules
    $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
}
add_action( 'generate_rewrite_rules', 'Tonik\Theme\App\Http\additional_rewrite_rules' );





function include_additional_templates( $template ) {

    if( $archive = get_query_var( 'archive' ) ) {
        $new_template = locate_template( [ 'archive-'.$archive.'.php', 'archive.php' ] );
        if ( $new_template ) return $new_template;
    }
    return $template;
}
add_filter( 'template_include', 'Tonik\Theme\App\Http\include_additional_templates', 99 );
