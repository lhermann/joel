<?php
use function Tonik\Theme\App\theme;
use function Tonik\Theme\App\template;
use function Tonik\Theme\App\config;
?>

<?php get_header() ?>

<?php template('partials/header') ?>

<main role="main">

    <?php if ( theme('slides') ): ?>
        <section id="slider" class="c-section c-section--flush">

            <?php template('vue-components/slider-init', [
                'id' => 'main-slider',
                'options' => [
                    'mode' => 'initial',
                    'teaser' => true
                ]
            ]) ?>

        </section>
    <?php endif ?>


    <?php if (config('promo')): ?>
    <section id="promo-list" class="c-section u-pt">

        <div class="o-wrapper o-wrapper--flush">

            <?php template('partials/landing/promo-list', [
                'style_modifier' => 'o-overflow--padding'
            ]) ?>

        </div>

    </section>
    <?php endif ?>


    <section id="medialists" class="c-section">

        <div class="o-wrapper">

            <div class="o-layout o-layout--large u-hidden-until@tablet">
                <div class="o-layout__item u-1/3@desktop u-1/2@tablet u-1/1">

                    <h2 class="u-h3">Beliebte Videos</h2>

                    <?php template('vue-components/medialist-init', [
                        'id' => 'medialist-popular',
                        'options' => [
                            'namespace' => 'wordpress-popular-posts/v1/',
                            'route' => 'popular-posts'
                        ],
                        'params' => [
                            'post_type' => 'recordings',
                            'limit' => 5
                        ]
                    ]) ?>

                </div>
                <div class="o-layout__item u-1/3@desktop u-1/2@tablet u-hidden-until@tablet">

                    <h2>
                        <a class="c-link c-link--dotted"
                            href="<?= home_url( '/series/' ) ?>">
                            Neue Serien
                        </a>
                    </h2>

                    <?php template('vue-components/medialist-init', [
                        'id' => 'medialist-series',
                        'options' => [
                            'route' => 'series'
                        ],
                        'params' => [
                            'per_page' => 4,
                            'order' => 'desc',
                            'orderby' => 'id'
                        ]
                    ]) ?>

                </div>
                <div class="o-layout__item u-1/3 u-hidden-until@desktop">

                    <h2>
                        <a class="c-link c-link--dotted"
                            href="<?= home_url( '/series/mit-gott-leben/' ) ?>">
                            Tägliche Andachten
                        </a>
                    </h2>

                    <?php template('vue-components/medialist-init', [
                        'id' => 'medialist-devotional',
                        'options' => [],
                        'params' => [
                            'per_page' => 5,
                            'series' => 368 // mit Gott leben
                        ]
                    ]) ?>

                </div>
            </div>

            <?php //template('vue-components/medialist-init', ['style_modifier' => 'u-hidden-from@tablet']) ?>

            <?php /*template('vue-components/medialist-init', [
                'id' => 'medialist-1',
                'options' => [
                    'pagination' => 'normal'
                ],
                'params' => [
                    'per_page' => 10
                ]
            ])*/ ?>

        </div>

    </section>

    <!--
    <section class="c-section">

        <?php //template(['partials/slider', 'quotes']) ?>

    </section>
    -->

    <section class="c-section c-section--alt">

        <div class="o-wrapper">

            <h2>
                <span class="u-text-middle">Artikel</span>
                <a class="c-btn c-btn--small c-btn--light u-default u-muted u-ml"
                    href="<?= get_permalink( get_page_by_path( 'artikel' ) ) ?>">
                    Alle Artikel anzeigen
                    <span class="u-ic-arrow_forward"></span>
                </a>
            </h2>

            <?php template('partials/landing/article-grid') ?>

        </div>

    </section>

    <!--
    <section class="c-section">

        <?php //template('partials/newsletter-form') ?>

    </section>
     -->

</main>

<?php get_footer() ?>
