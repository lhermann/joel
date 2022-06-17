<?php

namespace Tonik\Theme\App\Helper;

/*
|-----------------------------------------------------------
| Recording Helper Functions
|-----------------------------------------------------------
*/


function ffprobe_recording( $file ) {
    $video = new \stdClass();
    $video->error = false;

    $exec = "ffprobe -show_streams " . escapeshellarg( $file ) . " 2>&1";
    exec ( $exec, $output );


    // Provide face output for development environment
    if( strpos ( implode($output) , "not found" ) ) {
        $video->error = $output;
        return $video;
    }

    $pattern = '/(?:\[STREAM\].*?index=0).*?width=(?<w>\d+).*?height=(?<h>\d+).*?sample_aspect_ratio=(?<par>\d+:\d+).*?display_aspect_ratio=(?<dar>\d+:\d+).*?r_frame_rate=(?<fps>\d+).*?bit_rate=(?<bitrate>\d+)/s';
    preg_match($pattern, implode($output), $matches);

    // print("<pre>"); print_r(implode($output)); print("</pre>");

    $video->width = $matches['w'];
    $video->height = $matches['h'];
    $video->bitrate = floor($matches['bitrate']/1000);
    $video->par = $matches['par'];
    $video->dar = $matches['dar'];
    $video->fps = $matches['fps'];

    return $video;
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


function get_series_of_podcast( $podcast ) {
    global $wpdb;

    $podcast_id = is_object($podcast) ? $podcast->term_id : $podcast;

    $querystr = "
        SELECT *
        FROM $wpdb->terms AS term
        JOIN $wpdb->term_taxonomy AS tax ON tax.term_id = term.term_id
        JOIN $wpdb->termmeta AS meta ON meta.term_id = term.term_id
        WHERE meta.meta_key = 'podcast'
        AND meta.meta_value = $podcast_id;
    ";

    return $wpdb->get_results($querystr, OBJECT);
}




