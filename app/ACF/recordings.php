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

if( function_exists('acf_add_local_field_group') ):



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
<div id="vue-recording-status" postid="<?= $post->ID ?>">
    <div v-if="loading" class="u-text-center u-m">
        <div class="c-spinner"></div>
    </div>
    <div v-if="!loading" v-cloak>
        <div class="o-flex o-flex--unit o-flex--middle">
            <div class="o-flex__item"><span class="dashicons dashicons-video-alt3"></span> 1:05:14</div>
            <div class="o-flex__item"><span class="dashicons dashicons-admin-page"></span> {{items.length}} Dateien</div>
            <div class="o-flex__item"><span class="dashicons dashicons-category"></span> {{size}}</div>
            <div v-if="!done" class="o-flex__item u-yellow u-smaller">Next update in {{timeUntil}} seconds</div>
            <div v-if="error" class="o-flex__item u-red"><span class="dashicons dashicons-warning"></span> {{error}}</div>
            <div class="o-flex__spacer"></div>
            <div class="o-flex__item"><button class="button" @click.prevent="showDetails = !showDetails">+</button></div>
        </div>
        <div class="c-progress c-progress--green">
            <div class="c-progress__bar " role="progressbar" :style="{'width': progress}">{{progress}}</div>
        </div>
    </div>
    <div v-cloak class="status-details" v-show="showDetails">
        <table class="widefat fixed c-table">
            <thead>
                <tr>
                    <th scope="col" class="column" width="12"><label class="screen-reader-text" for="status_icon">Status</label></th>
                    <th scope="col" class="column" width="100%">File</th>
                    <th scope="col" class="column" width="80">Size</th>
                    <th scope="col" class="column" width="80">Stats</th>
                </tr>
            </thead>
            <tbody>
                <tr is="status-item"
                    v-for="(item, i) in items"
                    :item="item"
                    :key="item.ID"
                    :class="{'alternate': i % 2}"
                />
            </tbody>
        </table>
        <div class="u-muted u-text-center">
            Die Originaldatei wird für zwei Monate gespeichert, bevor sie gelöscht wird.
        </div>
    </div>
