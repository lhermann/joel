<?php
use function Tonik\Theme\App\config;
use function Tonik\Theme\App\Legacy\trac_permalink;
use function Tonik\Theme\App\asset_path;


$podcast = wp_get_post_terms(get_the_ID(), 'podcasts')[0];
$podcast->itunes = get_field('itunes_link', $podcast);
$podcast->stitcher = get_field('stitcher_link', $podcast);

?>

<div class="u-mb">
    Diese Aufnahme ist als Podcast verfügbar:
    <a class="u-ml-" href="<?= $podcast->itunes ?>" target="_blank">
        <img src="<?= asset_path('images/listen-on-apple-podcasts.svg') ?>"
            alt="Podcast auf Apple Podcasts anzeigen">
    </a>
    <a class="u-ml-" href="<?= $podcast->stitcher ?>" target="_blank">
        <img src="<?= asset_path('images/listen-on-stitcher.svg') ?>"
            alt="Podcast auf Stitcher anzeigen">
    </a>
    <a class="c-btn c-btn--small c-btn--secondary u-ml-"
        href="<?= get_term_link($podcast) ?>" target="_blank">
        RSS-Feed
    </a>
</div>
