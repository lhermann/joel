<?php
use function Tonik\Theme\App\config;
use function Tonik\Theme\App\asset_path;
use function Tonik\Theme\App\Helper\get_series_of_podcast;

$podcast->itunes = get_field('itunes_link', $podcast);
$podcast->stitcher = get_field('stitcher_link', $podcast);
$podcast->image = get_field('image', $podcast);
$podcast->series = get_series_of_podcast($podcast);

?>


<div class="o-layout u-mb">
    <div class="o-layout__item u-2/3@tablet">

        <div class="o-media">
            <div class="o-media__img">
                <?= wp_get_attachment_image($podcast->image, 'square80', null, ['class' => 'u-rounded']) ?>
            </div>
            <div class="o-media__body">
                <h3 class="u-h5 u-mb--"><?= $podcast->name ?></h3>
                <div class="u-small u-muted">
                    <p class="u-mb-"><?= $podcast->description ?></p>
                    <p class="u-mb--">
                        <?php if (count($podcast->series) == 1): ?>
                            Dieser Podcast beinhaltet die folgende Serie:
                        <?php else: ?>
                            Dieser Podcast beinhaltet die folgenden Serien:
                        <?php endif ?>
                    </p>
                    <ul class="u-mb0">
                        <?php foreach ($podcast->series as $series): ?>
                            <li>
                                <a href="<?= get_term_link($series); ?>">
                                    <?= $series->name ?>
                                </a>
                            </li>
                        <?php endforeach ?>
                    </ul>
                </div>
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

