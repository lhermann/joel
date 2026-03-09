<?php

namespace Tonik\Theme\App;

/*
|-----------------------------------------------------------
| Theme Helper Functions
|-----------------------------------------------------------
|
| Core helper functions that the entire theme depends on.
| These provide config access, template rendering, and asset
| resolution. Originally powered by the Tonik Gin framework,
| now self-contained after the Gin dependency was removed.
|
| The Tonik\Theme\App namespace is preserved so that all
| existing `use function Tonik\Theme\App\config;` imports
| throughout templates and app files continue to work.
|
*/


/*
|-----------------------------------------------------------
| Config
|-----------------------------------------------------------
|
| Simple config store loaded from config/app.php.
| Accessed via config('key') throughout the theme.
| Supports get/set so that assets.php can override
| directory paths during Vite dev proxy mode.
|
*/

/**
 * Global config instance. Loaded once from config/app.php
 * during bootstrap (functions.php), then accessed everywhere
 * via the config() helper below.
 */
$_theme_config = null;

/**
 * Initialize the config store. Called once from functions.php.
 *
 * @param array $items The config array from config/app.php
 */
function config_init(array $items)
{
    global $_theme_config;
    $_theme_config = $items;
}

/**
 * Get a configuration value by key.
 *
 * @param  string $key  Config key from config/app.php (e.g. 'textdomain', 'paths', 'directories')
 * @return mixed
 */
function config(string $key)
{
    global $_theme_config;
    return $_theme_config[$key] ?? null;
}

/**
 * Override a configuration value at runtime.
 * Used by assets.php to switch directory paths during Vite dev proxy mode.
 *
 * @param string $key
 * @param mixed  $value
 */
function config_set(string $key, $value): void
{
    global $_theme_config;
    $_theme_config[$key] = $value;
}


/*
|-----------------------------------------------------------
| Theme Service Container
|-----------------------------------------------------------
|
| Minimal service container. Services are closures that get
| resolved once (singleton pattern) and cached.
| Currently used for 'slides' (bound in Setup/services.php).
|
*/

/**
 * Access the theme service container.
 *
 * - theme()           → returns a ThemeContainer for ->bind() / ->get()
 * - theme('slides')   → shortcut for theme()->get('slides')
 *
 * @param  string|null $key
 * @param  array       $parameters
 * @return mixed|ThemeContainer
 */
function theme($key = null, $parameters = [])
{
    static $container = null;
    if ($container === null) {
        $container = new ThemeContainer();
    }

    if (null !== $key) {
        return $container->get($key, $parameters);
    }

    return $container;
}

/**
 * Minimal service container replacing Tonik\Gin\Foundation\Theme.
 * Supports bind() for registering singleton services and get() for
 * resolving them. Services are resolved lazily on first access.
 */
class ThemeContainer
{
    /** @var array<string, \Closure> */
    private array $services = [];

    /** @var array<string, mixed> Cached resolved values */
    private array $resolved = [];

    /**
     * Register a service. The closure receives ($this, $parameters).
     */
    public function bind(string $key, \Closure $service): self
    {
        $this->services[$key] = $service;
        // Clear cached value so re-binding works
        unset($this->resolved[$key]);
        return $this;
    }

    /**
     * Resolve a service by key. Result is cached (singleton).
     *
     * @throws \RuntimeException if the key was never bound
     */
    public function get(string $key, array $parameters = [])
    {
        if (!isset($this->services[$key])) {
            throw new \RuntimeException("Service [{$key}] is not registered.");
        }

        if (!array_key_exists($key, $this->resolved)) {
            $this->resolved[$key] = call_user_func($this->services[$key], $this, $parameters);
        }

        return $this->resolved[$key];
    }
}


/*
|-----------------------------------------------------------
| Template Rendering
|-----------------------------------------------------------
|
| Renders PHP template files from resources/templates/.
| Array format joins with '-': ['single', 'page'] → single-page.tpl.php
|
| Data is passed via extract() so template files can use
| variables directly (e.g. $style_modifier, $youtube).
|
*/

/**
 * Render a template file with optional data.
 *
 * Supports two formats:
 *   template('partials/header')              → resources/templates/partials/header.tpl.php
 *   template(['single', 'page'])             → resources/templates/single-page.tpl.php
 *   template('partials/meta', ['key' => 1])  → template receives $key = 1 via extract()
 *
 * @param string|array $file  Template name. Array joins with '-' (e.g. ['single','page'] → 'single-page').
 * @param array        $data  Variables to extract into the template's scope.
 */
