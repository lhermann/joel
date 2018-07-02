<?php

namespace Tonik\Theme\App\Legacy;

/*
|-----------------------------------------------------------
| Recordings Legacy Functions
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
 * LEGACY
 * Returns an array with files for the download dropdown
 */
function get_download_files($post_id) {
    global $wpdb;
    $query = sprintf('
        (SELECT * FROM wp_video_files WHERE post_id = %1$d AND status = 5 AND (resolution = "720p" OR resolution = "360p"))
        UNION
        (SELECT * FROM wp_video_files WHERE post_id = %1$d AND status = 5 AND type = "audio" ORDER BY bitrate DESC LIMIT 1)
        UNION
        (SELECT * FROM wp_video_files WHERE post_id = %1$d AND status = 5 AND type = "audio" ORDER BY bitrate ASC LIMIT 1)
        ',
        $post_id
    );
    return $wpdb->get_results($query);
}





