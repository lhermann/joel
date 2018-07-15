<?php
use function Tonik\Theme\App\config;
use function Tonik\Theme\App\Legacy\trac_permalink;
use function Tonik\Theme\App\asset_path;

$podcast = wp_get_post_terms(get_the_ID(), 'podcasts')[0];
$podcast->itunes = get_field('itunes_link', $podcast);
$podcast->stitcher = get_field('stitcher_link', $podcast);
$podcast->image = get_field('image', $podcast);

// var_dump($podcast);

?>


<div class="o-layout o-layout--middle u-mb">
    <div class="o-layout__item u-2/3@tablet">

        <div class="o-flag">
            <div class="o-flag__img">
                <?= wp_get_attachment_image($podcast->image, 'square80', null, ['class' => 'u-rounded']) ?>
            </div>
            <div class="o-flag__body">
                <h2 class="u-default u-muted u-mb0">Podcast:</h2>
                <!-- <p class="u-muted u-small u-mb0">
                    Diese Aufnahme ist als <strong>Podcast</strong> verfügbar:
                </p> -->
                <h3 class="u-h5 u-mb--"><?= $podcast->name ?></h3>
                <p class="u-small u-muted"><?= $podcast->description ?></p>
            </div>
        </div>

    </div>
    <div class="o-layout__item u-1/3@tablet u-mt- u-mt0@tablet">

        <div class="u-mr-- u-mv-- u-ib-until@tablet">
            <a href="<?= $podcast->itunes ?>" target="_blank">
                <img src="<?= asset_path('images/listen-on-apple-podcasts.svg') ?>"
                    alt="Podcast auf Apple Podcasts anzeigen">
            </a>
        </div>
        <div class="u-mr-- u-mv-- u-ib-until@tablet">
            <a href="<?= $podcast->stitcher ?>" target="_blank">
                <img src="<?= asset_path('images/listen-on-stitcher.svg') ?>"
                    alt="Podcast auf Stitcher anzeigen">
            </a>
        </div>
        <div class="u-mr-- u-mv-- u-ib-until@tablet u-small">
            <a class="c-link c-link--muted c-link--dotted u-mr-- u-mv--"
                href="<?= get_term_link($podcast) ?>" target="_blank">
                RSS-Feed
            </a>
        </div>

    </div>
</div>

