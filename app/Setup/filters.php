<?php

namespace App\Theme\Setup;

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

/**
 * Hides sidebar on index template on specific views.
 *
 * @see apply_filters('theme/index/sidebar/visibility')
 * @see apply_filters('theme/single/sidebar/visibility')
 */
function show_index_sidebar($status)
{
    if (is_404() || is_page()) {
        return false;
    }

    return $status;
}
add_filter('theme/index/sidebar/visibility', 'App\Theme\Setup\show_index_sidebar');
add_filter('theme/single/sidebar/visibility', 'App\Theme\Setup\show_index_sidebar');

/**
 * Shortens posts excerpts to 60 words.
 *
 * @return integer
 */
function modify_excerpt_length()
{
    return 60;
}
add_filter('excerpt_length', 'App\Theme\Setup\modify_excerpt_length');
