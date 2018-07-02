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
use Tonik\Gin\Foundation\Theme;
use WP_Query;

/**
 * Bind all the services
 *
 * @return void
 */
function bind_services()
{
    /**
     * Retreive all the slides and allready get all the fields
     */
    theme()->bind('slides', function (Theme $theme, $parameters) {
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
