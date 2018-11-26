<?php

namespace Tonik\Theme\App\Structure;

/*
|-----------------------------------------------------------
| Theme Sidebars
|-----------------------------------------------------------
|
| This file is for registering your theme sidebars,
| Creates widgetized areas, which an administrator
| of the site can customize and assign widgets.
|
*/

use function Tonik\Theme\App\config;

/**
 * Registers the widget areas.
 *
 * @return void
 */
function register_widget_areas()
{

    register_sidebar( array(
        'name'          => 'Footer 1',
        'id'            => 'footer_1',
        'before_widget' => '<div class="c-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="c-site-footer__heading u-truncate">',
        'after_title'   => '</h3>',
    ) );
    register_sidebar( array(
        'name'          => 'Footer 2',
        'id'            => 'footer_2',
        'before_widget' => '<div class="c-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="c-site-footer__heading u-truncate">',
        'after_title'   => '</h3>',
    ) );
    register_sidebar( array(
        'name'          => 'Footer 3',
        'id'            => 'footer_3',
        'before_widget' => '<div class="c-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="c-site-footer__heading u-truncate">',
        'after_title'   => '</h3>',
    ) );
    register_sidebar( array(
        'name'          => 'Footer 4',
        'id'            => 'footer_4',
        'before_widget' => '<div class="c-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="c-site-footer__heading u-truncate">',
        'after_title'   => '</h3>',
    ) );
    register_sidebar( array(
        'name'          => 'Footer Right',
        'id'            => 'footer_right',
        'before_widget' => '<div class="c-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="c-site-footer__heading u-truncate">',
        'after_title'   => '</h3>',
    ) );
}
add_action('widgets_init', 'Tonik\Theme\App\Structure\register_widget_areas');