</div>
        <?php
        $field['message'] = str_replace(["\r","\n"],"",ob_get_clean());
        ob_flush();
        return $field;
    }



    /**
     * LEGACY
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
        $min_bitrate = 340;

        // read file directory
        $directory = array_filter(
            scandir(config('processing')['upload-dir']),
            function($file) {
                return(preg_match( '/\.(mov|mpg|mp4|flv|avi|mpeg4|mkv|mpeg|mpg2|mpeg2)$/', $file ));
            }
        );

        // array for select field and video information
        $videofiles = array();
        $video_information = array();

        foreach ( $directory as $key => $file ) {
            // fill select
            $videofiles[$file] = $file.' ['.formatbytes( filesize( config('processing')['upload-dir'].$file ) ).']';
            // prepare video info boxes
            $video = ffprobe_recording( config('processing')['upload-dir'].$file );

            if( $video->error ) {

                // remove ffmpeg build information
                for ($i=1; $i < 11; $i++) {
                    unset($video->error[$i]);
                }
                $video_information[] = sprintf('<div id="%1$s" data-value="%2$s" class="%3$s"><h4>%5$s %2$s</h4>%4$s</div>',
                     'format-info-'.$key,
                     $file,
                     'video-alert hidden alert-warning',
                     '<pre>'.implode($video->error, "\n").'</pre>',
                     '<span class="dashicons dashicons-info"></span>'
                );

            } else {

                switch($video->par) {
                    case '1:1': $parmsg = ''; break;
                    case '0:1': $parmsg = '-> Keine PAR-Info im video gespeichert'; break;
                    default:    $parmsg = '-> Video wird eventuell verzerrt dargestellt; Ideal: 1:1'; break;
                }
                switch ($video->dar) {
                    case '16:9': $parmsg = ''; break;
                    case '0:1': $parmsg = '-> Keine DAR-Info im video gespeichert'; break;
                    default:    $parmsg = '-> Video wird beschnitten; Ideal: 16:9'; break;
                }

                // Generate output string
                $output['resolution']   = sprintf( '<li%s>Aufl&ouml;sung: <strong>%sx%s</strong> %s</li>',
                                                $video->height < $min_height ? ' class="format-warning"' : '',
                                                $video->width,
                                                $video->height,
                                                $video->height < $min_height ? '-> Aufl&ouml;sung zu gering; Ideal: 1280x720' : ''
                                        );
                $output['bitrate']      = sprintf( '<li%s>Bitrate: <strong>%skb/s</strong> %s</li>',
                                                $video->bitrate < $min_bitrate ? ' class="format-warning"' : '',
                                                $video->bitrate,
                                                $video->bitrate < $min_bitrate ? '-> F&uuml;r eine optimale Qualit&aumlt empfiehlt sich eine Bitrate von 5000kb/s)' : ''
                                        );
                $output['PAR']          = sprintf( '<li%s>Pixel Aspect Ratio (PAR): <strong>%s</strong> %s</li>',
                                                $parmsg == '' ? '' : ' class="format-warning"',
                                                $video->par,
                                                $parmsg
                                        );
                $output['DAR']          = sprintf( '<li%s>Display Aspect Ratio (DAR): <strong>%s</strong> %s</li>',
                                                $parmsg == '' ? '' : ' class="format-warning"',
                                                $video->dar,
                                                $parmsg
                                        );
                $unfit = $video->height < 480 || $video->par !== '1:1' || $video->dar !== '16:9' ? true : false;

                // Add video format info boxes
                $video_information[] = sprintf('<div id="%1$s" data-value="%2$s" class="%3$s"><h4>%5$s %2$s</h4>%4$s</div>',
                     'format-info-'.$key,
                     $file,
                     'video-alert hidden '.($unfit ? 'alert-warning' : 'alert-success'),
                     '<ul>'.$output['resolution'].$output['bitrate'].$output['PAR'].$output['DAR'].'</ul>',
                     $unfit ? '<span class="dashicons dashicons-info"></span>' : '<span class="dashicons dashicons-yes"></span>'
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
        $field['instructions'] .= '<p class="u-smaller u-muted">Die ausgewählte Datei wird vom Server verarbeitet und die existierende Datei (falls vorhanden) wird überschrieben.</p>';
        $field['instructions'] .= $alert.$js.implode($video_information);

        // Important: return the field
        return $field;
    }



    /*
     * Recording ACF fields
     */
    acf_add_local_field_group(array(
        'key' => 'group_acf_recording',
        'title' => __('Recordings', config('textdomain')),
        'fields' => array(
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
        'hide_on_screen' => '',
        'active' => true,
        'hide_on_screen' => array(),
        'description' => '',
        'hide_on_screen' => array(
            0 => 'excerpt',
            1 => 'custom_fields',
            2 => 'discussion',
            3 => 'comments',
            4 => 'slug',
            5 => 'author',
            6 => 'format',
            7 => 'featured_image',
            8 => 'categories',
            9 => 'tags',
            10 => 'send-trackbacks',
        ),
    ));

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
                    'value' => 'recordings',
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
    acf_add_local_field_group(array(
        'key' => 'group_acf_bibelantworten',
        'title' => 'Bibel.Antworten Kategorie',
        'fields' => array(
            array(
                'key' => 'field_54b66d16a979b',
                'label' => 'Bibel.Antworten Kategorie',
                'name' => 'bibel_antworten_kategorie',
                'type' => 'select',
                'instructions' => 'Diese Kategorien sind nur für Videos der Serie "Bibel.Antworten" von Bedeutung.',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'choices' => array(
                    0 => 'Keine Kategorie (wird nicht aufgelistet)',
                    1 => '1. Lesen, hören, bewahren – Fragen zur Bibel',
                    2 => '2. „Ich bin“ – Fragen zur Gottheit',
                    3 => '3. „gewaschen durch sein Blut“ – Fragen zur Erlösung',
                    4 => '4. „Könige und Priester“ – Fragen zum Leben als Christ',
                    5 => '5. „Siehe, er kommt“ – Fragen zur Wiederkunft',
                    6 => '6. „Tag des Herrn“ – Fragen zum Sabbat',
                    7 => '7. „sieben goldene Leuchter“ – Fragen zum Heiligtum',
                    8 => '8. „die Schlüssel des Totenreiches“ – Fragen zum Tod',
                    9 => '9. „was ist und was geschehen soll“ – Fragen zur Prophetie',
                    10 => '10. „sieben Gemeinden“ – Fragen zum Volk Gottes',
                ),
                'default_value' => array(
                    0 => 0,
                ),
                'allow_null' => 0,
                'multiple' => 0,
                'ui' => 1,
                'ajax' => 1,
                'return_format' => 'value',
                'placeholder' => '',
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
        'menu_order' => 9,
        'position' => 'side',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ));

endif;
