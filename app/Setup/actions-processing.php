<?php

namespace Tonik\Theme\App\Setup;

use function Tonik\Theme\App\config;

/*
 * This script is triggered every time a video in the wordpress backend is being saved.
 */

/**
 * Is triggered whenever a post or page is created or updated.
 *
 * Note: ACF fires with priority 10, this needs to execute afterwards so that
 * speaker and series are updated already
 */
add_action('save_post', 'Tonik\Theme\App\Setup\process_on_save', 11);
function process_on_save ($post_id) {

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
  $speaker = wp_get_post_terms($post_id, 'speakers') ?? [];
  $speaker = reset($speaker)->name;
  $series = wp_get_post_terms($post_id, 'series') ?? [];
  $series = reset($series)->name;
  $attachment_id = get_field('thumbnail', $post_id);

  // Get thumbnail path
  $thumbnailPath = get_attached_file($attachment_id); // without correct filename
  $thumbnailUrl = wp_get_attachment_image_url($attachment_id, '720p'); // with correct filename
  $thumbnailPathParts = explode('/', $thumbnailPath);
  array_pop($thumbnailPathParts);
  $thumbnailUrlParts = explode('/', $thumbnailUrl);
  $thumbnail = implode('/', array_merge($thumbnailPathParts, [array_pop($thumbnailUrlParts)]));

  /*
   * $filename: the selected video or audio file; $filename = (var) 'null' for empty values
   * this foreach loop will only run though the first cycle to grab the filename
   */
  $filename = stripslashes($_POST['acf']['field_52c9deb0d3c39']);

  /*
   * Check if the database contains anything already
   */
  $existing_rows = $wpdb->get_results(
    "SELECT * FROM wp_video_files WHERE post_id = $post_id",
    ARRAY_A,
  );

  /*
   * set up process if a new filename is provided
   */
  if ($filename && $filename !== '' && $filename !== 'null') {
    $process_me = true;
    $_POST['acf']['field_52c9deb0d3c39'] = 'null';
  }

  if ($existing_rows) { // the database DOES contain entries for that post

    if ($process_me) { // a new file has been provided: the old files will be deleted and the new will be processed
      // delete identical filenames
      $wpdb->delete(
        'wp_video_files',
        ['post_id' => $post_id, 'type' => 'source', 'relative_url' => $filename]
      );

      // set status 99 'DELETE' for all previous source files
      $wpdb->update(
        'wp_video_files',
        ['status' => 99],
        ['post_id' => $post_id, 'type' => 'source']
      );

      // log
      file_put_contents(
        config('processing')['log-dir'] . 'main.log',
        date('[Y-m-d H:i:s]') . " New file provided for $post_id (Mark old source files 'obsolet')" . PHP_EOL,
        FILE_APPEND
      );
    }


    /**
     * Update all rows with new speaker & series
     */
    foreach ($existing_rows as $row) {
      $wpdb->update(
        'wp_video_files',
        [
          'thumbnail' => $thumbnail,
          'speaker' => $speaker,
          'series' => $series,
          'post_modified' => current_time('mysql', 1),
        ],
        ['ID' => $row['ID']],
      );
    }

  }

  /*
   * set up database for processing of file
   */
  if ($process_me) {
    $wpdb->insert(
      'wp_video_files',
      array(
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
        'thumbnail'     => $thumbnail,
        'speaker'       => $speaker,
        'series'        => $series,
        'created'       => current_time('mysql'),
        'modified'      => current_time('mysql'),
      ),
    );

    // log the event
    file_put_contents(
      config('processing')['log-dir'] . 'main.log',
      date('[Y-m-d H:i:s]') . " Post $post_id `" . $_POST['post_title'] . "`, `$filename`" . PHP_EOL,
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
add_action('before_delete_post', 'Tonik\Theme\App\Setup\delete_video');
function delete_video ($postid) {

  // We check if the global post type isn't ours and just return
  global $post_type;
  if ( !$postid || $post_type !== 'recordings' ) return;

  // Mark video and audio files as 'OBSOLET' – status 255
  global $wpdb;
  $wpdb->update(
    'wp_video_files',
    ['status' => 99],
    ['post_id' => $postid],
  );

  // log the event
  file_put_contents(
    config('processing')['log-dir'].'main.log',
    date('[Y-m-d H:i:s]') . " Post $postid deleted" . PHP_EOL,
    FILE_APPEND
  );
}
