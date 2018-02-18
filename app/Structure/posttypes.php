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

    register_post_type('media', [
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
            'name' => _x('Media', 'post type general name', config('textdomain')),
            'singular_name' => _x('Media', 'post type singular name', config('textdomain')),
            'menu_name' => _x('Archive', 'admin menu', config('textdomain')),
            'name_admin_bar' => _x('Media', 'add new on admin bar', config('textdomain')),
            'add_new' => _x('Add New', 'book', config('textdomain')),
            'add_new_item' => __('Add New Media', config('textdomain')),
            'new_item' => __('New Media', config('textdomain')),
            'edit_item' => __('Edit Media', config('textdomain')),
            'view_item' => __('View Media', config('textdomain')),
            'all_items' => __('All Media', config('textdomain')),
            'search_items' => __('Search Media', config('textdomain')),
            'parent_item_colon' => __('Parent Media:', config('textdomain')),
            'not_found' => __('No books found.', config('textdomain')),
            'not_found_in_trash' => __('No books found in Trash.', config('textdomain')),
        ],
    ]);

}
add_action('init', 'AppTheme\Structure\register_media_post_type');


// Sort admin colums of video by date by default
function set_video_post_type_admin_order($wp_query) {
    if (is_admin()) {

        $post_type = $wp_query->query['post_type'];

        if ( $post_type == 'video' && empty($_GET['orderby'])) {
            $wp_query->set('orderby', 'date');
            $wp_query->set('order', 'DESC');
        }

    }
}
add_filter ( 'pre_get_posts', 'AppTheme\Structure\set_video_post_type_admin_order' );


/**
 * Adding Cutsom Columns to the index of post type `media`
 */
// Register the columns
function media_edit_columns($columns) {
    $columns = array(
        "cb"             => "<input type=\"checkbox\" />",
        "image"          => __('Image', config('textdomain')),
        "status"         => '',
        "title"          => __('Title', config('textdomain')),
        "media_speakers" => __('Speaker', config('textdomain')),
        "media_series"   => __('Series', config('textdomain')),
        "date"           => __('Date', config('textdomain'))
    );

    return $columns;
}
add_filter("manage_edit-media_columns", "AppTheme\Structure\media_edit_columns");


// Register the columns as sortable
function media_sortable_columns($columns) {
    $custom = array(
    // meta column id => sortby value used in query
        "media_speakers" => __('Speaker', config('textdomain')),
        "media_series" => __('Series', config('textdomain'))
    );

    return wp_parse_args($custom, $columns);
}
add_filter("manage_edit-media_sortable_columns", 'AppTheme\Structure\media_sortable_columns');


// Make the column "Sprecher" order correctly
function media_speakers_clauses( $clauses, $wp_query ) {
    global $wpdb;

    if ( isset( $wp_query->query['orderby'] )
            && $wp_query->query['orderby'] === __('Speaker', config('textdomain')) ) {

        $clauses['join'] .= <<<SQL
LEFT OUTER JOIN {$wpdb->term_relationships} ON {$wpdb->posts}.ID={$wpdb->term_relationships}.object_id
LEFT OUTER JOIN {$wpdb->term_taxonomy} USING (term_taxonomy_id)
LEFT OUTER JOIN {$wpdb->terms} USING (term_id)
SQL;

        $clauses['where'] .= " AND (taxonomy = 'media_speakers' OR taxonomy IS NULL)";
        $clauses['groupby'] = "object_id";
        $clauses['orderby'] = "GROUP_CONCAT({$wpdb->terms}.name ORDER BY name ASC) ";
        $clauses['orderby'] .= ( 'ASC' == strtoupper( $wp_query->get('order') ) ) ? 'ASC' : 'DESC';
    }

    return $clauses;
}
add_filter( 'posts_clauses', 'AppTheme\Structure\media_speakers_clauses', 10, 2 );


// Make the column "Serie" order correctly
function media_series_clauses( $clauses, $wp_query ) {
    global $wpdb;

    if ( isset( $wp_query->query['orderby'] )
        && $wp_query->query['orderby'] === __('Series', config('textdomain')) ) {

        $clauses['join'] .= <<<SQL
LEFT OUTER JOIN {$wpdb->term_relationships} ON {$wpdb->posts}.ID={$wpdb->term_relationships}.object_id
LEFT OUTER JOIN {$wpdb->term_taxonomy} USING (term_taxonomy_id)
LEFT OUTER JOIN {$wpdb->terms} USING (term_id)
SQL;

        $clauses['where'] .= " AND (taxonomy = 'media_series' OR taxonomy IS NULL)";
        $clauses['groupby'] = "object_id";
        $clauses['orderby'] = "GROUP_CONCAT({$wpdb->terms}.name ORDER BY name ASC) ";
        $clauses['orderby'] .= ( 'ASC' == strtoupper( $wp_query->get('order') ) ) ? 'ASC' : 'DESC';
    }

    return $clauses;
}
add_filter( 'posts_clauses', 'AppTheme\Structure\media_series_clauses', 10, 2 );


/*
 * Display the columns content
 *
 * TODO:
 * - fix 'get_status_video_files' function
 */
function media_custom_columns($column) {
    global $post, $wpdb;

    switch ($column) {
        case "image":
            if(function_exists('get_field')) {
                $thumb = wp_get_attachment_image_src( get_field( 'media_thumbnail', $post->ID ), '108p' )[0];
                if ( !empty( $thumb ) ) {
                    $img_tag = sprintf('<img src="%s" class="media-thumbnail img-54p" alt="icon">', $thumb);
                    if( current_user_can( 'edit_others_posts' ) ) {
                        edit_post_link( $img_tag, '', '', $post->ID );
                    } else {
                        print($img_tag);
                    }
                } else {
                    print( "Kein Bild" );
                }
            }
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
            $terms = get_the_term_list($post->ID, 'media_speakers', '', ', ','');
            if ( is_string( $terms ) ) {
                echo $terms;
            } else {
                echo 'Kein Sprecher';
            }
            break;
        case "media_series":
            $terms = get_the_term_list($post->ID, 'media_series', '', ', ','');
            if ( is_string( $terms ) ) {
                echo $terms;
            } else {
                echo 'Keine Serie';
            }
            break;
    }
}
add_action("manage_media_posts_custom_column",  "AppTheme\Structure\media_custom_columns");


// Remove Serien and Sprecher standard meta box in favor of ACF
if (is_admin()) :
    function remove_meta_boxes() {
        remove_meta_box('tagsdiv-media_speakers', 'media', 'side');
        remove_meta_box('tagsdiv-media_series', 'media', 'side');
        remove_meta_box('tagsdiv-media_topics', 'media', 'side');
        remove_meta_box('tagsdiv-podcasts', 'media', 'side');
    }
    add_action( 'admin_menu', 'AppTheme\Structure\remove_meta_boxes' );
endif;


