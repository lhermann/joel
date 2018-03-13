<?php

namespace AppTheme\Helper;

/*
|-----------------------------------------------------------
| Global Theme Helper Functions
|-----------------------------------------------------------
*/

/**
 * Get the url currently in the browser's address bar, or compare a argument
 * with it.
 */
function current_url($url_to_compare_with = false) {
    $actual_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    if($url_to_compare_with) {
        return $url_to_compare_with == $actual_url;
    }
    return $actual_url;
}

/**
 * check if this item, or any of its children, is active
 */
function menu_item_is_active($item, $menu = []) {
    $is_active = current_url($item->url);
    if( count($menu) ) {
        foreach ($menu as $subitem) {
            $is_active = $is_active || current_url($subitem->url);
        }
    }
    return $is_active;
}

/**
 * check if this item, or any of its children, is active
 */
function menu_item_has_children($item, $menu) {
    foreach ($menu as $temp_item) {
        if( $temp_item->menu_item_parent == $item->ID )
            return true;
    }
    return false;
}

/**
 * Generate a simple menu structure for use in the flyin menu
 */
function render_menu_for_flyin($location) {
    if( !$menu = wp_get_nav_menu_items( wp_get_nav_menu_name( $location ) ) ) return;

    // var_dump(menu_item_is_active($menu[0], $menu));
    // var_dump(menu_item_is_active($menu[1], $menu));

    print('<ul class="u-break-wrapper c-mobile-nav__list">');

    foreach ($menu as $key => $item) {
        if( $item->menu_item_parent != 0 ) continue;

        print('<li>');

        printf(
            '<h3 class="u-h5 u-mb-">
                <a class="c-link c-link--block c-link--primary u-truncate %s" href="%s">%s</a>
            </h3>',
            menu_item_is_active($item, $menu) ? 'is-active' : '',
            $item->url,
            $item->title
        );

        if( menu_item_has_children($item, $menu) ) {

            print('<ul class="o-list-bare">');

            foreach ($menu as $key => $subitem) {
                if( $subitem->menu_item_parent != $item->ID ) continue;

                printf(
                    '<li>
                        <a class="c-link c-link--block c-link--primary u-truncate %s" href="%s">%s</a>
                    </li>',
                    menu_item_is_active($subitem) ? 'is-active' : '',
                    $subitem->url,
                    $subitem->title
                );

            }

            print('</ul>');

        }

        print('</li>');
    }

    print('</ul>');
    print('<hr class="u-break-wrapper"/>');
}







