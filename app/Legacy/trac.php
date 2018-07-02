<?php

namespace Tonik\Theme\App\Legacy;

/*
|-----------------------------------------------------------
| Trac Legacy Functions
|-----------------------------------------------------------
*/

/*
 * Trac link examples:
 * https://www.joelmediatv.de/trac/6977/podcastdl/vodhttp.joelmediatv.de/christopher-kramp/der-ersehnte/6977-kramp-de-92-das-tut-auch-ihr-ihnen-128.mp3/
 * https://www.joelmediatv.de/trac/6977/videodl/dl.joelmediatv.de/christopher-kramp/der-ersehnte/6977-kramp-de-92-das-tut-auch-ihr-ihnen-720p.mp4/
 * /trac/5284/videodl/dl.joelmediatv.de/john-bradshaw/josua-camp-2016/5284-bradshaw-josua-11-bitter-im-bauch-720p.mp4/
 */


/**
 * Tracking outbound links
 */
use Jaybizzle\CrawlerDetect\CrawlerDetect;

/**
 * Add a query variables: trac-label, redirect_url
 */
add_action('init', 'Tonik\Theme\App\Legacy\trac_query_variables', 10, 0);
function trac_query_variables() {
    add_rewrite_tag('%trac-label%', '([^&]+)');
    add_rewrite_tag('%redirect_url%', '([^&]+)');
    add_rewrite_tag('%template%', '([^&]+)');
    // flush_rewrite_rules();
}


/**
 * Custom URL Routing
 * Tutorial: http://www.hongkiat.com/blog/wordpress-url-rewrite/
 *
 * NOTE: Standard WP rewrite rules are turned off for each custom post type.
 *       Thus these restful routes here are the only way to reach them
 */
add_action( 'generate_rewrite_rules', 'Tonik\Theme\App\Legacy\trac_rewrite_rules' );
function trac_rewrite_rules() {
    global $wp_rewrite;

    // define new rules
    $new_rules = array(
        "trac/([0-9]{1,})/(.+?)/(.+?)/?$"

                    => "index.php"
                            ."?p=".$wp_rewrite->preg_index(1)
                            ."&trac-label=".$wp_rewrite->preg_index(2)
                            ."&redirect_url=".$wp_rewrite->preg_index(3)
                            ."&template=trac",

    );

    // Add new rules to existing rules
    $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
}


/**
 * Catch trac before anything is rendered
 * do the database thing
 * and then redirect
 */
add_action( 'wp', 'Tonik\Theme\App\Legacy\instantiate_the_controller', 10, 1 );
function instantiate_the_controller( $wp ) {

    // Bail for admin area & if template is another
    if( is_admin() || get_query_var('template') != 'trac' ) return;

    // Detect Crawler
    $CrawlerDetect = new CrawlerDetect;
    $crawler = $CrawlerDetect->isCrawler();

    // Write log file
    $log = sprintf( "[%s] Crawler: %s | Post: %s | Label: %s | Agent: %s)\n",
        current_time('mysql'),
        $crawler ? 'true' : 'false',
        get_query_var('p'),
        get_query_var('trac-label'),
        $_SERVER['HTTP_USER_AGENT']
    );
    file_put_contents('trac.log', $log, FILE_APPEND);

    if( !$crawler ) {
        // Update the database
        update_trac_database(get_query_var('p'), get_query_var('trac-label'));
    }

    // don't redirect on development environment but show a debug message
    if( ENVIRONMENT == 'development' ) {
        print("<h1>Trac Debug Message</h1>");
        printf('<p><strong>HTTP Referer:</strong> <a href="%1$s">%1$s</a></p>',
            isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : "#"
        );
        printf("<p><strong>Redirect Url:</strong> <code>https://%s</code></p>",
            get_query_var('redirect_url')
        );
        print("<p><strong>Log:</strong> <code>$log</code></p>");
        die();
    }

    // Redirect to specified url
    header("HTTP/1.1 302 Found");
    header('Location: https://' . get_query_var('redirect_url'));
    exit();
}


