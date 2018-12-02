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

            <div class="o-layout o-layout--large">
                <div class="o-layout__item u-3/5@tablet u-2/3@desktop">
                    <div class="o-ratio o-ratio--16:9 u-box-shadow ">
                        <iframe id="player"
                            class="o-ratio__content c-player"
                            src="<?= config('url-prefix')['embed'].'0livestream' ?>"
                            frameborder="0"
                            allowfullscreen>
                        </iframe>
                    </div>
                </div>
                <div class="o-layout__item u-2/5@tablet u-1/3@desktop u-mt u-mt0@tablet">
                    <div class="u-anchor">
                        <div class="c-tlkio">

                            <!-- <div id="tlkio" data-channel="joelmedia"></div>
                            <script async src="//tlk.io/embed.js" type="text/javascript"></script> -->

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="o-wrapper u-mb++">

        <div class="o-layout o-layout--large">
            <div class="o-layout__item u-3/5@tablet u-2/3@desktop">

                <div class="o-flex o-flex--middle o-flex--between u-pv">
                    <div class="o-flex__item u-lead">
                        <span class="c-primary-nav__signal"></span> <strong>Live</strong>
                        <span class="u-hidden-from@desktop"><br>0:24:13</span>
                    </div>
                    <div class="o-flex__item u-text-center u-lead u-pl- u-hidden-until@desktop">
                        0:24:13
                    </div>
                    <div class="o-flex__item u-pl@desktop">
                        <div class="o-flag">
                            <div class="o-flag__img">
                                <img class="u-rounded u-hidden-until@desktop" src="{{image}}" width="80">
                            </div>
                            <div class="o-flag__body">
                                <strong class="u-lead">Offenbarung Vers für Vers</strong>
                                <br>Christopher Kramp · 19:30 – 20:15
                            </div>
                        </div>
                    </div>
                    <div class="o-flex__item u-text-right u-lead">
                        <strong>20 <span class="u-hidden-until@tablet">Zuschauer</span></strong>
                    </div>
                </div>

                <hr/>

            </div>
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
