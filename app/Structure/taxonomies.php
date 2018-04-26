<?php

namespace AppTheme\Structure;

/*
|-----------------------------------------------------------
| Theme Custom Taxonomies
|-----------------------------------------------------------
|
| This file is for registering your theme custom taxonomies.
| Taxonomies help to classify posts and custom post types.
|
*/

use function AppTheme\config;

/**
 * Registers `media_series`, `media_speakers`, `media_topics` and `media_podcasts` custom taxonomies.
 *
 * @return void
 */
function register_media_taxonomies()
{

    register_taxonomy('series', 'recordings', [
        'public'            => true,
        'show_in_nav_menus' => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'hierarchical'      => false,
        'show_in_rest'      => true,
        'rewrite'           => [
            'slug'              => 'series',
            'with_front'        => true,
            'hierarchical'      => true,
        ],
        'labels' => [
            'name' => _x('Series', 'taxonomy general name', config('textdomain')),
            'singular_name' => _x('Series', 'taxonomy singular name', config('textdomain')),
            'search_items' => __('Search Series', config('textdomain')),
            'all_items' => __('All Series', config('textdomain')),
            'parent_item' => __('Parent Series', config('textdomain')),
            'parent_item_colon' => __('Parent Series:', config('textdomain')),
            'edit_item' => __('Edit Series', config('textdomain')),
            'update_item' => __('Update Serie', config('textdomain')),
            'add_new_item' => __('Add New Series', config('textdomain')),
            'new_item_name' => __('New Series Name', config('textdomain')),
            'menu_name' => __('Series', config('textdomain')),
        ],
    ]);

    register_taxonomy('speakers', 'recordings', [
        'public'            => true,
        'show_in_nav_menus' => true,
        'hierarchical'      => false,
        'show_in_rest'      => true,
        'rewrite'           => [
            'slug'              => 'speakers',
            'with_front'        => true,
            'hierarchical'      => true,
        ],
        'labels'            => [
            'name' => _x('Speakers', 'taxonomy general name', config('textdomain')),
            'singular_name' => _x('Speaker', 'taxonomy singular name', config('textdomain')),
            'search_items' => __('Search Speakers', config('textdomain')),
            'all_items' => __('All Speakers', config('textdomain')),
            'parent_item' => __('Parent Speaker', config('textdomain')),
            'parent_item_colon' => __('Parent Speaker:', config('textdomain')),
            'edit_item' => __('Edit Speaker', config('textdomain')),
            'update_item' => __('Update Speaker', config('textdomain')),
            'add_new_item' => __('Add New Speaker', config('textdomain')),
            'new_item_name' => __('New Speaker Name', config('textdomain')),
            'menu_name' => __('Speaker', config('textdomain')),
        ],
    ]);

    register_taxonomy('topics', 'recordings', [
        'public'        => true,
        'show_in_rest'  => true,
        'rewrite'       => [
            'slug'          => 'topics',
            'with_front'    => true,
            'hierarchical'  => true,
        ],
        'hierarchical'  => true,
        'labels'        => [
            'name' => _x('Topics', 'taxonomy general name', config('textdomain')),
            'singular_name' => _x('Topic', 'taxonomy singular name', config('textdomain')),
            'search_items' => __('Search Topics', config('textdomain')),
            'all_items' => __('All Topics', config('textdomain')),
            'parent_item' => __('Parent Topic', config('textdomain')),
            'parent_item_colon' => __('Parent Topic:', config('textdomain')),
            'edit_item' => __('Edit Topic', config('textdomain')),
            'update_item' => __('Update Topic', config('textdomain')),
            'add_new_item' => __('Add New Topic', config('textdomain')),
            'new_item_name' => __('New Topic Name', config('textdomain')),
            'menu_name' => __('Topic', config('textdomain')),
        ],
    ]);

    register_taxonomy('podcasts', 'recordings', [
        'rewrite' => [
            'slug' => 'podcasts',
            'with_front' => true,
            'hierarchical' => true,
        ],
        'hierarchical' => false,
        'labels' => [
            'name' => _x('Podcasts', 'taxonomy general name', config('textdomain')),
            'singular_name' => _x('Podcast', 'taxonomy singular name', config('textdomain')),
            'search_items' => __('Search Podcasts', config('textdomain')),
            'all_items' => __('All Podcasts', config('textdomain')),
            'parent_item' => __('Parent Podcast', config('textdomain')),
            'parent_item_colon' => __('Parent Podcast:', config('textdomain')),
            'edit_item' => __('Edit Podcast', config('textdomain')),
            'update_item' => __('Update Podcast', config('textdomain')),
            'add_new_item' => __('Add New Podcast', config('textdomain')),
            'new_item_name' => __('New Podcast Name', config('textdomain')),
            'menu_name' => __('Podcast', config('textdomain')),
        ],
    ]);
}
add_action('init', 'AppTheme\Structure\register_media_taxonomies');


/**
 * Add Taxonomy Filter to Admin List of `recordings`
 *
 * @source https://wordpress.org/support/topic/add-taxonomy-filter-to-admin-list-for-my-custom-post-type
 * @return void
 */
function restrict_recordings_by_taxonomy() {
    global $typenow;
    $post_type = 'recordings';
    $taxonomies = ['series', 'speakers', 'topics'];
    if ($typenow == $post_type) {
        foreach ($taxonomies as $taxonomy) {
            $selected = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
            $info_taxonomy = get_taxonomy($taxonomy);
            wp_dropdown_categories(array(
                'show_option_all' => __("Show all {$info_taxonomy->label}", config('textdomain')),
                'taxonomy' => $taxonomy,
                'name' => $taxonomy,
                'orderby' => 'name',
                'selected' => $selected,
                'show_count' => true,
                'hide_empty' => true,
            ));
        };
    }
}
add_action('restrict_manage_posts', 'AppTheme\Structure\restrict_recordings_by_taxonomy');

