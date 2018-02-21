<?php

namespace AppTheme\Setup;

/*
|-----------------------------------------------------------
| Theme Filters
|-----------------------------------------------------------
|
| This file purpose is to include your theme various
| filters hooks, which changes output or behaviour
| of diffrent parts of WordPress functions.
|
*/

use function AppTheme\config;

/**
 * Hides sidebar on index template on specific views.
 *
 * @see apply_filters('theme/index/sidebar/visibility')
 * @see apply_filters('theme/single/sidebar/visibility')
 */
function show_index_sidebar($status)
{
    if (is_404() || is_page()) {
        return false;
    }

    return $status;
}
add_filter('theme/index/sidebar/visibility', 'AppTheme\Setup\show_index_sidebar');
add_filter('theme/single/sidebar/visibility', 'AppTheme\Setup\show_index_sidebar');

/**
 * Shortens posts excerpts to 60 words.
 *
 * @return integer
 */
function modify_excerpt_length()
{
    return 60;
}
add_filter('excerpt_length', 'AppTheme\Setup\modify_excerpt_length');

/**
 * Remove p-tags in term description
 */
remove_filter('term_description', 'wpautop');

/*
 * Get a recursive directory tree form the partials directory and add the
 * default filter to all files
 */
function add_default_template_context($context) {
    if( !isset($context['style_modifier']) )
        $context['style_modifier'] = '';
    return $context;
}

$partials_dir = get_stylesheet_directory().'/'.config('directories')['templates'].'/partials';
$scanned_directory = array_diff(scandir($partials_dir), array('..', '.'));
foreach ($scanned_directory as $key => $file) {
    $subdir = $partials_dir.'/'.$file;
    if (is_dir($subdir)) {
        $scanned_subdir = array_diff(scandir($subdir), array('..', '.'));
        unset($scanned_directory[array_search($file, $scanned_directory)]);
        array_walk($scanned_subdir, function(&$item, $key, $prefix){ $item = $prefix.'/'.$item; }, $file);
        $scanned_directory = array_merge($scanned_directory, $scanned_subdir);
    }
}
// remove dotfiles
$scanned_directory = array_filter($scanned_directory, function($item) { return strpos($item, '.') !== 0; });
// remove .tpl in filenames
array_walk($scanned_directory, function(&$item) {
    $item = str_replace('.tpl', '', $item);
});
foreach ($scanned_directory as $file) {
    add_filter('tonik/gin/template/context/partials/'.$file, 'AppTheme\Setup\add_default_template_context');
}




