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
add_action( 'wp', 'Tonik\Theme\App\Legacy\trac_controller', 10, 1 );
function trac_controller( $wp ) {

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
        update_trac_database(get_query_var('trac-label'), get_query_var('p'));
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


function trac_by_object($label, $object_id, $object_type) {
    global $wpdb;

    $row = $wpdb->get_row( $wpdb->prepare(
        "SELECT * FROM `trac_by_object`
            WHERE `object_id` = %d
            AND `object_type` = %s
            AND `label` = %s
        ;",
        $object_id,
        $object_type,
        $label
    ) );

    if($row) {
        $wpdb->update(
            'trac_by_object',
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
        $temp = $wpdb->insert(
            'trac_by_object',
            array(
                'object_id' => $object_id,
                'object_type' => $object_type,
                'label' => $label,
                'count' => 1,
                'last_modified' => current_time('mysql')
            ),
            array(
                '%d',
                '%s',
                '%s',
                '%d',
                '%s'
            )
        );
    }
}


function trac_by_date($label) {
    global $wpdb;

    $row = $wpdb->get_row( $wpdb->prepare(
        "SELECT * FROM `trac_by_date` WHERE `date` = %s AND `label` = %s;",
        date("Y-m-d"),
        $label
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
                'count' => 1,
                'last_modified' => current_time('mysql')
            ),
            array(
                '%s',
                '%s',
                '%d',
                '%s'
            )
        );
    }
}


function trac_90days($label, $object_id, $object_type) {
    global $wpdb;

    $row = $wpdb->get_row( $wpdb->prepare(
        "SELECT * FROM `trac_90days`
            WHERE `date` = %s
            AND `label` = %s
            AND `object_id` = %d
            AND `object_type` = %s
        ;",
        date("Y-m-d"),
        $label,
        $object_id,
        $object_type
    ) );

    if($row) {
        $wpdb->update(
            'trac_90days',
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
        $temp = $wpdb->insert(
            'trac_90days',
            array(
                'date' => date("Y-m-d", current_time('timestamp')),
                'object_id' => $object_id,
                'object_type' => $object_type,
                'label' => $label,
                'count' => 1,
                'last_modified' => current_time('mysql')
            ),
            array(
                '%s',
                '%d',
                '%s',
                '%s',
                '%d',
                '%s'
            )
        );
    }

    if(rand(0,5000) === 0 ) {
        $wpdb->query( $wpdb->prepare(
            "DELETE FROM `trac_90days` WHERE `date` < %s;",
            date("Y-m-d", strtotime("-90 days"))
        ) );
    }
}


function update_trac_database( $label, $object_id, $object_type = 'post' ) {
    global $wpdb;

    // trac by object
    trac_by_object($label, $object_id, $object_type);

    // trac by date
    trac_by_date($label);

    // trac by 90days
    trac_90days($label, $object_id, $object_type);


    // exception: for podcasts find the term and trac it also
    if( $label == 'podcastdl' && $object_type == 'post' ) {
        $term_id = wp_get_post_terms($object_id, 'podcasts', array('fields' => 'ids'));
        $term_id = $term_id ? $term_id[0] : 0;
        trac_by_object($label, $term_id, 'term');
        trac_90days($label, $term_id, 'term');
    }
}



add_action('init', 'Tonik\Theme\App\Legacy\migrate_trac_database', 10, 0);
function migrate_trac_database() {
    global $wpdb;

    if(!$wpdb->query("SHOW TABLES LIKE 'trac_90days';")) {

        // trac_90days
        $wpdb->query("CREATE TABLE `trac_90days` (
          `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
          `date` date DEFAULT NULL,
          `object_id` bigint(20) unsigned DEFAULT NULL,
          `object_type` varchar(8) NOT NULL DEFAULT '',
          `label` varchar(20) NOT NULL DEFAULT '',
          `count` bigint(20) unsigned NOT NULL DEFAULT '0',
          `last_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
          PRIMARY KEY (`id`),
          KEY `object_id` (`object_id`,`object_type`,`label`)
        ) ENGINE=InnoDB AUTO_INCREMENT=4455 DEFAULT CHARSET=utf8;");

        // trac_by_object
        $wpdb->query("ALTER TABLE `trac_by_post` CHANGE COLUMN `post_id` `object_id` bigint(20);");
        $wpdb->query("ALTER TABLE `trac_by_post` MODIFY COLUMN `object_id` bigint(20) unsigned DEFAULT NULL;");
        $wpdb->query("ALTER TABLE `trac_by_post` ADD COLUMN `object_type` varchar(8) NOT NULL DEFAULT '' AFTER `object_id`;");
        $wpdb->query("ALTER TABLE `trac_by_post` DROP INDEX `label`;");
        $wpdb->query("ALTER TABLE `trac_by_post` DROP INDEX `postid`;");
        $wpdb->query("ALTER TABLE `trac_by_post` ADD KEY `object_id` (`object_id`,`object_type`,`label`);");
        $wpdb->query("UPDATE `trac_by_post` SET object_type = 'post';");
        $wpdb->query("RENAME TABLE `trac_by_post` to `trac_by_object`;");

        // trac_by_date
        $wpdb->query("ALTER TABLE `trac_by_date` DROP INDEX `label`;");
        $wpdb->query("ALTER TABLE `trac_by_date` DROP INDEX `date`;");
        $wpdb->query("ALTER TABLE `trac_by_date` ADD KEY `date` (`date`,`label`);");

        // transfer terms from _date to _object
        $results = $wpdb->get_results("SELECT id, date, label, term_id, SUM(`count`) AS count FROM `trac_by_date` WHERE `term_id` <> 0 GROUP BY `label`, `term_id`;");
        foreach ($results as $row) {
            $wpdb->insert( "trac_by_object", [
                'object_id' =>      $row->term_id,
                'object_type' =>    'term',
                'label' =>          $row->label,
                'count' =>          $row->count,
                'last_modified' =>  current_time('mysql')
            ], ['%d', '%s', '%s', '%d', '%s'] );
        }

        // consolitate _date
        $results = $wpdb->get_results("SELECT id, date, label, SUM(`count`) AS count FROM `trac_by_date` WHERE `term_id` <> 0 GROUP BY `label`, `date`;");
        $wpdb->query("DELETE FROM `trac_by_date` WHERE `term_id` <> 0;");
        $wpdb->query("ALTER TABLE `trac_by_date` DROP COLUMN `term_id`;");
        foreach ($results as $row) {
            $wpdb->insert( "trac_by_date", [
                'id' =>             $row->id,
                'date' =>           $row->date,
                'label' =>          $row->label,
                'count' =>          $row->count,
                'last_modified' =>  current_time('mysql')
            ], ['%d', '%s', '%s', '%d', '%s'] );
        }
    }

    // var_dump($results);
    // die();
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
     * Graph Data
     */
    $chart = [];
    $days = 14;

    for ($i=$days; $i > 0 ; $i--) {
        $chart['dates'][] = date("Y-m-d", strtotime("-$i days"));
        $chart['labels'][] = '"'.date("j M", strtotime("-$i days")).'"';
    }
    foreach (['videodl', 'audiodl', 'podcastdl'] as $label) {
        $rows = $wpdb->get_results($wpdb->prepare(
            "SELECT `date`, sum(count) as `count`
                FROM `trac_by_date`
                WHERE `date` BETWEEN '%s' AND '%s'
                AND `label` = '%s' GROUP BY `date`;
            ",
            date("Y-m-d", strtotime("-$days days")),
            date("Y-m-d", strtotime("-1 days")),
            $label
        ));
        $chart[$label] = array_fill(0, $days, 0);
        foreach ($rows as $row) {
            $key = array_search($row->date, $chart["dates"]);
            $chart[$label][$key] = (int) $row->count;
        }
    }


    /*
     * Total Downloads
     */
    $total = [];
    foreach (array('videodl', 'audiodl', 'podcastdl') as $label) {
        $total[$label] = number_format((int) $wpdb->get_var(
            sprintf(
                "SELECT sum(count) FROM trac_by_date WHERE label = '%s'",
                $label
            )
        ) ?: 0);
    }

    /*
     * Relative Downloads
     */
    $daily = [];
    foreach (array('videodl', 'audiodl', 'podcastdl') as $label) {
        $daily[$label] = number_format((int) $wpdb->get_var(
            sprintf(
                "SELECT avg(count)
                    FROM `trac_by_date`
                    WHERE label = '%s'
                    AND `date` > '%s';",
                $label,
                date("Y-m-d", strtotime("-3 month"))
            )
        ) ?: 0);
    }


    /*
     * Podcast subscribers
     */
    $subs = [];
    $values = $wpdb->get_results($wpdb->prepare(
        "SELECT
            (SELECT avg(count) FROM `trac_by_date` WHERE label = %s AND `date` BETWEEN %s AND %s) AS `current`,
            (SELECT avg(count) FROM `trac_by_date` WHERE label = %s AND `date` BETWEEN %s AND %s) AS `previous`;
        ",
        'podcastdl',
        date("Y-m-d", strtotime("-22 days")), // + 21 days
        date("Y-m-d", strtotime("-1 days")),
        'podcastdl',
        date("Y-m-d", strtotime("-44 days")), // + 21 days
        date("Y-m-d", strtotime("-23 days"))
    ))[0];

    foreach($values as $key => $value) {
        $subs[$key] = number_format((int) $value);
    };
    $subs['diff'] = number_format((int) $values->current - $values->previous);

?>
<div class="activity-block">
    <h3><strong>
        Downloads (last 10 days)
        <span style="color: #d70206;">Video</span> /
        <span style="color: #f4c63d;">Audio</span> /
        <span style="color: #d17905;">Podcast</span>
    </strong></h3>
    <div id="trac-download-chart"></div>
    <script>
        var chartist_data = {
          // A labels array that can contain any sort of values
          labels: [<?= implode(',', $chart['labels']) ?>],
          // Our series array that contains series objects or in this case series data arrays
          series: [
            [<?= implode(',', $chart['videodl']) ?>],
            [],
            [<?= implode(',', $chart['audiodl']) ?>],
            [<?= implode(',', $chart['podcastdl']) ?>]
          ]
        };
    </script>
</div>
<div class="table" style="padding-top: 1em;">
    <table class="widefat striped" cellspacing="10">
        <tbody>
            <tr>
                <th><span class="dashicons dashicons-format-video"></span> Videos</th>
                <td class="right"><?= $total['videodl'] ?> <small>total</small></td>
                <td class="right"><?= $daily['videodl'] ?><small>/day</small></td>
            </tr>
            <tr>
                <th><span class="dashicons dashicons-format-audio"></span> Audios</th>
                <td class="right"><?= $total['audiodl'] ?> <small>total</small></td>
                <td class="right"><?= $daily['audiodl'] ?><small>/day</small></td>
            </tr>
            <tr>
                <th><span class="dashicons dashicons-rss"></span> Podcasts</th>
                <td class="right"><?= $total['podcastdl'] ?> <small>total</small></td>
                <td class="right"><?= $daily['podcastdl'] ?><small>/day</small></td>
            </tr>
            <tr>
                <th><span class="dashicons dashicons-rss"></span> Subscriptions</th>
                <td class="right"><?= $subs['current'] ?> <small>total</small></td>
                <td class="right" style="color: <?= $subs['diff'] >= 0 ? 'green' : 'red' ?>;">
                    <?= abs($subs['diff']) ?>
                    <?php if ($subs['diff'] >= 0): ?>
                        <span class="dashicons dashicons-arrow-up"></span>
                    <?php else: ?>
                        <span class="dashicons dashicons-arrow-down"></span>
                    <?php endif ?>
                </td>
            </tr>
        </tbody>
    </table>
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
        SELECT wp_posts.ID, trac_by_object.count
        FROM wp_term_taxonomy
        LEFT JOIN wp_term_relationships ON wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_taxonomy_id
        LEFT JOIN wp_posts ON wp_posts.ID = wp_term_relationships.object_id
        LEFT JOIN trac_by_object ON trac_by_object.post_id = wp_posts.ID AND trac_by_object.label = 'podcastdl'
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





