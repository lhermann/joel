<?php

/**
 * Return a constant or a default if constant is not defined
 */
function if_defined($constant, $default = "") {
    return defined($constant) ? constant($constant) : $default;
}

return [
    /*
    |--------------------------------------------------------------------------
    | Theme Switches
    |--------------------------------------------------------------------------
    */
    'searchbar' => if_defined('SEARCHBAR', false),
    'slider' => if_defined('SLIDER', true),
    'landing-promo' => if_defined('LANDING_PROMO', false),
    'landing-videos' => if_defined('LANDING_VIDEOS', true),
    'landing-content' => if_defined('LANDING_CONTENT', true),
    'landing-quotes' => if_defined('LANDING_QUOTES', true),
    'landing-articles' => if_defined('LANDING_ARTICLES', true),
    'landing-newsletter' => if_defined('LANDING_NEWSLETTER', false),
    'landing-donate' => if_defined('LANDING_DONATE', true),
    'livestream' => [
        'enabled' => if_defined('LIVESTREAM', true),
        'program-timeframe' => if_defined('LIVESTREAM_TIMEFRAME', 4), // in weeks
    ],

    /*
    |--------------------------------------------------------------------------
    | Theme Defaults
    |--------------------------------------------------------------------------
    */
    'url-prefix' => [
        'download' => if_defined('URL_PREFIX_DOWNLOAD', 'https://dl.joelmediatv.de/'),
        'embed' => if_defined('URL_PREFIX_EMBED', 'https://embed.joelmediatv.de/'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Theme Textdomain
    |--------------------------------------------------------------------------
    |
    | Determines a textdomain for your theme. Should be used to dynamically set
    | namespace for gettext strings across theme. Remember, this value must
    | be in sync with `Text Domain:` entry inside style.css theme file.
    |
    */
    'textdomain' => 'joel',

    /*
    |--------------------------------------------------------------------------
    | Templates files extension
    |--------------------------------------------------------------------------
    |
    | Determines the theme's templates settings like an extension of the files.
    | By default, they use `.tpl.php` suffix to distinguish template files
    | from controllers, but you are free to change it however you like.
    |
    */
    'templates' => [
        'extension' => '.tpl.php'
    ],

    /*
    |--------------------------------------------------------------------------
    | Theme Root Paths
    |--------------------------------------------------------------------------
    |
    | This values determines the "root" paths of your theme. By default,
    | they use WordPress `get_template_directory` functions and
    | probably you don't need make any changes in here.
    |
    */
    'paths' => [
        'directory' => get_template_directory(),
        'uri' => get_template_directory_uri(),
    ],

    /*
    |--------------------------------------------------------------------------
    | Theme Directory Structure Paths
    |--------------------------------------------------------------------------
    |
    | This array of directories will be used within core for locating
    | and loading theme files, assets and templates. They must be
    | given as relative to the `root` theme directory.
    |
    */
    'directories' => [
        'languages' => 'resources/languages',
        'templates' => 'resources/templates',
        'assets' => 'resources/assets',
        'public' => 'public',
        'app' => 'app',
    ],

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Theme Components
    |--------------------------------------------------------------------------
    |
    | The components listed below will be automatically loaded on the
    | theme bootstrap by `functions.php` file. Feel free to add your
    | own files to this array which you would like to autoload.
    |
    */
    'autoload' => [
        'helpers.php',
        'ACF/recordings.php',
        'ACF/slides.php',
        'Helper/global.php',
        'Helper/recordings.php',
        'Legacy/recordings.php',
        'Legacy/trac.php',
        'Http/assets.php',
        'Http/ajaxes.php',
        'Http/restapi.php',
        'Http/routes.php',
        'Setup/actions.php',
        'Setup/filters.php',
        'Setup/supports.php',
        'Setup/services.php',
        'Structure/navs.php',
        'Structure/widgets.php',
        'Structure/sidebars.php',
        'Structure/posttypes.php',
        'Structure/taxonomies.php',
        'Structure/shortcodes.php',
        'Structure/thumbnails.php',
    ]
];
