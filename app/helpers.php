<?php

namespace Tonik\Theme\App;

use Tonik\Gin\Asset\Asset;
use Tonik\Gin\Foundation\Theme;
use Tonik\Gin\Template\Template;


function webpack_dev_server() {
    return strpos($_SERVER['HTTP_HOST'], 'localhost') !== false &&
        key_exists("HTTP_X_FORWARDED_PORT", $_SERVER);
}

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
 * Gets asset file from public directory.
 *
 * @param  string $file Relative file path to the asset file.
 *
 * @return string
 */
function template_path($file)
{
    $template = new Template(config());

    return $template
        ->setFile($file)
        ->getRelativePath();
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
 * Store Singleton
 */
class Store {
    protected static $store = [];

    protected function __construct(){}
    protected function __clone(){}

    public static function set($key, $value = true) {
        self::$store[$key] = $value;
    }

    public static function get($key) {
        return self::$store[$key];
    }

    public static function isset($key) {
        return isset(self::$store[$key]);
    }

    public static function isset_then_set($key, $value = true) {
        $return = self::isset($key);
        self::set($key, $value);
        return $return;
    }
}







