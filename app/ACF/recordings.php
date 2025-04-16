<?php

namespace Tonik\Theme\App\ACF;

/*
|-----------------------------------------------------------
| Advanced Custom Fields
|-----------------------------------------------------------
|
| The ACF settings for recordings
|
*/

use function Tonik\Theme\App\config;
use function Tonik\Theme\App\Helper\formatbytes;
use function Tonik\Theme\App\Helper\ffprobe_recording;
use Tonik\Theme\App\Helper\Google_API;

if(function_exists('acf_add_local_field_group')):


  /**
   * Show youtube video upload status
   * And set all new videos to 'enqueue_new'
   */
  add_filter('acf/load_field/name=youtube_upload', 'Tonik\Theme\App\ACF\youtube_upload');
  function youtube_upload( $field ) {
    global $post;

    // Only modify the field when we're on the post edit screen
    // Check if we're in admin AND editing a single post
    if( !is_admin() || !$post || get_current_screen()->base !== 'post' ) {
        return $field;
    }

    $client = new Google_API();

    $api_class = '';
    $api_text = '';
    if ($client->authenticated()) {
      $api_class = 'u-green';
      $api_text = '[YouTube API: authenticated]';
    } else {
      $api_class = 'u-red';
      $api_text = sprintf(
        '[YouTube API: missing - <a href="%s" target="_blank">%s</a>]',
        $client->getAuthUrl($post->ID),
        'Authenticate'
      );
    }
    $field['instructions'] .= sprintf(
      ' <span class="%s">%s</span>',
      $api_class,
      $api_text
    );

    // automatically enqueue all videos after 2020-08-01
    if (strtotime('2020-08-01') < strtotime('now')) {
      $field['default_value'] = 'enqueue_new';
    }

    return $field;
  }


  /**
   * Generate the container for the video processing status
   * The container will reload itself every 60 seconds
   */
  add_filter('acf/load_field/name=recording_status', 'Tonik\Theme\App\ACF\recording_status');
  function recording_status( $field ) {
    global $post;
    if( !$post || !is_admin() ) return $field;
    ob_start();
    ?>
    <div
      data-vue="JoRecordingStatus"
      data-options='<?= json_encode([ 'postid' => $post->ID ]) ?>'
    ></div>
    <?php
    $field['message'] = str_replace(["\r","\n"],"",ob_get_clean());
    if(ob_get_length()) ob_end_flush();
    return $field;
  }


  /**
   * Helper to render video select dropdown feedback item
   * @param  string  $field
   * @param  string  $value
   * @param  string  $recommended
   * @param  boolean $valid
   * @return string
   */
  function dropdown_feedback_item ($field, $value, $recommended = '', $valid = true) {
    return sprintf(
      '<li>
        <span style="color: %s;">%s</span>
        <span>%s:</span>
        <strong>%s</strong>
        %s
      </li>',
      $valid ? 'green' : 'red',
      $valid ? '✓' : '✗',
      $field,
      $value,
      !$valid && $recommended ? "<span>→ $recommended</span>" : "",
    );
  }


  /**
   * Populate the select dropdown list
   *
   * #1 Manipulate the 'select' field for videos and populate them with a list of all the videos
   * existing in the 'upload-video/' directory
   *
   * #2 Display an alert box to show if a file has been selected.
   * Also render an alert box to warn, that a newly selected file will overwrite the old, and show this alert box via javascript
   * the css-class 'alert-invisible' is used to hide the box
   *
   * #3 Render boxes to show video information
   *
   * Note: DAR = SAR (Sample Aspect Ratio)
   */
  add_filter('acf/load_field/name=recording_select', 'Tonik\Theme\App\ACF\populate_select_video');
  function populate_select_video( $field ) {
    global $post, $wpdb;
    if( !$post || !is_admin() ) return $field;

    // Settings
    $min_height = 720;
    $min_bitrate = 2500;

    // read file directory
    $directory = file_exists(config('processing')['upload-dir']) ? array_filter(
      scandir(config('processing')['upload-dir']),
      function($file) {
        return(preg_match( '/\.(mov|mpg|mp4|flv|avi|mpeg4|mkv|mpeg|mpg2|mpeg2)$/', $file ));
      }
    ) : [];

    // array for select field and video information
    $videofiles = array();
    $video_information = array();

    foreach ( $directory as $key => $file ) {
      // fill select
      $videofiles[$file] = $file.' ['.formatbytes( filesize( config('processing')['upload-dir'].$file ) ).']';
      // prepare video info boxes
      $video = ffprobe_recording(config('processing')['upload-dir'] . $file);

      if( $video->error ) {

        // remove ffmpeg build information
        for ($i=1; $i < 11; $i++) {
          unset($video->error[$i]);
        }
        $video_information[] = sprintf('<div id="%1$s" data-value="%2$s" class="%3$s"><h4>%5$s %2$s</h4>%4$s</div>',
           'format-info-'.$key,
           $file,
           'video-alert hidden alert-warning',
           '<pre>'.implode("\n", $video->error).'</pre>',
           '<span class="dashicons dashicons-info"></span>'
        );

      } else {
        $unfit = $video->height < 720
          || $video->par !== '1:1'
          || $video->dar !== '16:9';

        $output = [];
        $output[] = dropdown_feedback_item(
          "Datei",
          $file,
          null,
          !$unfit,
        );
        $output[] = dropdown_feedback_item(
          "Aufl&ouml;sung",
          $video->width . "×" . $video->height,
          "Min: 1280×720, Empfohlen: 1920×1080",
          $video->height >= $min_height,
        );
        $output[] = dropdown_feedback_item(
          "Bitrate",
          $video->bitrate . " kb/s",
          "Min: 2.500 kb/s, Empfohlen: 5.000 kb/s",
          $video->bitrate >= $min_bitrate,
        );
        $output[] = dropdown_feedback_item(
          "Pixel Aspect Ratio (PAR)",
          $video->par,
          "Erfordert: 1:1",
          $video->par === '1:1',
        );
        $output[] = dropdown_feedback_item(
          "Display Aspect Ratio (DAR)",
          $video->dar,
          "Erfordert: 16:9",
          $video->dar === '16:9',
        );
        $output[] = dropdown_feedback_item(
          "Frame Rate",
          $video->fps . "fps",
          "Empfohlen: 30fps",
          $video->fps >= 30,
        );

        // Add video format info boxes
        $video_information[] = sprintf(
          '<div id="%s" data-value="%s" class="video-alert %s hidden">%s</div>',
          "format-info-$key",
          $file,
          $unfit ? 'alert-error' : 'alert-success',
          "<ul>" . join('', $output) . "</ul>",
        );

      }
    }

    // Reset choices
    $field['choices'] = $videofiles;

    // Add the alert boxes
    $db_file = $wpdb->get_results(
      "SELECT * FROM wp_video_files WHERE post_id = '$post->ID' AND status <= 10",
      ARRAY_A
    );
    $alert = '<div id="alert-exists" class="video-alert alert-success hidden">Es ist bereits ein Video vorhanden.</div>';
    $alert .= '<div id="alert-overwritten" class="video-alert alert-error hidden"><strong>Achtung:</strong> Vorhandene Datei wird &uuml;berschrieben</div>';
    $js = sprintf('<div id="videofile-exists" data-bool="%s"></div>',
      $db_file ? "true" : "false"
    );

    // Append instructions
    $field['instructions'] .= '<p><a id="ftp-credentials-button" class="button">FTP Serverdaten anzeigen</a>  &nbsp; Alle Videodateien (<code>.mp4</code>, <code>.mpg</code>, <code>.mpg2</code>, usw.) stehen zur Auswahl zur Verfügung.</p>';
    $field['instructions'] .= '<div id="ftp-credentials" class="video-alert alert-invisible"><strong>FTP Zugang f&uuml;r Videodateien</strong><br/>Host: <code>'.config('processing')['ftp-host'].'</code><br/>Username: <code>'.config('processing')['ftp-user'].'</code><br/>Password: <code>'.config('processing')['ftp-password'].'</code></div>';
    $field['instructions'] .= '<p class="u-text-- u-muted">Die ausgewählte Datei wird vom Server verarbeitet und die existierende Datei (falls vorhanden) wird überschrieben.</p>';
    $field['instructions'] .= $alert.$js.implode($video_information);

    // Important: return the field
    return $field;
  }


  /*
   * Recording ACF fields
   */
  if( function_exists('acf_add_local_field_group') ):
  acf_add_local_field_group(array(
    'key' => 'group_acf_recording',
    'title' => 'Recordings (Recovered)',
    'fields' => array(
      array(
        'key' => 'field_5d0ce6616e3b6',
        'label' => 'Eigener Upload',
        'name' => '',
        'type' => 'tab',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'placement' => 'top',
        'endpoint' => 0,
      ),
      array(
        'key' => 'field_53dfb75328fc8',
        'label' => __('Status', config('textdomain')),
        'name' => 'recording_status',
        'type' => 'message',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '100',
          'class' => '',
          'id' => '',
        ),
        'message' => '',
        'new_lines' => 'wpautop',
        'esc_html' => 0,
      ),
      array(
        'key' => 'field_52c9deb0d3c39',
        'label' => __('Select Recording', config('textdomain')),
        'name' => 'recording_select',
        'type' => 'select',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '100',
          'class' => '',
          'id' => '',
        ),
        'choices' => array(
          'Gold Play Buddon.mp4' => 'Gold Play Buddon.mp4 [5 MB]',
          'logo-assembly-cut.mp4' => 'logo-assembly-cut.mp4 [5 MB]',
          'trailer-ausweg-2012.mp4' => 'trailer-ausweg-2012.mp4 [59 MB]',
        ),
        'default_value' => array(
        ),
        'allow_null' => 1,
        'multiple' => 0,
        'ui' => 0,
        'ajax' => 0,
        'return_format' => 'value',
        'placeholder' => '',
      ),
      array(
        'key' => 'field_4fb10184a8596',
        'label' => __('Thumbnail', config('textdomain')),
        'name' => 'thumbnail',
        'type' => 'image',
        'instructions' => 'Erforderliche Aufl&ouml;sung in Pixel: 1920x1080',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'return_format' => 'id',
        'preview_size' => '360p',
        'library' => 'all',
        'min_width' => 1280,
        'min_height' => 720,
        'min_size' => '',
        'max_width' => '',
        'max_height' => '',
        'max_size' => '',
        'mime_types' => '',
      ),
      array(
        'key' => 'field_5d0ce6ac6e3b7',
        'label' => 'YouTube Video',
        'name' => '',
        'type' => 'tab',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'placement' => 'top',
        'endpoint' => 0,
      ),
      array(
        'key' => 'field_5ecd4cafdc1e9',
        'label' => 'YouTube-Upload',
        'name' => 'youtube_upload',
        'type' => 'button_group',
        'instructions' => 'Video zum Upload auf YouTube markieren.',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'choices' => array(
          'false' => '✗ Nein',
          'enqueue_old' => 'Einreihen: Alte zuerst',
          'enqueue_new' => 'Einreihen: Neue zuerst',
          'enqueue_priority' => 'Einreihen: Priorität',
          'uploading' => '⬆ Wird Hochgeladen',
          'uploaded' => '✓ Fertig Hochgeladen',
        ),
        'allow_null' => 0,
        'default_value' => 'false',
        'layout' => 'horizontal',
        'return_format' => 'value',
      ),
      array(
        'key' => 'field_5d0ce6e66e3b9',
        'label' => 'YouTube Video',
        'name' => 'youtube_video',
        'type' => 'oembed',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'width' => '',
        'height' => '',
      ),
      array(
        'key' => 'field_5d0ce85050ab5',
        'label' => '',
        'name' => '',
        'type' => 'tab',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'placement' => 'top',
        'endpoint' => 1,
      ),
      array(
        'key' => 'field_5ccad024b0ad4',
        'label' => 'Beschreibung',
        'name' => 'content',
        'type' => 'wysiwyg',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'default_value' => '',
        'tabs' => 'all',
        'toolbar' => 'full',
        'media_upload' => 1,
        'delay' => 1,
      ),
    ),
    'location' => array(
      array(
        array(
          'param' => 'post_type',
          'operator' => '==',
          'value' => 'recordings',
        ),
      ),
    ),
    'menu_order' => 0,
    'position' => 'acf_after_title',
    'style' => 'seamless',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => array(
      0 => 'excerpt',
      1 => 'discussion',
      2 => 'comments',
      3 => 'slug',
      4 => 'author',
      5 => 'format',
      6 => 'featured_image',
      7 => 'categories',
      8 => 'tags',
      9 => 'send-trackbacks',
    ),
    'active' => true,
    'description' => '',
  ));
  endif;

  /*
   * Podcast Taxonomy ACF fields
   */
  acf_add_local_field_group(array(
    'key' => 'group_59de0a51d9c79',
    'title' => 'Taxonomy Podcast',
    'fields' => array(
      array(
        'key' => 'field_59de0a67baf08',
        'label' => __('Image', config('textdomain')),
        'name' => 'image',
        'type' => 'image',
        'instructions' => 'Artwork must be a minimum size of 1400 x 1400 pixels and a maximum size of 3000 x 3000 pixels, in JPEG or PNG format, 72 dpi, with appropriate file extensions (.jpg, .png), and in the RGB colorspace.',
        'required' => 1,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'return_format' => 'id',
        'preview_size' => 'medium',
        'library' => 'all',
        'min_width' => 1400,
        'min_height' => 1400,
        'min_size' => '',
        'max_width' => '',
        'max_height' => '',
        'max_size' => '',
        'mime_types' => '',
      ),
      array(
        'key' => 'field_59de0abf106de',
        'label' => __('Author', config('textdomain')),
        'name' => 'autor',
        'type' => 'taxonomy',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'taxonomy' => 'speakers',
        'field_type' => 'select',
        'allow_null' => 1,
        'add_term' => 0,
        'save_terms' => 0,
        'load_terms' => 0,
        'return_format' => 'object',
        'multiple' => 0,
      ),
      array(
        'key' => 'field_59de0b32b3aa1',
        'label' => __('Categories', config('textdomain')),
        'name' => 'categorien',
        'type' => 'checkbox',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'choices' => array(
          'Health' => 'Health',
          'Health/Alternative Health' => 'Health / Alternative Health',
          'Health/Fitness &amp; Nutrition' => 'Health / Fitness & Nutrition',
          'Kids &amp; Family' => 'Kids & Family',
          'News &amp; Politics' => 'News & Politics',
          'Religion &amp; Spirituality' => 'Religion & Spirituality',
          'Religion &amp; Spirituality/Christianity' => 'Religion & Spirituality / Christianity',
          'Science &amp; Medicine' => 'Science & Medicine',
          'Science &amp; Medicine/Medicine' => 'Science & Medicine / Medicine',
          'Society &amp; Culture' => 'Society & Culture',
          'Society &amp; Culture/History' => 'Society & Culture / History',
        ),
        'allow_custom' => 0,
        'save_custom' => 0,
        'default_value' => array(
          0 => 'Religion & Spirituality',
          1 => 'Religion & Spirituality: Christianity',
        ),
        'layout' => 'vertical',
        'toggle' => 0,
        'return_format' => 'value',
      ),
      array(
        'key' => 'field_59e44c58b3706',
        'label' => __('iTunes Link', config('textdomain')),
        'name' => 'itunes_link',
        'type' => 'url',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'default_value' => '',
        'placeholder' => '',
      ),
      array(
        'key' => 'field_59e79635e0444',
        'label' => __('Stitcher Link', config('textdomain')),
        'name' => 'stitcher_link',
        'type' => 'url',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'default_value' => '',
        'placeholder' => '',
      ),
      array(
        'key' => 'field_59e3aaa4c51dc',
        'label' => __('Website', config('textdomain')),
        'name' => 'website_link',
        'type' => 'url',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'default_value' => '',
        'placeholder' => '',
      ),
    ),
    'location' => array(
      array(
        array(
          'param' => 'taxonomy',
          'operator' => '==',
          'value' => 'podcasts',
        ),
      ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'seamless',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => true,
    'description' => '',
  ));

  /*
   * Series Taxonomy ACF fields
   */
  acf_add_local_field_group( array(
    'key' => 'group_59ddf45ff376f',
    'title' => 'Taxonomy Series',
    'fields' => array(
      array(
        'key' => 'field_59ddf48a5960c',
        'label' => __('Thumbnail for series', config('textdomain')),
        'name' => 'image',
        'type' => 'image',
        'instructions' => 'Bild sollte mindestens 1280x720 Pixel haben.',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'return_format' => 'id',
        'preview_size' => '180p',
        'library' => 'all',
        'min_width' => 1280,
        'min_height' => 720,
        'min_size' => '',
        'max_width' => '',
        'max_height' => '',
        'max_size' => '',
        'mime_types' => '',
      ),
      array(
        'key' => 'field_59e5a5c30a4fd',
        'label' => __('Podcast', config('textdomain')),
        'name' => 'podcast',
        'type' => 'taxonomy',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'taxonomy' => 'podcasts',
        'field_type' => 'select',
        'allow_null' => 1,
        'add_term' => 0,
        'save_terms' => 0,
        'load_terms' => 0,
        'return_format' => 'id',
        'multiple' => 0,
      ),
    ),
    'location' => array(
      array(
        array(
          'param' => 'taxonomy',
          'operator' => '==',
          'value' => 'series',
        ),
      ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'seamless',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => true,
    'description' => '',
  ));

  /*
   * Speaker Taxonomy ACF fields
   */
  acf_add_local_field_group(array(
    'key' => 'group_59ddf5713cb1d',
    'title' => 'Taxonomy Sprecher',
    'fields' => array(
      array(
        'key' => 'field_59ddf586d5c6d',
        'label' => __('Image', config('textdomain')),
        'name' => 'image',
        'type' => 'image',
        'instructions' => 'Sollte <strong>quadratisch</strong> sein, wird ansonnsten automatisch ausgeschnitten. Mindestens <strong>300x300 Pixel</strong>.',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'return_format' => 'id',
        'preview_size' => 'medium',
        'library' => 'all',
        'min_width' => 300,
        'min_height' => 300,
        'min_size' => '',
        'max_width' => '',
        'max_height' => '',
        'max_size' => '',
        'mime_types' => '',
      ),
      array(
        'key' => 'field_59ddf623f1beb',
        'label' => __('Website', config('textdomain')),
        'name' => 'website',
        'type' => 'url',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'default_value' => '',
        'placeholder' => '',
      ),
    ),
    'location' => array(
      array(
        array(
          'param' => 'taxonomy',
          'operator' => '==',
          'value' => 'speakers',
        ),
      ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'seamless',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => true,
    'description' => '',
  ));

  /*
   * Recordings Sidebar ACF fields
   */
  acf_add_local_field_group(array(
    'key' => 'group_acf_choose-taxonomies',
    'title' => __('Choose taxonomies', config('textdomain')),
    'fields' => array(
      array(
        'key' => 'field_53dfb0355292e',
        'label' => __('Speakers', config('textdomain')),
        'name' => 'speakers',
        'type' => 'taxonomy',
        'instructions' => '',
        'required' => 1,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'taxonomy' => 'speakers',
        'field_type' => 'multi_select',
        'allow_null' => 0,
        'add_term' => 0,
        'save_terms' => 1,
        'load_terms' => 1,
        'return_format' => 'id',
        'multiple' => 0,
      ),
      array(
        'key' => 'field_53dfaf955292d',
        'label' => __('Series', config('textdomain')),
        'name' => 'series',
        'type' => 'taxonomy',
        'instructions' => '',
        'required' => 1,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'taxonomy' => 'series',
        'field_type' => 'select',
        'allow_null' => 0,
        'add_term' => 0,
        'save_terms' => 1,
        'load_terms' => 1,
        'return_format' => 'id',
        'multiple' => 0,
      ),
      array(
        'key' => 'field_59dcf1a261753',
        'label' => __('Topics', config('textdomain')),
        'name' => 'topics',
        'type' => 'taxonomy',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'taxonomy' => 'topics',
        'field_type' => 'checkbox',
        'allow_null' => 0,
        'add_term' => 1,
        'save_terms' => 1,
        'load_terms' => 1,
        'return_format' => 'id',
        'multiple' => 0,
      ),
      array(
        'key' => 'field_59dcf1e0346a5',
        'label' => __('Podcast', config('textdomain')),
        'name' => 'podcast',
        'type' => 'taxonomy',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'taxonomy' => 'podcasts',
        'field_type' => 'select',
        'allow_null' => 1,
        'add_term' => 0,
        'save_terms' => 1,
        'load_terms' => 1,
        'return_format' => 'id',
        'multiple' => 0,
      ),
      array(
        'key' => 'field_59dcf417389a4',
        'label' => __('Download', config('textdomain')),
        'name' => 'download',
        'type' => 'true_false',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'message' => '',
        'default_value' => 1,
        'ui' => 1,
        'ui_on_text' => __('permit', config('textdomain')),
        'ui_off_text' => __('deny', config('textdomain')),
      )
    ),
    'location' => array(
      array(
        array(
          'param' => 'post_type',
          'operator' => '==',
          'value' => 'recordings'
        ),
      ),
    ),
    'menu_order' => 0,
    'position' => 'side',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => true,
    'description' => '',
  ));

  /*
   * Bibel.Antworten Kategorie
   */
  // acf_add_local_field_group(array(
  //     'key' => 'group_acf_bibelantworten',
  //     'title' => 'Bibel.Antworten Kategorie',
  //     'fields' => array(
  //         array(
  //             'key' => 'field_54b66d16a979b',
  //             'label' => 'Bibel.Antworten Kategorie',
  //             'name' => 'bibel_antworten_kategorie',
  //             'type' => 'select',
  //             'instructions' => 'Diese Kategorien sind nur für Videos der Serie "Bibel.Antworten" von Bedeutung.',
  //             'required' => 0,
  //             'conditional_logic' => 0,
  //             'wrapper' => array(
  //                 'width' => '',
  //                 'class' => '',
  //                 'id' => '',
  //             ),
  //             'choices' => array(
  //                 0 => 'Keine Kategorie (wird nicht aufgelistet)',
  //                 1 => '1. Lesen, hören, bewahren – Fragen zur Bibel',
  //                 2 => '2. „Ich bin“ – Fragen zur Gottheit',
  //                 3 => '3. „gewaschen durch sein Blut“ – Fragen zur Erlösung',
  //                 4 => '4. „Könige und Priester“ – Fragen zum Leben als Christ',
  //                 5 => '5. „Siehe, er kommt“ – Fragen zur Wiederkunft',
  //                 6 => '6. „Tag des Herrn“ – Fragen zum Sabbat',
  //                 7 => '7. „sieben goldene Leuchter“ – Fragen zum Heiligtum',
  //                 8 => '8. „die Schlüssel des Totenreiches“ – Fragen zum Tod',
  //                 9 => '9. „was ist und was geschehen soll“ – Fragen zur Prophetie',
  //                 10 => '10. „sieben Gemeinden“ – Fragen zum Volk Gottes',
  //             ),
  //             'default_value' => array(
  //                 0 => 0,
  //             ),
  //             'allow_null' => 0,
  //             'multiple' => 0,
  //             'ui' => 1,
  //             'ajax' => 1,
  //             'return_format' => 'value',
  //             'placeholder' => '',
  //         ),
  //     ),
  //     'location' => array(
  //         array(
  //             array(
  //                 'param' => 'post_type',
  //                 'operator' => '==',
  //                 'value' => 'recordings',
  //             ),
  //         ),
  //     ),
  //     'menu_order' => 9,
  //     'position' => 'side',
  //     'style' => 'default',
  //     'label_placement' => 'top',
  //     'instruction_placement' => 'label',
  //     'hide_on_screen' => '',
  //     'active' => true,
  //     'description' => '',
  // ));

endif;