function convert_id_to_term_in_query( $query ) {
    global $pagenow;
    $post_type = 'recordings';
    $taxonomies = ['series', 'speakers', 'topics'];
    $q_vars = &$query->query_vars;
    foreach ( $taxonomies as $taxonomy ) {
        if ($pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0) {
            $term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
            $q_vars[$taxonomy] = $term->slug;
        }
    }
}
add_filter('parse_query', 'AppTheme\Structure\convert_id_to_term_in_query');


/**
 * Adding a Custom Column to the taxonomy video_sprecher to dispay the thumbnail in the index in the backend
 * -- removing the description
 * Adding a Custom Column to the taxonomy video_serien to dispay the thumbnail
 * -- removing the description
 */
// Register the column
function recordings_tax_edit_columns($columns) {
    $columns = array_merge(
        array_slice($columns, 0, 1),
        array( 'image' => __('Image', config('textdomain')) ),
        array_slice($columns, 1, 1),
        array_slice($columns, 3)
    );
    return $columns;
}
add_filter('manage_edit-speakers_columns', 'AppTheme\Structure\recordings_tax_edit_columns', 5);
add_filter('manage_edit-series_columns', 'AppTheme\Structure\recordings_tax_edit_columns', 5);

// Display the columns content
function speakers_custom_columns($value, $column_name, $id) {
    global $post;
    if( $column_name == 'image' ) {
        $img = '';
        if( function_exists('get_field') ) {
            $img = wp_get_attachment_image_src( get_field( "image", "speakers_".$id ), 'square160' )[0];
        }
        printf( '<img class="img img-square80" src="%s" alt="" />', $img );
    }
}
add_action('manage_speakers_custom_column', 'AppTheme\Structure\speakers_custom_columns', 5, 3);


function series_custom_columns($value, $column_name, $id) {
    global $post;
    if( $column_name == 'image' ) {
        $img = '';
        if( function_exists('get_field') ) {
            $img = wp_get_attachment_image_src( get_field( "image", "series_".$id ), '108p' )[0];
        }
        printf( '<img class="img img-54p" src="%s" alt="" />', $img );
    }
}
add_action('manage_series_custom_column', 'AppTheme\Structure\series_custom_columns', 5, 3);


/**
 * Adding a Custom Column to the taxonomy media_podcasts to dispay the thumbnail in the index in the backend
 */
// Register the column
function podcasts_edit_columns($columns) {
    $columns = array_merge(
        array_slice($columns, 0, 1),
        array( 'image' => __('Image', config('textdomain')) ),
        array_slice($columns, 1, 1),
        array_slice($columns, 3),
        array( 'subscribers' => __('Subs', config('textdomain')) )
    );
    return $columns;
}
add_filter('manage_edit-podcasts_columns', 'AppTheme\Structure\podcasts_edit_columns', 5);

// Display the columns content
function podcasts_custom_columns($value, $column_name, $id) {
    global $post;
    switch ($column_name) {
        case 'image':
            $img = '';
            if( function_exists('get_field') ) {
                $img = wp_get_attachment_image_src( get_field( "image", "podcasts_".$id ), 'square160' )[0];
            }
            printf( '<img class="img img-square80" src="%s" alt="" />', $img );
            break;
        case 'subscribers':
            if( function_exists('trac_get_podcast_subscribers') ) {
                $pod_subs = trac_get_podcast_subscribers($id);
                printf("%d <span style=\"color: %s;\">(%s%d)</span>",
                       $pod_subs['current'],
                       $pod_subs['diff'] >= 0 ? 'green' : 'red',
                       $pod_subs['diff'] >= 0 ? '+' : '-',
                       abs($pod_subs['diff'])
                );
            }
            break;
    }
}
add_action('manage_podcasts_custom_column', 'AppTheme\Structure\podcasts_custom_columns', 5, 3);


/**
 * Increase posts_per_page for podcasts
 */
function podcasts_increase_posts_per_page( $query ) {
    if( !is_admin() && $query->is_tax()
                    && $query->get_queried_object()
                    && $query->get_queried_object()->taxonomy == "podcasts" ) {
        $query->set( 'posts_per_page', '100' );
    }
}
add_action( 'pre_get_posts', 'AppTheme\Structure\podcasts_increase_posts_per_page' );


/**
 * Add a post to a podcast if ...
 * - post is a video
 * - post has a new source file selected
 * Is triggered whenever a post or page is created or updated.
 * (ACF fires with priority 10, so this one needs to be first since it alters
 * the $_POST variable) – this line is obsolete by know, yet I haven't chaned
 * the priority
 */
function auto_add_to_podcast( $post_id ) {
    // Avoid the function to trigger at wrong time
    if( !isset($_POST['post_type']) ) return; // check if it is post-type index exists to prevent errors
    if( $_POST['post_type'] != 'recordings' ) return; // check if post-type is video or audio
    if( empty($_POST['acf']) ) return;

    $series_key = "field_53dfaf955292d";
    $podcast_key = "field_59dcf1e0346a5";
    $series = (int) $_POST['acf'][$series_key];

    // is a podcast associated with the series?
    if($series) {
        $_POST['acf'][$podcast_key] = (string) get_field('podcast', 'series_'.$series);
    }

}
add_action( 'acf/save_post', 'AppTheme\Structure\auto_add_to_podcast', 1 );
