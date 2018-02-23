<?php

namespace AppTheme\Setup;

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

use function AppTheme\theme;
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
            'post_type' => 'slide',
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
add_action('init', 'AppTheme\Setup\bind_services');
