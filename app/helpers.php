<?php

namespace AppTheme;

use Tonik\Gin\Asset\Asset;
use Tonik\Gin\Foundation\Theme;
use Tonik\Gin\Template\Template;

/**
 * Gets theme instance.
 *
 * @param string|null $key
 * @param array $parameters
 *
 * @return \Tonik\Gin\Foundation\Theme
 */
function theme($key = null, $parameters = [])
{
    if (null !== $key) {
        return Theme::getInstance()->get($key, $parameters);
    }

    return Theme::getInstance();
}

/**
 * Gets theme config instance.
 *
 * @param string|null $key
 *
 * @return array
 */
function config($key = null)
{
    if (null !== $key) {
        return theme('config')->get($key);
    }

    return theme('config');
}

/**
 * Renders template file with data.
 *
 * @param  string $file Relative path to the template file.
 * @param  array  $data Dataset for the template.
 *
 * @return void
 */
function template($file, $data = [])
{
    $template = new Template(config());

    return $template
        ->setFile($file)
        ->render($data);
}

/**
 * Gets asset instance.
 *
 * @param  string $file Relative file path to the asset file.
 *
 * @return \Tonik\Gin\Asset\Asset
 */
function asset($file)
{
    $asset = new Asset(config());

    return $asset->setFile($file);
}

/**
 * Gets asset file from public directory.
 *
 * @param  string $file Relative file path to the asset file.
 *
 * @return string
 */
function asset_path($file)
{
    return asset($file)->getUri();
}

/**
 * Get the url currentlu in the status bar, or compare a parameter with it.
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