function update_trac_database( $post_id, $label, $term_id = 0 ) {
    global $wpdb;

    // trac by post
    $row = $wpdb->get_row( $wpdb->prepare(
        "SELECT * FROM trac_by_post WHERE post_id = '%d' AND label = '%s'",
        $post_id,
        $label
    ) );

    if($row) {
        $wpdb->update(
            'trac_by_post',
            array(
                'count' => $row->count + 1,
                'last_modified' => current_time('mysql')
            ),
            array( 'id' => $row->id ),
            array(
                '%d',
                '%s'
            ),
            array( '%d' )
        );
    } else {
        $wpdb->insert(
            'trac_by_post',
            array(
                'post_id' => $post_id,
                'label' => $label,
                'count' => 1,
                'last_modified' => current_time('mysql')
            ),
            array(
                '%d',
                '%s',
                '%d',
                '%s'
            )
        );
    }

    // trac by date
    if( $label == 'podcastdl' ) {
        $term_id = wp_get_post_terms($post_id, 'podcasts', array('fields' => 'ids'));
        $term_id = $term_id ? $term_id[0] : 0;
    }
    $row = $wpdb->get_row( $wpdb->prepare(
        "SELECT * FROM trac_by_date WHERE date = %s AND label = %s AND term_id = %d",
        date("Y-m-d"),
        $label,
        $term_id
    ) );

    if($row) {
        $wpdb->update(
            'trac_by_date',
            array(
                'count' => $row->count + 1,
                'last_modified' => current_time('mysql')
            ),
            array( 'id' => $row->id ),
            array(
                '%d',
                '%s'
            ),
            array( '%d' )
        );
    } else {
        $wpdb->insert(
            'trac_by_date',
            array(
                'date' => date("Y-m-d", current_time('timestamp')),
                'label' => $label,
                'term_id' => $term_id,
                'count' => 1,
                'last_modified' => current_time('mysql')
            ),
            array(
                '%s',
                '%s',
                '%d',
                '%d',
                '%s'
            )
        );
    }

}


/**
 * Add a dashboard widget
 */
function trac_dashboard_widget() {

    wp_add_dashboard_widget(
        'dashboard_trac',                // Widget slug.
        'Download Statistics',           // Title.
        'Tonik\Theme\App\Legacy\trac_dashboard_widget_function' // Callback function.
    );
}
add_action( 'wp_dashboard_setup', 'Tonik\Theme\App\Legacy\trac_dashboard_widget' );

