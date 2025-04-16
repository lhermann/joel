<?php

use function Tonik\Theme\App\config;
use function Tonik\Theme\App\Helper\formatbytes;

/**
 * Admin Page for website & video monitoring and controlls
 *
 * @since Joel Media 1.2.1
 */

class VideoControllPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page() {
        add_submenu_page(
            'tools.php',                        //string $parent_slug,
            'Video Controll Room',          //string $page_title,
            'Video Controll Room',          //string $menu_title,
            'manage_options',               //string $capability,
            'video-controlls',              //string $menu_slug,
            [$this, 'create_admin_page']    //callable $function = ''
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page() {
        ?>
        <div class="wrap jm-tools">
            <h2>Programmers Only Access Center</h2>
            <section>
                <div id="poststuff">
                    <div id="post-body" class="metabox-holder columns-1">

                        <!-- main content -->
                        <div id="post-body-content">

                            <div class="meta-box-sortables ui-sortable">
                                <div class="postbox">
                                    <h3 class="hndle" style="cursor: default;">Themencheck</h3>
                                    <div id="ajax-video-themencheck" class="inside">
                                        <div class="c-spinner u-m"></div>
                                    </div><!-- .inside -->
                                </div><!-- .postbox -->
                            </div><!-- .meta-box-sortables .ui-sortable -->

                            <div class="meta-box-sortables ui-sortable">
                                <div class="postbox">
                                    <h3 class="hndle" style="cursor: default;">Popular Post Data Cleanup</h3>
                                    <div id="ajax-video-popularpost_cleanup" class="inside">
                                        <div class="c-spinner u-m"></div>
                                    </div><!-- .inside -->
                                </div><!-- .postbox -->
                            </div><!-- .meta-box-sortables .ui-sortable -->

                            <div class="meta-box-sortables ui-sortable">
                                <div class="postbox">
                                    <h3 class="hndle" style="cursor: default;">YouTube API</h3>
                                    <div class="inside">
                                        <?= print_youtube_api_status() ?>
                                    </div><!-- .inside -->
                                </div><!-- .postbox -->
                            </div><!-- .meta-box-sortables .ui-sortable -->

                            <div class="meta-box-sortables ui-sortable">
                                <div class="postbox">
                                    <h3 class="hndle" style="cursor: default;">Logs</h3>
                                    <div id="ajax-video-logs" class="inside">
                                        <div class="c-spinner u-m"></div>
                                    </div><!-- .inside -->
                                </div><!-- .postbox -->
                            </div><!-- .meta-box-sortables .ui-sortable -->

                            <div class="meta-box-sortables ui-sortable">
                                <div class="postbox">
                                    <h3 class="hndle" style="cursor: default;">Video Files</h3>
                                    <div id="ajax-video-files" class="inside">
                                        <div class="c-spinner u-m"></div>
                                    </div><!-- .inside -->
                                </div><!-- .postbox -->
                            </div><!-- .meta-box-sortables .ui-sortable -->

                        </div><!-- post-body-content -->

                    </div><!-- #post-body -->
                </div><!-- #poststuff -->
                <script>
                    (function($) {
                        /* Standard function to load in content */
                        function video_ajax_update( _element_ ) {
                            $.post( ajaxurl, { action: 'video_' + _element_ }, function(response) {
                                $('#ajax-video-' + _element_ ).html(response);
                            });
                        }

                        /* - Themencheck - */
                        video_ajax_update( 'themencheck' );
                        /* When the button is clicked */
                        $('#ajax-video-themencheck').on('click', '#video_themenfix_action', function() {
                                var _button_ = $('#video_themenfix_action');
                                _button_.prop('disabled', true);
                                _button_.find('.jm-spinner').removeClass('spinner-hidden');
                                $.post( ajaxurl, { action: 'video_themenfix' } ).done(function() {
                                    video_ajax_update( 'themencheck' );
                                });
                        });

                        /* - Popularpost Cleanup - */
                        video_ajax_update( 'popularpost_cleanup' );
                        /* When the button is clicked */
                        $('#ajax-video-popularpost_cleanup').on('click', '#video_popularpost_cleanup_action', function() {
                                var _button_ = $('#video_popularpost_cleanup_action');
                                _button_.prop('disabled', true);
                                _button_.find('.jm-spinner').removeClass('spinner-hidden');
                                $.post( ajaxurl, { action: 'video_popularpost_cleanup_action' } ).done(function() {
                                    video_ajax_update( 'popularpost_cleanup' );
                                });
                        });

                        /* - Logs - */
                        video_ajax_update( 'logs' );
                        /* When the button is clicked */
                        $('#ajax-video-logs').on('click', '#video_logs_action', function() {
                                var _button_ = $('#video_logs_action');
                                _button_.prop('disabled', true);
                                _button_.find('.jm-spinner').removeClass('spinner-hidden');
                                $.post( ajaxurl, { action: 'video_logs', limit: $('#video_logs_input').val() } ).done(function(response) {
                                    $('#ajax-video-logs' ).html(response);
                                });
                        });

                        /* - Video Files - */
                        video_ajax_update( 'files' );
                        /* When the button is clicked */
                        $('#ajax-video-files').on('click', '#video_files_action', function() {
                                var _button_ = $('#video_files_action');
                                _button_.prop('disabled', true);
                                _button_.find('.jm-spinner').removeClass('spinner-hidden');
                                $.post( ajaxurl, { action: 'video_files', status: $('#video_files_input').val() } ).done(function(response) {
                                    $('#ajax-video-files' ).html(response);
                                });
                        });

                    })( jQuery );
                </script>
            </section>
            <hr>
            <div class="clear"></div>
            <hr>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init() {
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input ) {
    }
}

if( is_admin() )
    $admin_page_video = new VideoControllPage();


/**
 * AJAX Helper Functions
 */

function helper_get_root_dir() {
    return realpath(__DIR__.'/../../');
}

/**
 * AJAX Callbacks
 */
/**
 * - Themencheck -
 * This one generates the html output
 */
add_action('wp_ajax_video_themencheck', 'ajax_video_themencheck');
function ajax_video_themencheck() {

    print( '<p>Hier sind die Themen augelistet die noch aktiviert werden m&uuml;ssen um die Hirachie nicht zu brechen</p>' );
    print( '<div id="video-success" class="notice notice-info" style="column-count: 2; -webkit-column-count: 2;">' );
    /* Display all the missing Topics */
    $themen = get_terms( 'video_themen' );
    $themen_ids = array(); // this one will contain all the topicts who are parents (or grandparents) at the same time
    $objects_diff = array(); // this one will contain all the missing children
    $print = '';
    // find children without parents
    foreach ($themen as $thema) {
            if ($thema->parent != 0) {
                    $objects1 = get_objects_in_term( $thema->term_id, 'video_themen' );
                    $objects2 = get_objects_in_term( $thema->parent, 'video_themen' );
                    if ( isset($objects_diff[$thema->parent]) ) {
                            $objects_diff[$thema->parent] = array_merge($objects_diff[$thema->parent], array_diff($objects1, array_intersect($objects1, $objects2)));
                    } else {
                            $objects_diff[$thema->parent] = array_diff($objects1, array_intersect($objects1, $objects2));
                    };
                    $themen_ids[] = $thema->parent;
            };
    };
    $results = array_unique($themen_ids);
    sort($results);
    // print the orphans
    foreach ($results as $result) {
        if ( !empty($objects_diff[$result]) ){
            $print .= '<p>';
            foreach ($themen as $thema) {
                    if ($thema->term_id == $result) {
                            $print .= '<em>Fehlende Videos in</em> <strong>'.$thema->name.':</strong><br>';
                    };
            };
            foreach ($objects_diff[$result] as $object) {
                    $echo = get_post($object);
                    $print .= 'ID: '.$object.' | '.$echo->post_title."</br>";
            };
            $print .= '</p>';
        };
    };
    if( empty( $print ) ) $print = 'There are no Orphans';
    print( $print );
    print( '</div>' );
    print( '<button type="button" name="themenfix" id="video_themenfix_action" class="button button-primary">F&uuml;ge alle fehlenden Themen hinzu <span class="jm-spinner spinner-inline spinner-hidden spinner-tiny"><span><span></span></span></span></button>' );

    die(); // this is required to return a proper result
}

/**
 * - Themencheck -
 * Button Interaction
 * Fuege die Themen, die benoetigt werden, um die Hirachie nicht zu brechen, automatisch hinzu.
 * @since Joel Media 1.0
 * @update Joel Media 1.2.1
 */
add_action('wp_ajax_video_themenfix', 'ajax_video_themenfix');
function ajax_video_themenfix() {

    $themen = get_terms( 'video_themen' );
    $themen_ids = array();
    $objects_diff = array();
    foreach ($themen as $thema) {
        if ($thema->parent != 0) {
            $objects1 = get_objects_in_term( $thema->term_id, 'video_themen' );
            $objects2 = get_objects_in_term( $thema->parent, 'video_themen' );
            if ( isset($objects_diff[$thema->parent]) ) {
                $objects_diff[$thema->parent] = array_merge($objects_diff[$thema->parent], array_diff($objects1, array_intersect($objects1, $objects2)));
            } else {
                $objects_diff[$thema->parent] = array_diff($objects1, array_intersect($objects1, $objects2));
            };
            $themen_ids[] = $thema->parent;
        };
    };
    $results = array_unique($themen_ids);
    sort($results);
    foreach ($results as $result) {
        if ( !empty($objects_diff[$result]) ){
            foreach ($themen as $thema) {
                if ($thema->term_id == $result) {
                    echo '<br><span style="font-weight:bold;"><i>Aktualisiert in</i> '.$thema->name.':</span><br>';
                    foreach ($objects_diff[$result] as $object_id) {
                        wp_set_object_terms( $object_id, $thema->slug, 'video_themen', true );
                        $echo = get_post($object_id);
                        echo 'ID: '.$object_id.' | '.$echo->post_title."</br>";
                    };
                };
            };
        };
    };
    die(); // die() to prevent a appended '0'
};


/**
 * - Popular Post Cleanup -
 * This one generates the html output
 */
add_action('wp_ajax_video_popularpost_cleanup', 'ajax_video_popularpost_cleanup');
function ajax_video_popularpost_cleanup() {

    // Get info
    global $wpdb;
    $rows_total = $wpdb->get_results( "SELECT COUNT(1) FROM wp_popularpostssummary", ARRAY_N );
    $rows_affected = $wpdb->get_results( "SELECT COUNT(1) FROM wp_popularpostssummary WHERE view_date < NOW() - INTERVAL 20 DAY;", ARRAY_N );

    // print output
    printf('<p>Rows total: <code>%s</code> / Rows to remove: <code>%s</code></p>', $rows_total[0][0], $rows_affected[0][0]);

    // print button
    print( '<button type="button" data-on="1" name="crontab-on" id="video_popularpost_cleanup_action" class="button button-primary">Cleanup Rows <span class="jm-spinner spinner-inline spinner-hidden spinner-tiny"><span><span></span></span></span></button>' );

    die(); // this is required to return a proper result
}

/**
 * - Popular Post Cleanup -
 * Button Interaction
 * Cleanup DB
 */
add_action('wp_ajax_video_popularpost_cleanup_action', 'ajax_video_popularpost_cleanup_action');
function ajax_video_popularpost_cleanup_action() {

    global $wpdb;
    $wpdb->get_results( "DELETE FROM wp_popularpostssummary WHERE view_date < NOW() - INTERVAL 20 DAY;" );

    die(); // die() to prevent a appended '0'
};


/**
 * - Logs -
 * This one generates the html output
 */
add_action('wp_ajax_video_logs', 'ajax_video_logs');
function ajax_video_logs() {

    $logfile = config('processing')['log-dir'] . 'main.log';
    $log = file( $logfile );

    // print last x lines
    $limit = ( isset($_POST['limit']) ? $_POST['limit'] : 20 );
    $count = count( $log );

    // print output
    print( "<p>Log File: <code>$logfile</code></p>" );
    print( '<p>Zeige die letzten <input type="text" id="video_logs_input" class="all-options" value="'.$limit.'" size="5" style="width: auto;" /> linien. <button type="button" name="reload" id="video_logs_action" class="button button-primary" value="Update">Aktualisieren</button></p>' );
    print( '<div class="notice notice-info" style="overflow-y: scroll;"><pre>' );
    foreach( $log as $i => $line ) {
        if( $i <= $count-$limit ) continue;
        print( "<b>[$i]</b> $line" );
    }
    print( '</pre></div>' );


    die(); // this is required to return a proper result
}


/**
 * - Video Files -
 * This one generates the html output
 */
add_action('wp_ajax_video_files', 'ajax_video_files');
function ajax_video_files() {
    global $wpdb;

    $status_string = ( isset($_POST['status']) ? $_POST['status'] : '61, 60, 99' );
    $status_stringx = str_replace( ' ', '', $status_string );
    $status_array = explode( ',', $status_stringx );
    $where = '';
    foreach( $status_array as $i => $status ){
        $where .= ($i == 0 ? '' : ' OR ' ).'status = '.$status;
    }

    $video_files = $wpdb->get_results( "SELECT * FROM wp_video_files WHERE $where ORDER BY post_id DESC" );
    // var_dump($vixdeo_files); die();

    // print output
    print( '<p>Video Status: <input type="text" id="video_files_input" class="all-options" value="'.$status_string.'" size="20" style="width: auto;" /> linien. <button type="button" name="reload" id="video_files_action" class="button button-primary" value="Update">Aktualisieren</button> <small>(comma seperated list)</p>' );
    print( '<table class="jm-video-list widefat fixed">
            <thead>
                <tr>
                    <th scope="col" class="column" width="45">ID</th>
                    <th scope="col" class="column" width="40">post</th>
                    <th scope="col" class="column" width="60">location</th>
                    <th scope="col" class="column" width="40">status</th>
                    <th scope="col" class="column" width="100%">relative_url</th>
                    <th scope="col" class="column" width="50">type</th>
                    <th scope="col" class="column" width="70">format</th>
                    <th scope="col" class="column" width="60">size</th>
                    <th scope="col" class="column" width="160">timestamps</th>
                </tr>
            </thead>
            <tbody>' );

        foreach( $video_files as $i => $row ) {
            $class = 'row-status-yellow';
            if( 60 <= $row->status && $row->status < 70 ) $class = 'row-status-red';
            $size = formatbytes( $row->size );
            print(
                "<tr class=\"".$class.( $i & 1 ? ' alternate' : '' )."\">
                    <td class=\"column\">$row->ID</td>
                    <td class=\"column\">$row->post_id</td>
                    <td class=\"column\">$row->location</td>
                    <td class=\"column\">$row->status</td>
                    <td class=\"column\">$row->relative_url</td>
                    <td class=\"column\">$row->type</td>
                    <td class=\"column\">$row->bitrate kb/s<br>$row->resolution</td>
                    <td class=\"column\">$size<br>$row->length</td>
                    <td class=\"column\">c: $row->created</br>m: $row->modified</td>
                </tr>"
            );
        }

    print( '</tbody></table>' );


    die(); // this is required to return a proper result
}

use Tonik\Theme\App\Helper\Google_API;

function print_youtube_api_status () {
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
    printf(
      ' <span class="%s">%s</span>',
      $api_class,
      $api_text
    );
}
