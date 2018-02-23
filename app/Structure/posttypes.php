<?php

namespace AppTheme\Structure;

/*
|-----------------------------------------------------------
| Theme Custom Post Types
|-----------------------------------------------------------
|
| This file is for registering your theme post types.
| Custom post types allow users to easily create
| and manage various types of content.
|
*/

use function AppTheme\config;

/**
 * Registers `media` custom post type.
 *
 * @return void
 */
function register_media_post_type()
{
    register_post_type( 'recording', [
        'description'        => __('Collection of Video and Audio Recordings.', config('textdomain')),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'menu_icon'          => 'dashicons-format-video',
        'query_var'          => true,
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 8,
        'supports'           => array( 'title', 'editor', 'comments' ),
        'labels' => [
            'name' => _x('Recording', 'post type general name', config('textdomain')),
            'singular_name' => _x('Recordings', 'post type singular name', config('textdomain')),
            'menu_name' => _x('Archive', 'admin menu', config('textdomain')),
            'name_admin_bar' => _x('Recordings', 'add new on admin bar', config('textdomain')),
            'add_new' => _x('Add New', 'book', config('textdomain')),
            'add_new_item' => __('Add New Recording', config('textdomain')),
            'new_item' => __('New Recording', config('textdomain')),
            'edit_item' => __('Edit Recording', config('textdomain')),
            'view_item' => __('View Recording', config('textdomain')),
            'all_items' => __('All Recordings', config('textdomain')),
            'search_items' => __('Search Recordings', config('textdomain')),
            'parent_item_colon' => __('Parent Recording:', config('textdomain')),
            'not_found' => __('No recordings found.', config('textdomain')),
            'not_found_in_trash' => __('No recordings found in Trash.', config('textdomain')),
        ],
    ]);

}
add_action('init', 'AppTheme\Structure\register_media_post_type');


// Sort admin colums of video by date by default
function set_recording_post_type_admin_order($wp_query) {
    if (is_admin()) {

        $post_type = $wp_query->query['post_type'];

        if ( $post_type == 'recording' && empty($_GET['orderby'])) {
            $wp_query->set('orderby', 'date');
            $wp_query->set('order', 'DESC');
        }

    }
}
add_filter ( 'pre_get_posts', 'AppTheme\Structure\set_recording_post_type_admin_order' );


/**
 * Adding Cutsom Columns to the index of post type `media`
 */
// Register the columns
function recording_edit_columns($columns) {
    $columns = array(
        "cb"         => "<input type=\"checkbox\" />",
        "image"      => __('Image', config('textdomain')),
        "status"     => '',
        "title"      => __('Title', config('textdomain')),
        "speakers"   => __('Speaker', config('textdomain')),
        "series"     => __('Series', config('textdomain')),
        "date"       => __('Date', config('textdomain'))
    );

    return $columns;
}
add_filter('manage_edit-recording_columns', 'AppTheme\Structure\recording_edit_columns');


// Register the columns as sortable
function recording_sortable_columns($columns) {
    $custom = array(
    // meta column id => sortby value used in query
        "speakers" => __('Speaker', config('textdomain')),
        "series"   => __('Series', config('textdomain'))
    );

    return wp_parse_args($custom, $columns);
}
add_filter('manage_edit-recording_sortable_columns', 'AppTheme\Structure\recording_sortable_columns');


// Make the column "Sprecher" order correctly
function speakers_clauses( $clauses, $wp_query ) {
    global $wpdb;

    if ( isset( $wp_query->query['orderby'] )
            && $wp_query->query['orderby'] === __('Speaker', config('textdomain')) ) {

        $clauses['join'] .= <<<SQL
LEFT OUTER JOIN {$wpdb->term_relationships} ON {$wpdb->posts}.ID={$wpdb->term_relationships}.object_id
LEFT OUTER JOIN {$wpdb->term_taxonomy} USING (term_taxonomy_id)
LEFT OUTER JOIN {$wpdb->terms} USING (term_id)
SQL;

        $clauses['where'] .= " AND (taxonomy = 'speakers' OR taxonomy IS NULL)";
        $clauses['groupby'] = "object_id";
        $clauses['orderby'] = "GROUP_CONCAT({$wpdb->terms}.name ORDER BY name ASC) ";
        $clauses['orderby'] .= ( 'ASC' == strtoupper( $wp_query->get('order') ) ) ? 'ASC' : 'DESC';
    }

    return $clauses;
}
add_filter( 'posts_clauses', 'AppTheme\Structure\speakers_clauses', 10, 2 );