function trac_dashboard_widget_function() {
    global $wpdb;

    /*
     * Graph data
     */

    $values = array();
    foreach (array('videodl', 'audiodl', 'podcastdl') as $value) {
        $rows = $wpdb->get_results(
            sprintf(
                "SELECT date, sum(count) as count FROM trac_by_date WHERE date BETWEEN '%s' AND '%s' AND label = '%s' GROUP BY date",
                date("Y-m-d", strtotime("-10 days")),
                date("Y-m-d", strtotime("-1 days")),
                $value
            ),
            ARRAY_A
        );
        foreach ($rows as $row) {
            $values[$value][$row['date']] = (int) $row['count'];
        }
    }

    $chart_dl = [
        'labels' => [],
        'video' => [],
        'audio' => [],
        'podcast' => []
    ];
    for ($i=10; $i > 0 ; $i--) {
        $date = date("Y-m-d", strtotime("-$i days"));
        $chart_dl['labels'][] = '"'.date("j.m.", strtotime("-$i days")).'"';
        $chart_dl['video'][] = isset($values['videodl'][$date]) ? $values['videodl'][$date] : 0;
        $chart_dl['audio'][] = isset($values['audiodl'][$date]) ? $values['audiodl'][$date] : 0;
        $chart_dl['podcast'][] = isset($values['podcastdl'][$date]) ? $values['podcastdl'][$date] : 0;
    }


    /*
     * Total downloads
     */

    $values = array();
    foreach (array('videodl', 'audiodl', 'podcastdl') as $value) {
        $values[$value] = $wpdb->get_var(
            sprintf(
                "SELECT sum(count) FROM trac_by_date WHERE label = '%s'",
                $value
            )
        ) ?: '0';
    }

    $total_dl = [
        'video' => $values['videodl'],
        'audio' => $values['audiodl'],
        'podcast' => $values['podcastdl']
    ];


    /*
     * Podcast subscribers
     */
    $podcast_ids = get_terms( array(
        'taxonomy' => 'podcasts',
        'hide_empty' => true,
        'fields' => 'ids'
    ) );
    $pod_subs = array('current' => 0, 'diff' => 0);
    foreach ($podcast_ids as $id) {
        $temp = trac_get_podcast_subscribers($id);
        $pod_subs['current'] += $temp['current'];
        $pod_subs['diff'] += $temp['diff'];
    }

?>
<div class="activity-block">
    <h3><strong>
        Downloads (last 10 days)
        <span style="color: #d70206;">Video</span> /
        <span style="color: #f4c63d;">Audio</span> /
        <span style="color: #d17905;">Podcast</span>
    </strong></h3>
    <div class="trac-download-chart"></div>
    <script>
        var data = {
          // A labels array that can contain any sort of values
          labels: [<?= implode(',', $chart_dl['labels']) ?>],
          // Our series array that contains series objects or in this case series data arrays
          series: [
            [<?= implode(',', $chart_dl['video']) ?>],
            [],
            [<?= implode(',', $chart_dl['audio']) ?>],
            [<?= implode(',', $chart_dl['podcast']) ?>]
          ]
        };
        new Chartist.Line('.trac-download-chart', data);
    </script>
</div>
<div class="activity-block">
    <h3><strong>Video &amp; Audio Downloads (total)</strong></h3>
    <ul style="margin-left: 2em;">
        <li><span class="dashicons dashicons-format-video"></span> <?= $total_dl['video'] ?> Video Downloads</li>
        <li><span class="dashicons dashicons-format-audio"></span> <?= $total_dl['audio'] ?> Audio Downloads</li>
    </ul>
</div>
<div class="activity-block">
    <h3><strong>Podcasts</strong></h3>
    <ul style="margin-left: 2em;">
        <li><span class="dashicons dashicons-format-audio"></span> <?= $total_dl['podcast'] ?> Total Downloads</li>
        <li>
            <span class="dashicons dashicons-rss"></span> <?= $pod_subs['current'] ?> Total Subscribers
            <span style="color: <?= $pod_subs['diff'] >= 0 ? 'green' : 'red' ?>;">(<?= $pod_subs['diff'] >= 0 ? 'increased' : 'decreased' ?> by <?= abs($pod_subs['diff']) ?>)</span>
        </li>
    </ul>
</div>
<?php
}


/**
 * Helper Function
 */
function trac_get_podcast_subscribers($podcast_id) {
    global $wpdb;
    $limit = 3;

    // get podcast download of last 6 episodes
    $query = sprintf("
        SELECT wp_posts.ID, trac_by_post.count
        FROM wp_term_taxonomy
        LEFT JOIN wp_term_relationships ON wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_taxonomy_id
        LEFT JOIN wp_posts ON wp_posts.ID = wp_term_relationships.object_id
        LEFT JOIN trac_by_post ON trac_by_post.post_id = wp_posts.ID AND trac_by_post.label = 'podcastdl'
        WHERE wp_term_taxonomy.term_id = %d
        ORDER BY wp_posts.post_date DESC
        LIMIT %d;",
        $podcast_id,
        $limit * 2
    );
    $rows = $wpdb->get_results($query);

    // sum up 3 and 3
    $return = array('current' => 0, 'past' => 0, 'diff' => 0);
    foreach ($rows as $i => $row) {
        if($i < $limit) {
            $return['current'] += $row->count;
        } else {
            $return['past'] += $row->count;
        }
    }

    // calculate and round average
    $return['current'] = (int) ceil($return['current']/$limit);
    $return['past'] = (int) ceil($return['past']/$limit);
    $return['diff'] = $return['current'] - $return['past'];

    return $return;
}

function trac_permalink($post_id, $label, $url) {
    // remove http:// or https://
    if(stripos($url, '://')) {
        $url = substr($url, stripos($url, '://') + 3);
    }
    // return trac permalink
    return sprintf(
        "%s/trac/%d/%s/%s/",
        get_bloginfo('url'),
        $post_id,
        $label,
        $url
    );
}