function template($file, $data = [])
{
    $relative_path = template_path($file);

    // locate_template() searches in child theme first, then parent theme
    $template = locate_template($relative_path, false, false);

    if (!$template) {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            trigger_error("Template file [{$relative_path}] not found.", E_USER_WARNING);
        }
        return;
    }

    // Fire WP's get_template_part action so hooks on template loading still work
    $slug = is_array($file) ? $file[0] : $file;
    $name = (is_array($file) && !empty($file[1])) ? $file[1] : null;
    do_action("get_template_part_{$slug}", $slug, $name);

    // Apply context filter (powers default $style_modifier = '' from filters.php)
    $filter_name = _template_basename($file) . '.php';
    $data = apply_filters("tonik/gin/template/context/{$filter_name}", $data);

    // Extract data so templates can use variables directly (e.g. $style_modifier)
    extract($data);

    require $template;
}

/**
 * Get the relative path to a template file.
 * e.g. template_path('partials/header') → "resources/templates/partials/header.tpl.php"
 *
 * @param  string|array $file
 * @return string
 */
function template_path($file): string
{
    $templates_dir = config('directories')['templates'];  // "resources/templates"
    $extension = config('templates')['extension'];          // ".tpl.php"

    return $templates_dir . '/' . _template_basename($file) . $extension;
}

/**
 * Turn a template specifier into its base name.
 *   'partials/header'    → 'partials/header'
 *   ['single', 'page']   → 'single-page'
 *   ['single', '']       → 'single'
 *
 * @internal
 */
function _template_basename($file): string
{
    if (is_array($file)) {
        // ['single', 'page'] → 'single-page', ['single'] or ['single', ''] → 'single'
        return !empty($file[1]) ? implode('-', $file) : $file[0];
    }
    return $file;
}


/*
|-----------------------------------------------------------
| Asset Resolution
|-----------------------------------------------------------
|
| Resolves files in the dist/ directory to their full URI
| or filesystem path. Used for images, JS polyfills, and
| any static assets shipped with the theme.
|
| asset() returns an AssetReference object with ->getUri()
| and ->getPath() methods, matching the old Gin interface
| that templates and assets.php depend on.
|
*/

/**
 * Get an asset reference for a file in the dist/ directory.
 *
 * @param  string $file  Relative to dist/, e.g. "images/logo.svg"
 * @return AssetReference
 */
function asset($file)
{
    return new AssetReference($file);
}

/**
 * Shorthand: get the URI for an asset file.
 *
 * @param  string $file  Relative to dist/, e.g. "images/logo.svg"
 * @return string         Full URI, e.g. "https://example.com/wp-content/themes/themefiles/dist/images/logo.svg"
 */
function asset_path($file)
{
    return asset($file)->getUri();
}

/**
 * Represents a file in the theme's public/dist directory.
 * Provides ->getUri() for URLs and ->getPath() for filesystem paths.
 *
 * This replaces Tonik\Gin\Asset\Asset. The interface is kept identical
 * so that existing code like `asset('file.js')->getUri()` keeps working.
 */
class AssetReference
{
    private string $file;

    public function __construct(string $file)
    {
        $this->file = $file;
    }

    /**
     * Get the full URL to this asset.
     * e.g. "https://example.com/wp-content/themes/themefiles/dist/images/logo.svg"
     */
    public function getUri(): string
    {
        $uri = config('paths')['uri'];
        $public = config('directories')['public'];
        return $uri . '/' . $public . '/' . $this->file;
    }

    /**
     * Get the absolute filesystem path to this asset.
     * e.g. "/var/www/html/wp-content/themes/themefiles/dist/images/logo.svg"
     */
    public function getPath(): string
    {
        $directory = config('paths')['directory'];
        $public = config('directories')['public'];
        return $directory . '/' . $public . '/' . $this->file;
    }
}


/*
|-----------------------------------------------------------
| Vite Dev Proxy Detection
|-----------------------------------------------------------
*/

/**
 * Detect if we're running behind the Vite dev server proxy.
 * The Vite config (vite.config.js) proxies requests to WordPress
 * and adds X-Forwarded-* headers. When detected, assets.php
 * switches to serving files directly from source instead of dist/.
 */
function vite_dev_proxy()
{
    return array_key_exists('HTTP_HOST', $_SERVER)
        && strpos($_SERVER['HTTP_HOST'], 'localhost') !== false
        && array_key_exists('HTTP_X_FORWARDED_PORT', $_SERVER);
}


/*
|-----------------------------------------------------------
| Store (Simple Key-Value Singleton)
|-----------------------------------------------------------
|
| Global key-value store for passing state between
| disconnected parts of the theme. Used sparingly.
|
*/

class Store
{
    protected static $store = [];

    protected function __construct() {}

    public static function set($key, $value = true)
    {
        self::$store[$key] = $value;
    }

    public static function get($key)
    {
        return self::$store[$key] ?? null;
    }

    public static function isset($key)
    {
        return isset(self::$store[$key]);
    }

    /**
     * Check if a key exists, then set it. Returns whether
     * the key was already set (useful for "run once" guards).
     */
    public static function isset_then_set($key, $value = true)
    {
        $return = self::isset($key);
        self::set($key, $value);
        return $return;
    }
}
