<?php

namespace AppTheme\Http;

/*
|-----------------------------------------------------------
| Additional Routes
|-----------------------------------------------------------
|
| Add routes mainly to create archive pages for series, speakers, topics and
| podcasts.
|
*/

use function AppTheme\config;


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
add_action('init', 'AppTheme\Http\gladtidings_get_variables', 10, 0);


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

        __('series', config('textdomain')).'/?$'
            => "index.php?archive=series",

        __('speakers', config('textdomain')).'/?$'
            => "index.php?archive=speakers",

        __('topcis', config('textdomain')).'/?$'
            => "index.php?archive=topics",

        __('podcasts', config('textdomain')).'/?$'
            => "index.php?archive=podcasts",
    );

    // Add new rules to existing rules
    $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
}
add_action( 'generate_rewrite_rules', 'AppTheme\Http\additional_rewrite_rules' );





function include_additional_templates( $template ) {

    if( $archive = get_query_var( 'archive' ) ) {
        $new_template = locate_template( [ 'archive-'.$archive.'.php', 'archive.php' ] );
        if ( $new_template ) return $new_template;
    }
    return $template;
}
add_filter( 'template_include', 'AppTheme\Http\include_additional_templates', 99 );
