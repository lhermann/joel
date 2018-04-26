<?php

namespace AppTheme\Helper;

/*
|-----------------------------------------------------------
| Recording Helper Functions
|-----------------------------------------------------------
*/

/**
 * LEGACY
 * Returns video files for one post object
 */
function get_video_files( $post_id, $type = false, $limit = 0 ) {
    global $wpdb;

    switch ($type) {
        case 'video':
        case 'audio':
        case 'source':
        case 'smil':
            $query = sprintf("
                SELECT type, location, status, relative_url, size, resolution, bitrate, length, flags
                FROM wp_video_files
                WHERE post_id = %d
                AND type = '%s'
                AND status = 5
                ORDER BY bitrate DESC;",
                $post_id,
                $type
            );
            break;

        default:
            $query = sprintf("
                SELECT type, location, status, relative_url, size, resolution, bitrate, length, flags
                FROM wp_video_files
                WHERE post_id = %d;",
                $post_id
            );
            break;
    }

    $files = $wpdb->get_results(
        $query,
        OBJECT
    );

    if($type == 'raw') return $files;

    $return = array();
    foreach ($files as $i => $file) {
        switch ($file->type) {
            case 'video':
                $return[$file->type.$file->resolution] = $file;
                break;
            case 'audio':
                $return[$file->type.$file->bitrate] = $file;
                break;
            default:
                $return[$file->type] = $file;
                break;
        }
    }

    return $return;
}


/**
 * LEGACY
 * Returns the length of a video
 */
function get_video_length( $post_id = false, $video_files = [] ) {
    if(!$video_files) {
        $video_files = get_video_files($post_id);
    }
    $length = '';
    foreach ($video_files as $file) {
        if($length = $file->length) break;
    }
    preg_match( '/([^D0].{3}|[^D0].{2}|[^D0]|)\d:\d{2}$/', $length, $matches ); // remove leading zeroes
    return isset($matches[0]) ? $matches[0] : '0';
}


/**
 * Function to retrieve the taxonomy terms by slug associated to a certain term_taxonomy_id
 * eg. get Sprecher associated with Serie X
 * jmm_get_terms_by_taxonomy_id( $tax_id / $term_taxonomy_id, $taxonomy_slug )
 * @since Joel Media 1.0
 */
function get_terms_associated_with_term( $term, $taxonomy_slug ) {
    global $wpdb;

    $term_id = is_object($term) ? $term->term_id : $term;

    $querystr = "
        SELECT DISTINCT term.term_id, term.name, tax.taxonomy, term.slug
        FROM $wpdb->terms AS term
        JOIN $wpdb->term_taxonomy AS tax ON tax.term_id = term.term_id
        JOIN $wpdb->term_relationships AS rel ON rel.term_taxonomy_id = tax.term_taxonomy_id
        WHERE rel.object_id IN (
            SELECT irel.object_id
            FROM $wpdb->terms AS iterm
            JOIN $wpdb->term_taxonomy AS itax on itax.term_id = iterm.term_id
            JOIN $wpdb->term_relationships AS irel on irel.term_taxonomy_id = itax.term_taxonomy_id
            WHERE iterm.term_id = $term_id
        )
        AND tax.taxonomy = '$taxonomy_slug';
    ";

    return $wpdb->get_results($querystr, OBJECT);
};


/**
 * Same as count( get_terms_associated_with_term(...) ),
 * but executing around 20% faster.
 */
function count_terms_associated_with_term( $term, $taxonomy_slug ) {
    global $wpdb;

    $term_id = is_object($term) ? $term->term_id : $term;

    $querystr = "
        SELECT COUNT(DISTINCT term.term_id) AS 'count'
        FROM $wpdb->terms AS term
        JOIN $wpdb->term_taxonomy AS tax ON tax.term_id = term.term_id
        JOIN $wpdb->term_relationships AS rel ON rel.term_taxonomy_id = tax.term_taxonomy_id
        WHERE rel.object_id IN (
            SELECT irel.object_id
            FROM $wpdb->terms AS iterm
            JOIN $wpdb->term_taxonomy AS itax on itax.term_id = iterm.term_id
            JOIN $wpdb->term_relationships AS irel on irel.term_taxonomy_id = itax.term_taxonomy_id
            WHERE iterm.term_id = $term_id
        )
        AND tax.taxonomy = '$taxonomy_slug';
    ";

    return (int) $wpdb->get_row($querystr, OBJECT)->count;
};





