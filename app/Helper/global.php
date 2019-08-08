<?php

namespace Tonik\Theme\App\Helper;

/*
|-----------------------------------------------------------
| Global Theme Helper Functions
|-----------------------------------------------------------
*/


/*
 * Generate a hash out of any data structure which can savely be used as
 * a html id attribute
 */
function idHash($input) {
    if(!is_string($input)) $input = serialize($input);
    return 'id' . md5($input);
}


/**
 * Recursively scann a directory
 */
function recursively_scann_dir($dir) {

    $scann = array_diff(scandir($dir), array('..', '.'));

    foreach ($scann as $key => $file) {
        $subdir = $dir.'/'.$file;

        if (is_dir($subdir)) {

            // recursively scann
            $subscan = recursively_scann_dir($subdir);

            unset( $scann[ array_search($file, $scann) ] );

            // add full path to $subscann
            array_walk( $subscan, function(&$item, $key, $prefix){
                $item = $prefix.'/'.$item;
            }, $file);

            // merge the lists
            $scann = array_merge($scann, $subscan);

        }
    }

    return $scann;
}


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


/**
 * Get a fallback img for any size and resolution
 */
function fallback_img($img, $resolution) {
    if($img) return $img;
    return \Tonik\Theme\App\asset_path('images/dummy-'.$resolution.'.jpg');
}


/**
 * Function to format filesize (used for download button)
 */
function formatbytes($a_bytes) {

    // calculate appropriate display
    if ($a_bytes < 1024) {
        $type = 'B';
        $filesize = $a_bytes;
    } elseif ($a_bytes < 1048576) {
        $type = 'KB';
        $filesize = round($a_bytes / 1024, 0);
    } elseif ($a_bytes < 1073741824) {
        $type = 'MB';
        $filesize = round($a_bytes / 1048576, 0);
    } else {
        $type = 'GB';
        $filesize = round($a_bytes / 1073741824, 0);
    };

        // return
    if( $filesize <= 0 ){
        return $filesize = 0;
    } else {
        return number_format($filesize, 0, ',', '.').' '.$type;
    };
};







