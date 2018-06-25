<?php
use function AppTheme\theme;
use function AppTheme\template;
?>

<?php get_header() ?>

<?php template('partials/header') ?>

<main role="main">

    <?php if ( theme('slides') ): ?>
        <section id="slider" class="c-section c-section--flush">

            <?php template('vue-components/slider-instantiator', [
                'id' => 'main-slider',
                'options' => [
                    'mode' => 'initial',
                    'teaser' => true
                ]
            ]) ?>

        </section>
    <?php endif ?>


    <section id="promo-list" class="o-wrapper o-wrapper--no-padding c-section u-mt">

        <?php template('partials/promo-list', ['style_modifier' => 'o-overflow--padding']) ?>

    </section>


    <section id="medialists" class="o-wrapper c-section">

        <div class="o-layout o-layout--large u-hidden-until@tablet">
            <div class="o-layout__item u-1/3@desktop u-1/2@tablet u-1/1">

                <h2 class="u-h3">Beliebte Videos</h2>

                <?php template('vue-components/medialist-instantiator', [
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

                <h3>
                    <a class="c-link c-link--dotted" href="#">
                        Neue Serien
                    </a>
                </h3>

                <?php template('vue-components/medialist-instantiator', [
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

                <h3>
                    <a class="c-link c-link--dotted" href="#">
                        Tägliche Andachten
                    </a>
                </h3>

                <?php template('vue-components/medialist-instantiator', [
                    'id' => 'medialist-devotional',
                    'options' => [],
                    'params' => [
                        'per_page' => 5,
                        'series' => 368 // mit Gott leben
                    ]
                ]) ?>

            </div>
        </div>

        <?php //template('vue-components/medialist-instantiator', ['style_modifier' => 'u-hidden-from@tablet']) ?>

        <?php /*template('vue-components/medialist-instantiator', [
            'id' => 'medialist-1',
            'options' => [
                'pagination' => 'normal'
            ],
            'params' => [
                'per_page' => 10
            ]
        ])*/ ?>

    </section>


    <section class="c-section">

        <?php //template(['partials/slider', 'quotes']) ?>

    </section>


    <section class="o-wrapper c-section">

        <h2><a class="c-link c-link--dotted" href="#">Blog</a></h2>
        <?php //template('partials/article-grid') ?>

    </section>

    <section class="c-section c-section--alt u-spacer-top">

        <?php //template('partials/newsletter-form') ?>

    </section>

</main>

<?php //template('partials/footer') ?>

<?php get_footer() ?>
