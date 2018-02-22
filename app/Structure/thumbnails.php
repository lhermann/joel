<?php

namespace AppTheme\Structure;

/*
|-----------------------------------------------------------
| Custom Thumbnails Sizes
|-----------------------------------------------------------
|
| This file is for registering your custom
| image sizes, for posts thumbnails.
|
*/

/**
 * Adds new thumbnails image sizes.
 *
 * @return void
 */
function add_image_sizes()
{
    // 16x9 image sizes
    add_image_size('54p',     96,   54, true);
    add_image_size('72p',    128,   72, true);
    add_image_size('108p',   192,  108, true);
    add_image_size('144p',   256,  144, true);
    add_image_size('180p',   320,  180, true);
    add_image_size('360p',   640,  360, true);
    add_image_size('720p',  1280,  720, true);
    add_image_size('1080p', 1920, 1080, true);

    // Square image sizes
    add_image_size('square80',  80,  80,  true);
    add_image_size('square160', 160, 160, true);
    add_image_size('square320', 320, 320, true);
    add_image_size('square640', 640, 640, true);

    add_image_size('bg3x1', 1200, 400, true);

    // add WordPress standard Thumbnail
    set_post_thumbnail_size( 320, 180, true );
}
add_action('after_setup_theme', 'AppTheme\Structure\add_image_sizes');
