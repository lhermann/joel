<?php

namespace Tonik\Theme\App\Structure;

/*
|----------------------------------------------------------------
| Theme Navigation Areas
|----------------------------------------------------------------
|
| This file is for registering your theme custom navigation areas
| where various menus can be assigned by site administrators.
|
*/

use function Tonik\Theme\App\config;

/**
 * Registers navigation areas.
 *
 * @return void
 */
function register_navigation_areas()
{
    register_nav_menus([
        'primary'    => __('Primary Menu', config('textdomain')),
        'flyin'      => __('Flyin Menu', config('textdomain')),
        'footer'     => __('Footer Menu', config('textdomain')),
        // 'footer_sitemap' => __('Footer Sitemap', config('textdomain'))
    ]);
}
add_action('after_setup_theme', 'Tonik\Theme\App\Structure\register_navigation_areas');
