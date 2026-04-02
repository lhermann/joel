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
    // Slider service removed
}
add_action('init', 'Tonik\Theme\App\Setup\bind_services');
