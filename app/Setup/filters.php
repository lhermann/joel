<?php

namespace Tonik\Theme\App\Setup;

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

use function Tonik\Theme\App\config;
use function Tonik\Theme\App\Helper\recursively_scann_dir;
use function Tonik\Theme\App\template_path;

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
add_filter('theme/index/sidebar/visibility', 'Tonik\Theme\App\Setup\show_index_sidebar');
add_filter('theme/single/sidebar/visibility', 'Tonik\Theme\App\Setup\show_index_sidebar');

/**
 * Shortens posts excerpts to 60 words.
 *
 * @return integer
 */
function modify_excerpt_length()
{
    return 60;
}
add_filter('excerpt_length', 'Tonik\Theme\App\Setup\modify_excerpt_length');


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

$partials_dir = config('paths')['directory'].'/'.config('directories')['templates'];
$scanned_directory = recursively_scann_dir($partials_dir);
// remove dotfiles
$scanned_directory = array_filter($scanned_directory, function($item) { return strpos($item, '.') !== 0; });
// remove .tpl in filenames
array_walk($scanned_directory, function(&$item) {
    $item = str_replace('.tpl', '', $item);
});
// apply filter to all templates
foreach ($scanned_directory as $file) {
    add_filter('tonik/gin/template/context/'.$file, 'Tonik\Theme\App\Setup\add_default_template_context');
}


/**
 * Disable the main query on archive fields
 * All list are generated through REST API calls, so we don't need WP to run
 * it's own queries on certain archive pages
 */
function disable_main_query( &$query ) {
    // var_dump($query); die();
    if(in_array( $query->get('archive'), ['series', 'speakers', 'topics', 'podcasts']))
        $query->set('p', 1);
}
add_action('pre_get_posts', 'Tonik\Theme\App\Setup\disable_main_query', 10, 1);

/**
 * Order children of OT (term_id: 40) and NT (term_id: 58) by the Bible
 * ATTENTION: 'parent' term id is hard coded
 */
function order_biblical_books($terms, $taxonomies, $args) {
    // bail early
    if( !in_array('topics', $taxonomies) ||
        $args['orderby'] !== 'name' ||
        !in_array($args['order'], ['ASC', 'DESC']) ||
        !in_array($args['parent'], [40, 58])
    ) return $terms;

    $ot = ['1-mose', '2-mose', '3-mose', '4-mose', '5-mose', 'josua', 'richter', 'ruth', '1-samuel', '2-samuel', '1-konige', '2-koenige', '1-chronik', '2-chronik', 'esra', 'nehemia', 'esther', 'hiob', 'psalmen', 'sprueche', 'prediger', 'hohelied', 'jesaja', 'jeremia', 'hesekiel', 'daniel', 'hosea', 'joel', 'amos', 'obadja', 'jona', 'micha', 'nahum', 'habakuk', 'zephanja', 'haggai', 'sacharia', 'maleachi'];
    $nt = ['matthaus', 'markus', 'lukas', 'johannes', 'apostelgeschichte', 'roemer', '1-korinther', '2-korinther', 'galater', 'epheser', 'philipper', 'kolosser', '1-thessalonicher', '2-thessalonicher', '1-timotheus', '2-timotheus', 'titus', 'philemon', 'hebraeer', 'jakobus', '1-petrus', '2-petrus', '1-johannes', '2-johannes', '3-johannes', 'judas', 'offenbarung-neues-testament'];

    // Old Testament: 40, New Testament: 58
    $order = $args['parent'] === 40 ? $ot : $nt;

    usort($terms, function($a, $b) use ($order) {
        return array_search($a->slug, $order) - array_search($b->slug, $order);
    });

    return $terms;
}
add_filter('get_terms', 'Tonik\Theme\App\Setup\order_biblical_books', 10, 3);
