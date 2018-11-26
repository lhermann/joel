<?php
/**
 * The template for displaying taxonomys.
 */

use function Tonik\Theme\App\Legacy\trac_permalink;
use function Tonik\Theme\App\Legacy\update_trac_database;
use function Tonik\Theme\App\Legacy\get_video_file;


header('Content-Type: application/rss+xml; charset=UTF-8');


$term = get_queried_object();
$fields = (object) get_fields($term);
$author = get_field('autor', $term);
$author = htmlspecialchars($author ? $fields->autor->name : "");
$link = $fields->website_link ?: get_term_link($term);
update_trac_database(0, 'podcastping', $term->term_id);


?>
<?php print('<?xml version="1.0" encoding="utf-8"?>'); //<? is interpreted as php short tag ?>
<rss xmlns:atom="http://www.w3.org/2005/Atom" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" xmlns:media="http://search.yahoo.com/mrss/" version="2.0">
     <channel>
            <title><?php htmlspecialchars(single_term_title()) ?></title>
            <atom:link href="<?= get_term_link($term) ?>" rel="self" type="application/rss+xml" />
            <link><?= $link ?></link>
            <description><?= "<![CDATA[" . term_description() . "]]>" ?></description>
            <lastBuildDate><?= get_the_date('r') ?></lastBuildDate>
            <language>de-DE</language>
            <copyright>Copyright &#xA9; Joel Media Ministry e.V. 2012-<?= date('Y') ?></copyright>

            <image>
                <url><?= wp_get_attachment_url( get_field('image', $term)) ?></url>
                <title><?php htmlspecialchars(single_term_title()) ?></title>
                <link><?= $link ?></link>
            </image>

            <itunes:subtitle>Ein Programm von Joel Media Ministry e.V.</itunes:subtitle>
            <itunes:author>Joel Media Ministry e.V.<?= $author ? ' / '.$author : '' ?></itunes:author>
            <itunes:summary><?= "<![CDATA[" . term_description() . "]]>" ?></itunes:summary>
            <itunes:owner>
                    <itunes:name>Joel Media Ministry e.V.<?= $author ? ' / '.$author : '' ?></itunes:name>
                    <itunes:email>kontakt@joelmediatv.de</itunes:email>
            </itunes:owner>
            <itunes:image href="<?= wp_get_attachment_url( get_field('image', $term)) ?>"/>
            <itunes:explicit>no</itunes:explicit>

            <media:copyright>Copyright &#xA9; Joel Media Ministry e.V. 2012-<?= date('Y') ?></media:copyright>
            <media:thumbnail url="<?= wp_get_attachment_url( get_field('image', $term)) ?>" />
            <media:category scheme="http://www.itunes.com/dtds/podcast-1.0.dtd">Religion &amp; Spirituality</media:category>
            <media:category scheme="http://www.itunes.com/dtds/podcast-1.0.dtd">Christianity</media:category>

            <category>Religion &amp; Spirituality, Christianity</category>
            <?php
                foreach ($fields->categorien as $i => $value) {
                    if( strpos($value, '/') ) {
                        $sub_cat = explode('/', $value);
                        $cat_array[$sub_cat[0]][] = $sub_cat[1];
                    } else {
                        $cat_array[$value] = array();
                    }
                }
                foreach ($cat_array as $key => $value) : ?>
                <itunes:category text="<?= htmlspecialchars($key) ?>">
                    <?php foreach ($value as $sub) : ?>
                        <itunes:category text="<?= htmlspecialchars($sub) ?>"/>
                    <?php endforeach; ?>
                </itunes:category>
            <?php endforeach; ?>

            <?php if ( have_posts() ) :
                while ( have_posts() ) : the_post();
                    $audio = get_video_file(get_the_ID(), 'audio');
                    if( !$audio ) continue;
                    ?>

                    <item>
                        <title><?php htmlspecialchars(the_title_rss()) ?></title>
                        <link><?php the_permalink() ?></link>
                        <pubDate><?= get_the_date('r') ?></pubDate>
                        <guid isPermaLink="false"><?php the_guid() ?></guid>
                        <description><?= "<![CDATA[" . get_the_content_feed('rss2') . "]]>" ?></description>
                        <enclosure url="<?= trac_permalink(get_the_ID(), 'podcastdl', 'https://vodhttp.joelmediatv.de/'.$audio->relative_url) ?>" length="<?= $audio->size ?>" type="audio/mpeg" />
                        <itunes:duration><?= $audio->length ?></itunes:duration>
                        <itunes:subtitle>Ein Programm von Joel Media Ministry e.V.</itunes:subtitle>
                        <dc:creator>Joel Media Ministry e.V.</dc:creator>
                        <itunes:summary><?= "<![CDATA[" . get_the_content_feed('rss2') . "]]>" ?></itunes:summary>
                        <itunes:author><?php
                            foreach(wp_get_post_terms(get_the_ID(), 'speakers') as $i => $s) {
                                echo $i != 0 ? ", " : ""; echo htmlspecialchars($s->name);
                            }
                        ?></itunes:author>
                        <itunes:explicit>no</itunes:explicit>
                        <itunes:block>no</itunes:block>
                    </item>

                <?php endwhile;
            endif; ?>

     </channel>
</rss>
