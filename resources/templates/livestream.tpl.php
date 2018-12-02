<?php
use function Tonik\Theme\App\template;
use function Tonik\Theme\App\config;
use function Tonik\Theme\App\asset_path;
?>

<?php get_header() ?>

<?php template('partials/header') ?>

<main role="main">

    <?php if(have_posts()): the_post() ?>

    <div id="head" class="c-header-bg c-header-bg--offset u-pt+"
        style="background-image: url(<?= asset_path('images/header-blue.svg') ?>);">
        <div class="o-wrapper">

            <header class="u-white">
                <h1><?php the_title() ?></h1>
            </header>

            <div class="o-flex">
                <div class="o-flex__item u-1/1 u-3/5@tablet u-2/3@desktop">
                    <div class="o-ratio o-ratio--16:9 u-box-shadow u-1/1">
                        <iframe id="player"
                            class="o-ratio__content c-player"
                            src="<?= config('url-prefix')['embed'].'0livestream' ?>"
                            frameborder="0"
                            allowfullscreen>
                        </iframe>
                    </div>
                </div>
                <div class="o-flex__item u-1/1 u-2/5@tablet u-1/3@desktop u-mv u-m0@tablet u-anchor">
                    <div class="c-tlkio">

                        <!-- <div id="tlkio" data-channel="joelmedia"></div>
                        <script async src="//tlk.io/embed.js" type="text/javascript"></script> -->

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="o-wrapper u-mb++">

        <div class="u-3/5@tablet u-2/3@desktop">

                <?php template('partials/livestream-meta') ?>

                <hr class="u-m0" />

        </div>

        <div class="u-mt+">

            <?php template('partials/program') ?>

        </div>

        <div class="u-mt+">
            <hr/>
            <div class="o-media u-mv">
                <div class="o-media__img">
                    <span class="u-ic-warning u-textmuted" style="font-size: 300%;"></span>
                </div>
                <div class="o-media__body u-small u-textmuted">
                    <p class="u-mb-">Alle Sendungen, deren Rechte bei Joel Media Ministry e.V. liegen, werden auf dieser Seite kostenfrei zur Verfügung gestellt. Es ist erlaubt, diese Inhalte dann herunterzuladen und zu vervielfältigen. Es ist nicht erlaubt, diese Inhalte zu verkaufen. Es ist erlaubt diese Inhalte auf der eigenen Internetseite zu verwenden, bzw. bei YouTube (oder vergleichbaren Diensten) hochzuladen und auf der eigenen Internetseite einzubinden. In diesem Fall bitten wir um kurze Benachrichtigung. Es ist nicht gestattet, den Livestream ohne ausdrückliche Erlaubnis aufzuzeichnen.</p>
                    <p class="u-m0">Nicht alle Sprecher geben uns die Rechte an ihren Aufzeichnung, woraufhin wir die Aufnahmen auch nicht online stellen dürfen. Wir bitten auch Sie, diese Rechte zu würdigen und die Videos nicht ohne Zustimmung auf YouTube oder anderen Plattformen zu veröffentlichen.</p>
                </div>
            </div>
            <hr/>
        </div>

    </div>

    <?php endif ?>

</main>

<?php get_footer() ?>
