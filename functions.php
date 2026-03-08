<?php

/*
|------------------------------------------------------------------
| Theme Bootstrap
|------------------------------------------------------------------
|
| Entry point for the WordPress theme. Loads Composer autoloader,
| initializes the config, and auto-loads all theme component files
| listed in config/app.php under the 'autoload' key.
|
| The theme was originally built on Tonik Gin framework, which has
| been replaced with simple self-contained helpers in app/helpers.php.
| The Tonik\Theme\App namespace is preserved throughout the codebase
| for backwards compatibility — it's just a namespace, not a dependency.
|
*/

// Composer autoloader (for google/apiclient, crawler-detect, etc.)
if (file_exists($composer = __DIR__ . '/vendor/autoload.php')) {
    require $composer;
}

// Check PHP version and other requirements before proceeding.
$ok = require_once __DIR__ . '/bootstrap/compatibility.php';
if (!$ok) {
    return;
}

// Load the helper functions first — everything else depends on these.
require_once __DIR__ . '/app/helpers.php';

// Initialize the config store from config/app.php.
// This must happen before any autoloaded file calls config().
$config = require __DIR__ . '/config/app.php';
\Tonik\Theme\App\config_init($config);

// Auto-load all theme component files listed in config/app.php.
// Each file registers its own hooks, filters, post types, etc.
// Uses locate_template() so child themes can override any file.
foreach ($config['autoload'] as $file) {
    $relative_path = $config['directories']['app'] . '/' . $file;
    if (!locate_template($relative_path, true, true)) {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            trigger_error(
                "Autoloaded file [{$relative_path}] cannot be found. Check 'autoload' in config/app.php.",
                E_USER_WARNING
            );
        }
    }
}
