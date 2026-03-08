<?php

namespace Tonik\Theme\App\Setup;

/*
|-----------------------------------------------------------
| Theme Custom Services
|-----------------------------------------------------------
|
| This file is for registering your third-parity services
| or custom logic within theme container, so they can
| be easily used for a theme template files later.
|
*/

use function Tonik\Theme\App\theme;
use WP_Query;

/**
 * Bind theme services into the container.
 *
 * Services registered here are singletons — resolved once on first
 * access via theme('service_name'), then cached for the request.
 */
function bind_services()
{
    /**
     * Pre-load all slides with their ACF fields.
     * Access via: theme('slides') → array of WP_Post objects with ACF fields attached.
     */
    theme()->bind('slides', function ($container, $parameters) {
        $query = new WP_Query([
            'post_type' => 'slides',
        ]);
        foreach ($query->posts as $i => $post) {
            $fields = get_fields($post->ID);
            foreach ($fields as $key => $field) {
                $query->posts[$i]->$key = $field;
            }
        }
        return $query->posts;
    });
}
add_action('init', 'Tonik\Theme\App\Setup\bind_services');
