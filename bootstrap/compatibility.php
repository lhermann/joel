<?php

/*
|--------------------------------------------------------------------------
| Theme Compatibility Check
|--------------------------------------------------------------------------
|
| Previously checked for the Tonik Gin package, which has been removed.
| Now checks for minimum PHP version requirements.
|
| Returns true if everything is OK, false otherwise.
|
*/

if (version_compare(PHP_VERSION, '8.1.0', '<')) {
    if (!is_admin()) {
        wp_die('This theme requires PHP 8.1 or later. You are running PHP ' . PHP_VERSION . '.');
    }

    add_action('admin_notices', function () {
        echo '<div class="error notice"><p>';
        echo 'This theme requires <strong>PHP 8.1</strong> or later. ';
        echo 'You are running PHP ' . PHP_VERSION . '.';
        echo '</p></div>';
    });

    return false;
}

return true;
