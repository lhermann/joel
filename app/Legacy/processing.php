<?php

namespace Tonik\Theme\App\Legacy;

use function Tonik\Theme\App\config;

/*
 * This script is triggered every time a video in the wordpress backend is being saved.
 * This script will start a cron job which will trigger 'filesize.php', 'process.php' and 'upload.php'
 *
 * Number System
 *
 * Num:   Function:                                                             Script:
 * PROCESS
 * 0    – file does not exist                                                   –
 * 1    – wait until the source file is fully uploaded to the server            filesize.txt
 * 2    – the file is fully uploaded and ready to be processed                  process.php
 * 3    – the file is being processed right now, thus locked for other scripts  worker.php
 * 4    – the file is ready to be uploaded to the streaming server              upload.php
 * 5    – the file is fully processed and uploaded to the streaming server      –
 *
 * ERROR
 * 60   – 'ERROR' a general error has been encountered                          manual
 * 61   – 'INCOMPATIBLE' the file cannot be processed                           manual
 *
 * DELETE
 * 254  – 'HELD BACK' - delete after 2 months                                   cleanup.php
 * 255  – 'OBSOLET' the file is marked to be deleted                            cleanup.php
 */

/**
 * Is triggered whenever a post or page is created or updated.
 * (ACF fires with priority 10, so this one needs to be first since it alters
 * the $_POST variable) – this line is obsolete by know, yet I haven't chaned
 * the priority
 */
add_action( 'save_post', 'Tonik\Theme\App\Legacy\process_on_save', 1 );
function process_on_save( $post_id ) {

    /*
     * Avoid the function to trigger at wrong time
     */
    if ( !isset($_POST['post_type']) ) return; // check if it is post-type index exists to prevent errors
    if ( $_POST['post_type'] !== 'recordings' ) return; // check if post-type is video or audio
    if ( !isset( $_POST['acf'] ) ) return; //do not execute for Quick Edit

    /*
     * get and set variables
     */
    global $wpdb;
    $process_me = false;

    /*
     * $filename: the selected video or audio file; $filename = (var) 'null' for empty values
     * this foreach loop will only run though the first cycle to grab the filename
     */
    $filename = stripslashes($_POST['acf']['field_52c9deb0d3c39']);
    $_POST['acf']['field_52c9deb0d3c39'] = 'null';
    // foreach ( $_POST['acf'] as $field_key => $field ) {
    //     $filename = stripslashes( $field );
    //     $_POST['acf'][$field_key] = 'null';
    //     break;
    // };


    /*
     * Check if the database contains anything already
     */
    $dbcheck = $wpdb->get_results(
        "SELECT * FROM wp_video_files WHERE post_id = ".$post_id,
        ARRAY_A
    );

    /*
     * set up process if a new filename is provided
     */
    if ( $filename !== '' && $filename ) $process_me = true;

    if ( $dbcheck ) { // the database DOES contain entries for that post

        if ( $filename !== '' ) { // a new file has been provided: the old files will be deleted and the new will be processed
            // delete identical filenames
            $wpdb->delete(
                'wp_video_files',
                ['post_id' => $post_id, 'type' => 'source', 'relative_url' => $filename]
            );

            // set status 99 'DELETE' for old fiels
            $wpdb->update(
                'wp_video_files',
                array ( 'status' => 99 ),
                array ( 'post_id' => $post_id, 'type' => 'source' )
            );
            // log
            file_put_contents(
                config('processing')['log-dir'].'main.log',
                current_time('[d M Y H:i:s] ').$post_id." MAIN: – set old files STATUS 99 'obsolet' -\n",
                FILE_APPEND
            );
        }

    }

    /*
     * set up database for processing of file
     */
    if ( $process_me ) {

        $wpdb->insert( // insert a row
            'wp_video_files', // table
            array( // value
                'post_id'       => $post_id,
                'location'      => 'local',
                'status'        => 1,
                'relative_url'  => $filename,
                'type'          => 'source',
                'bitrate'       => 0,
                'resolution'    => '',
                'size'          => filesize(config('processing')['upload-dir'].$filename),
                'length'        => '',
                'flags'         => '',
                'created'       => current_time( 'mysql' ),
                'modified'      => current_time( 'mysql' )
            ),
            array( // format
                '%d',
                '%s',
                '%d',
                '%s',
                '%s',
                '%d',
                '%s',
                '%d',
                '%s',
                '%s',
                '%s',
                '%s'
            )
        );
        // log the event
        file_put_contents(
            config('processing')['log-dir'].'main.log',
            current_time('[d M Y H:i:s] ').$post_id." MAIN: Post Title: '".$_POST['post_title']."' | New Source: '$filename'\n",
            FILE_APPEND
        );
    }

    return;
};


/**
 * This function is triggered whenever a video or audio is being deleted (removed from trash)
 *
 * it marks the database entries connected to the post ID as 'OBSOLET'
 * namley, 'video', 'audio_lq' and 'audio_hq' is set to STATUS 99
 * the deleting process is taken care of by hand or another script
 */
add_action( 'before_delete_post', 'Tonik\Theme\App\Legacy\delete_video' );
function delete_video( $postid ){

    // We check if the global post type isn't ours and just return
    global $post_type;
    if ( !$postid || $post_type !== 'recordings' ) return;

    // Mark video and audio files as 'OBSOLET' – status 255
    global $wpdb;
    $wpdb->update(
        'wp_video_files',
        array ( 'status' => 99 ),
        array ( 'post_id' => $postid )
    );

    // log the event
    file_put_contents(
        config('processing')['log-dir'].'main.log',
        current_time('[d M Y H:i:s] ')." Post $postid deleted.\n",
        FILE_APPEND
    );
}
