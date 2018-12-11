<?php

namespace Tonik\Theme\App\Legacy;

/*
|-----------------------------------------------------------
| Recordings Legacy Functions
|-----------------------------------------------------------
*/

use function Tonik\Theme\App\Helper\formatbytes;


function get_video_file( $post_id, $type = '', $postfix = '' ) {
    $files = get_video_files($post_id, $type, 1);
    if( isset( $files[$type.$postfix] ) ) return $files[$type.$postfix];
    if( $type ) {
        foreach ($files as $file) {
            if( $file->type == $type ) return $file;
        }
        return null;
    }
    return reset($files);
}


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
                SELECT *
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
                SELECT *
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


/**
 * LEGACY
 * Get a number of a video/audio file status corresponding to the staus flag
 * This function has been exported because it is used by embed.php
 *
 * 0 – file does not exist
 * 1 – 'wait'
 * 2 – 'open'
 * 3 – 'processing'
 * 4 – 'ready'
 * 5 – 'uploaded'
 *
 * INPUT
 *  $post_id    -> $post->ID
 *  $value      -> 'highest', 'lowest' (std) or false for array
 *
 * OUTPUT
 *  'higehst'   -> the highest status of all files below 200 (eg. 60 for ERROR)
 *  'lowest'    -> the lowest status of all files (eg. 3 for PROCESSING)
 */

function get_status_video_files( $post_id, $value = false ) {
    global $wpdb;

    $files = $wpdb->get_results(
        "SELECT * FROM wp_video_files WHERE post_id = '$post_id' AND status <= 200",
        ARRAY_A
    );

    $return = array(
        'highest'   => 0,
        'lowest'    => 200
    );
    if ( !empty( $files ) ) {
        foreach( $files as $file ) {
            $i = (int)$file['status'];
            $return['highest'] = $return['highest'] < $i ? $i : $return['highest'];
            $return['lowest'] = $return['lowest'] > $i ? $i : $return['lowest'];
        }
    } else {
        $return['lowest'] = 0;
    };

    if( $value ) return $return[$value];
    return $return;
}


/**
 * LEGACY
 * Add a widget to the dashboard.
 *
 * This function is hooked into the 'wp_dashboard_setup' action below.
 *
 * @since Joel Media 1.1
 * @update Joel Media 1.2.1
 */
function dashboard_widgets() {

    wp_add_dashboard_widget(
        'dashboard_jmm_video_audio',            // Widget slug.
        'Joel Media Videos',                    // Title.
        'Tonik\Theme\App\Legacy\dashboard_widget_function'   // Display function.
    );
}
add_action( 'wp_dashboard_setup', 'Tonik\Theme\App\Legacy\dashboard_widgets' );

/**
 * Create the function to output the contents of our Dashboard Widget.
 */
function dashboard_widget_function() {
    global $wpdb;

    // Get the number of videos and audios
    $num_videos = wp_count_posts('recordings');
    $num_vsprecher = wp_count_terms('speakers');
    $num_vserien = wp_count_terms('series');

    // Get Filesize
    $videosize = $wpdb->get_var( "SELECT sum(size) FROM wp_video_files" );

    ?>
<div id="video-count" class="activity-block">
    <div class="main">
        <h4><span class="video-count-headline headline">Videoarchiv</span></h4>
        <ul>
            <li class="video-count"><a href="edit.php?post_type=video"><?php echo $num_videos->publish; ?> Videos</a></li>
            <li class="video-sprecher-count"><a href="edit-tags.php?taxonomy=video_sprecher&post_type=video"><?php echo $num_vsprecher; ?> Sprecher</a></li>
            <li class="video-serien-count"><a href="edit-tags.php?taxonomy=video_serien&post_type=video"><?php echo $num_vserien; ?> Serien</a></li>
        </ul>
        <h4>Gr&ouml;sse auf Datentr&auml;ger: <span class="dashicons dashicons-category"></span> <?php echo formatbytes($videosize); ?></h4>
    </div>
</div>
    <?php
}