// Make the column "Serie" order correctly
function series_clauses( $clauses, $wp_query ) {
    global $wpdb;

    if ( isset( $wp_query->query['orderby'] )
        && $wp_query->query['orderby'] === __('Series', config('textdomain')) ) {

        $clauses['join'] .= <<<SQL
LEFT OUTER JOIN {$wpdb->term_relationships} ON {$wpdb->posts}.ID={$wpdb->term_relationships}.object_id
LEFT OUTER JOIN {$wpdb->term_taxonomy} USING (term_taxonomy_id)
LEFT OUTER JOIN {$wpdb->terms} USING (term_id)
SQL;

        $clauses['where'] .= " AND (taxonomy = 'series' OR taxonomy IS NULL)";
        $clauses['groupby'] = "object_id";
        $clauses['orderby'] = "GROUP_CONCAT({$wpdb->terms}.name ORDER BY name ASC) ";
        $clauses['orderby'] .= ( 'ASC' == strtoupper( $wp_query->get('order') ) ) ? 'ASC' : 'DESC';
    }

    return $clauses;
}
add_filter( 'posts_clauses', 'AppTheme\Structure\series_clauses', 10, 2 );


/*
 * Display the columns content
 *
 * TODO:
 * - fix 'get_status_video_files' function
 */
function recordings_custom_columns($column) {
    global $post, $wpdb;

    switch ($column) {
        case "image":
            $img = '';
            if(function_exists('get_field')) {
                $img = wp_get_attachment_image_src( get_field( 'thumbnail', $post->ID ), '108p' )[0];
            }
            printf('<img src="%s" class="media-thumbnail img img-54p" alt="">', $img);
            break;
        case "status":
            if(function_exists('get_status_video_files')) {
                $status_num = get_status_video_files( $post->ID, 'lowest' );
                switch ($status_num) {
                    case 5:
                        $status = 'status-on';
                        break;
                    case 4:
                    case 3:
                    case 2:
                    case 1:
                        $status = 'status-active';
                        break;
                    default:
                        $status = 'status-off';
                        break;
                }
                print ( '<span class="status '.$status.'"><span></span></span>' );
            }
            break;
        case "media_speakers":
            $terms = get_the_term_list($post->ID, 'speakers', '', ', ','');
            if ( is_string( $terms ) ) {
                echo $terms;
            } else {
                echo 'Kein Sprecher';
            }
            break;
        case "media_series":
            $terms = get_the_term_list($post->ID, 'series', '', ', ','');
            if ( is_string( $terms ) ) {
                echo $terms;
            } else {
                echo 'Keine Serie';
            }
            break;
    }
}
add_action('manage_recording_posts_custom_column',  'AppTheme\Structure\recordings_custom_columns');


// Remove Serien and Sprecher standard meta box in favor of ACF
if (is_admin()) :
    function remove_meta_boxes() {
        remove_meta_box('tagsdiv-speakers', 'recording', 'side');
        remove_meta_box('tagsdiv-series', 'recording', 'side');
        remove_meta_box('tagsdiv-topics', 'recording', 'side');
        remove_meta_box('tagsdiv-podcasts', 'recording', 'side');
    }
    add_action( 'admin_menu', 'AppTheme\Structure\remove_meta_boxes' );
endif;


/* ========================================================================== */

/**
 * Registers `media` custom post type.
 *
 * @return void
 */
function register_slide_post_type()
{

    register_post_type( 'slide', [
        'description'        => __('Collection of slides for the homepage.', config('textdomain')),
        'public'             => true,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'menu_icon'          => 'dashicons-images-alt',
        'query_var'          => false,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 3,
        'show_in_rest'       => true,
        'rest_base'          => 'slides',
        'supports'           => array( 'title' ),
        'labels' => [
            'name' => _x('Slide', 'post type general name', config('textdomain')),
            'singular_name' => _x('Slides', 'post type singular name', config('textdomain')),
            'menu_name' => _x('Slider', 'admin menu', config('textdomain')),
            'name_admin_bar' => _x('Slides', 'add new on admin bar', config('textdomain')),
            'add_new' => _x('Add New', 'book', config('textdomain')),
            'add_new_item' => __('Add New Slide', config('textdomain')),
            'new_item' => __('New Slide', config('textdomain')),
            'edit_item' => __('Edit Slide', config('textdomain')),
            'view_item' => __('View Slide', config('textdomain')),
            'all_items' => __('All Slides', config('textdomain')),
            'search_items' => __('Search Slides', config('textdomain')),
            'parent_item_colon' => __('Parent Slide:', config('textdomain')),
            'not_found' => __('No slides found.', config('textdomain')),
            'not_found_in_trash' => __('No slides found in Trash.', config('textdomain')),
        ],
    ]);

}
add_action('init', 'AppTheme\Structure\register_slide_post_type');
