<?php
use function AppTheme\template;
use function AppTheme\config;
use function AppTheme\asset_path;

$speakers = wp_get_object_terms( get_the_ID(), 'speakers' );
$series = wp_get_object_terms( get_the_ID(), 'series' )[0];
?>

<?php get_header() ?>

<?php template('partials/header') ?>

<main role="main" class="u-mb u-mb++@tablet">

    <?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post() ?>

    <div id="head"
        class="c-header-bg c-header-bg--offset u-pt+"
        style="background-image: url(<?= asset_path('images/header-blue.svg') ?>);">
        <div class="o-wrapper">
            <div class="u-box-center u-3/4@tablet u-2/3@desktop">


                <header class="u-white u-mb u-mb+@tablet">
                    <h1 class="u-responsive u-mb0"><?php the_title() ?></h1>
                    <?php foreach ($speakers as $i => $speaker): ?>
                        <a class="c-link c-link--dotted c-link--white"
                            href="<?= get_term_link( $speaker ) ?>"
                        >
                            <?= $speaker->name ?>
                        </a><?= $i !== count($speakers)-1 ? ',' : '' ?>
                    <?php endforeach ?>
                    <span class="u-mh--">&middot;</span>
                    <a class="c-link c-link--dotted c-link--white"
                        href="<?= get_term_link( $series ) ?>"
                    >
                        <?= $series->name ?>
                    </a>
                </header>

                <div class="o-ratio o-ratio--16:9 u-box-shadow ">
                    <iframe id="player"
                        class="o-ratio__content c-player"
                        src="<?= config('player_base_url').'0'.get_the_ID() ?>"
                        frameborder="0"
                        allowfullscreen>
                    </iframe>
                </div>


            </div>
        </div>
    </div>

    <div class="o-wrapper">
        <div class="u-box-center u-3/4@tablet u-2/3@desktop">

            <section id="infobox">

                <div class="o-pack o-pack--middle o-pack--auto u-mv">

                    <!-- Date -->
                    <div class="o-pack__item">
                        <span class="u-hidden-until@desktop">Veröffentlicht am</span>
                        <time class="u-bolder entry-date updated"
                            datetime="<?= esc_attr( get_the_date( 'c' ) ); ?>">
                            <?= esc_attr( get_the_date('j. F Y') ); ?>
                        </time>
                    </div>

                    <!-- Klicks -->
                    <?php if(function_exists('wpp_get_views')): ?>
                        <div class="o-pack__item">
                            <span class="u-ic-visibility"></span>
                            <?= wpp_get_views( get_the_ID() ) ?>
                            <span class="u-hidden-until@mobile">Klicks</span>
                        </div>
                    <?php endif ?>

                    <!-- Download -->
                    <div class="o-pack__item u-text-right">
                            <button id="download-button" class="c-btn c-btn--dropdown c-btn--small c-btn--secondary jsDropdownBtn" aria-haspopup="true" aria-expanded="false">
                                <span class="u-ic-download"></span> Download
                            </button>

                            <ul class="c-dropdown c-dropdown--round u-hidden jsDropdown" aria-labelledby="download-button" data-placement="bottom-end" x-placement="top-end">

                                <li class="c-dropdown__item ">
                                    <a class="c-link c-link--block c-link--secondary" href="#">
                                        <span class="u-ic-videocam"></span> <strong>HD</strong> 307 MB <small class="u-muted">– MP4 720p</small>
                                    </span></span></a>
                                </li>
                                <li class="c-dropdown__item ">
                                    <a class="c-link c-link--block c-link--secondary" href="#">
                                        <span class="u-ic-videocam"></span> <strong>SD</strong> 127 MB <small class="u-muted">– MP4 360p</small>
                                    </span></span></a>
                                </li>
                                <li class="c-dropdown__item ">
                                    <a class="c-link c-link--block c-link--secondary" href="#">
                                        <span class="u-ic-headset"></span> <strong>HQ</strong> 36 MB <small class="u-muted">– MP3 56kb/s</small>
                                    </span></span></a>
                                </li>
                                <li class="c-dropdown__item ">
                                    <a class="c-link c-link--block c-link--secondary" href="#">
                                        <span class="u-ic-headset"></span> <strong>LQ</strong> 16 MB <small class="u-muted">– MP3 32kb/s</small>
                                    </span></span></a>
                                </li>

                            </ul>
                    </div>

                    <!-- Share -->
                    <div class="o-pack__item u-text-right u-hidden-until@tablet">
                            <button id="share-button"
                                class="c-btn c-btn--dropdown c-btn--small c-btn--secondary jsDropdownBtn"
                                aria-haspopup="true"
                                aria-expanded="false">
                                <span class="u-ic-share"></span> Teilen
                            </button>

                            <ul class="c-dropdown c-dropdown--round u-hidden jsDropdown"
                                aria-labelledby="share-button"
                                data-placement="bottom-end">

                                <li class="c-dropdown__item ">
                                    <a class="c-link c-link--block c-link--secondary"
                                        href="http://www.facebook.com/sharer/sharer.php?u=<?= get_permalink() ?>"
                                        target="_blank">
                                        <span class="u-ic-facebook u-mr--"></span>
                                        Auf Facebook teilen
                                    </a>
                                </li>
                                <li class="c-dropdown__item u-ph u-pv-">
                                    <p class="u-mb--">Video-Player in die eigene Website einbetten:</p>
                                    <input class="u-1/1"
                                        type="text"
                                        onclick="this.select()"
                                        readonly="readonly"
                                        value="<?=
                                            htmlentities(sprintf(
                                                '<iframe width="560" height="315" src="%s" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>',
                                                config('player_base_url').'1'.get_the_ID()
                                            ))
                                        ?>">
                                </li>

                            </ul>
                    </div>
                </div>

                <hr>

            </section>

            <?php if (get_the_content()): ?>
            <section id="content">

                <div class="u-mv+ u-text-center">

                    <?php if (strlen(get_the_content()) > 600): ?>

                        <div class="u-text-left u-mb- u-show-more">
                            <?php the_content() ?>
                        </div>
                        <button class="c-btn c-btn--ghost c-btn--subtle c-btn--tiny u-ph jsToggle"
                            data-target=".u-show-more"
                            data-class="is-visible">
                            <span>
                                <span class="u-ic-plus"></span> alles anzeigen
                            </span>
                            <span class="u-hidden">
                                <span class="u-ic-minus"></span> verbergen
                            </span>
                        </button>

                    <?php else: ?>

                        <div class="u-text-left u-mb-">
                            <?php the_content() ?>
                        </div>

                    <?php endif ?>

                </div>

                <hr class="u-mv+">

            </section>
            <?php endif ?>

            <section id="next-video">

                <!-- <h2 class="u-h5 u-mb--">Nächstes Video</h2> -->
                <h2 class="u-h5 u-mb--">Weitere Aufnahmen</h2>

                <p class="u-small u-muted">
                    Serie:
                    <a class="c-link c-link--dotted u-ml--"
                        href="<?= get_term_link( $series ) ?>">
                        <?= $series->name ?>
                    </a>
                </p>

                <?php template('vue-components/medialist', [
                    'id' => 'medialist-next-video',
                    'options' => [
                        'pagination' => 'normal'
                    ],
                    'params' => [
                        'per_page' => 7,
                        'series' => $series->term_id,
                        'exclude' => get_the_ID()
                    ]
                ]) ?>

                <hr class="u-mv+">

            </section>

            <?php if (false): ?>
            <section id="recommended">

                <?php template('vue-components/medialist', [
                    'id' => 'medialist-recommended',
                    'options' => [
                        'pagination' => 'minimal'
                    ],
                    'params' => [
                        'per_page' => 7,
                        'series' => $series->term_id,
                        'exclude' => get_the_ID()
                    ]
                ]) ?>

                <hr class="u-mv+">

            </section>
            <?php endif ?>

            <section id="license">

                <div class="o-flag ">
                    <div class="o-flag__img">
                        <img src="" style="max-width: 100px;">
                    </div>
                    <div class="o-flag__body u-smaller">
                        <h2 class="u-default u-mb0 u-muted">Lizenz</h2>
                        Copyright ©2017 Joel Media Ministry e.V.
                        <br>Dieses Werk ist lizenziert unter einer Creative Commons Namensnennung - Nicht kommerziell - Keine Bearbeitungen 4.0 International Lizenz.
                    </div>
                </div>

        </div>
    </div>

    <?php endwhile ?>
    <?php endif ?>

</main>

<?php //template('partials/footer') ?>

<?php get_footer() ?>
