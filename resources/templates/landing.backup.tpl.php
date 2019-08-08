<?php
use function Tonik\Theme\App\theme;
use function Tonik\Theme\App\template;
use function Tonik\Theme\App\config;
?>

<?php get_header() ?>

<main role="main">

    <?php foreach (config('slider') as $section => $options): ?>

        <?php template('partials/landing/'.$section, $options) ?>

    <?php endforeach ?>



    <?php if (false && config('slider') && theme('slides') ): ?>
        <section id="slider" class="c-section c-section--flush">

            <?php template('vue-components/slider', [
                'id' => 'main-slider',
                'options' => [
                    'mode' => 'initial',
                    'teaser' => true
                ]
            ]) ?>

        </section>
    <?php endif ?>


    <?php if (config('landing-promo')): ?>
    <section id="promo-list" class="c-section u-pt">

        <div class="o-wrapper o-wrapper--flush">

            <?php template('partials/landing/promo-list', [
                'style_modifier' => 'o-overflow--padding'
            ]) ?>

        </div>

    </section>
    <?php endif ?>

    <?php if ( eo_get_events([
        'showpastevents' => false,
        'post_status' => 'publish',
        'event-category' => 'veranstaltung',
        'numberposts' => 1
    ]) ): ?>
    <section id="event-list" class="c-section u-pt u-pb0">

        <div class="o-wrapper">

            <?php template('partials/landing/event-list') ?>

        </div>

        <hr class="u-m0 u-mt" />

    </section>
    <?php endif ?>

    <?php if (config('landing-videos-simple')): ?>
    <section class="c-section u-pv">

        <div class="o-wrapper">

            <h2 class="u-h3 u-mb-">
                <span class="u-text-middle u-mr-">Neue Videos</span>
                <a class="c-btn c-btn--tiny c-btn--subtle u-text"
                    href="<?= home_url( '/'._x('recordings', 'http route', config('textdomain')).'/' ) ?>">
                    Alle Videos anzeigen
                    <span class="u-ic-arrow_forward"></span>
                </a>
            </h2>

            <?php template('vue-components/medialist', [
                'id' => 'medialist-simple',
                'options' => [
                    'columns' => 3
                ],
                'params' => [
                    'per_page' => 3
                ]
            ]) ?>

        </div>

        <hr class="u-m0 u-mt-" />

    </section>
    <?php endif ?>


    <?php if (config('landing-videos')): ?>
    <section id="medialists" class="c-section u-pt">
        <div class="o-wrapper u-hidden-from@tablet u-mb">

            <h2 class="u-h3 u-mb-">
                <span class="u-text-middle u-mr-">Neue Videos</span>
                <a class="c-btn c-btn--tiny c-btn--subtle u-text"
                    href="<?= home_url( '/'._x('recordings', 'http route', config('textdomain')).'/' ) ?>">
                    Alle Videos anzeigen
                    <span class="u-ic-arrow_forward"></span>
                </a>
            </h2>

            <?php template('vue-components/medialist', [
                'id' => 'medialist-new',
                'options' => [],
                'params' => [
                    'per_page' => 5,
                    'series_exclude' => 368
                ]
            ]) ?>

        </div>
        <div class="o-wrapper">

            <div class="o-layout o-layout--large">
                <div class="o-layout__item u-1/3@desktop u-1/1 u-mb">

                    <h2 class="u-h3 u-mb-">Beliebte Videos</h2>

                    <?php template('vue-components/medialist', [
                        'id' => 'medialist-popular',
                        'options' => [
                            'namespace' => 'wordpress-popular-posts/v1/',
                            'route' => 'popular-posts'
                        ],
                        'params' => [
                            'post_type' => 'recordings',
                            'per_page' => 5,
                            'limit' => 5,
                            'range' => 'custom',
                            'time_unit' => 'day',
                            'time_quantity' => 7
                        ]
                    ]) ?>

                </div>
                <div class="o-layout__item u-1/3@desktop u-1/2@tablet u-mb">

                    <h2 class="u-h3 u-mb-">
                        <a class="c-link c-link--dotted"
                            href="<?= home_url( '/'._x('series', 'http route', config('textdomain')).'/' ) ?>">
                            <?= __('New Series', config('textdomain')) ?>
                        </a>
                    </h2>

                    <?php template('vue-components/medialist', [
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
                <div class="o-layout__item u-1/3@desktop u-1/2@tablet u-1/1 u-mb">

                    <h2 class="u-h3 u-mb-">
                        <a class="c-link c-link--dotted"
                            href="<?= home_url( '/'._x('series', 'http route', config('textdomain')).'/mit-gott-leben/' ) ?>">
                            Tägliche Andachten
                        </a>
                    </h2>

                    <?php template('vue-components/medialist', [
                        'id' => 'medialist-devotional',
                        'options' => [],
                        'params' => [
                            'per_page' => 5,
                            'series' => 368 // mit Gott leben
                        ]
                    ]) ?>

                </div>
            </div>

        </div>
    </section>
    <?php endif ?>

    <?php if (have_posts()): the_post(); ?>
        <?php if (config('landing-content') && get_post()->post_content): ?>
            <section id="landing-content" class="c-section ">
                <div class="o-wrapper c-article">

                    <?= the_content(); ?>

                </div>
            </section>
        <?php endif; ?>
    <?php endif; ?>

    <?php if (config('landing-quotes')): ?>
    <section class="c-section">

        <?php template(['partials/slider', 'quotes']) ?>

    </section>
    <?php endif ?>


    <?php if (config('landing-articles')): ?>
    <section class="c-section c-section--alt">
        <div class="o-wrapper">

            <h2>
                <span class="u-text-middle">Artikel</span>
                <a class="c-btn c-btn--tiny c-btn--subtle u-text u-ml-"
                    href="<?= get_permalink( get_page_by_path( 'artikel' ) ) ?>">
                    Alle Artikel anzeigen
                    <span class="u-ic-arrow_forward"></span>
                </a>
            </h2>

            <?php template('partials/landing/article-grid') ?>

        </div>

    </section>
    <?php endif ?>

    <?php if (config('landing-newsletter')): ?>
    <section class="c-section">

        <?php template('partials/newsletter-form') ?>

    </section>
    <?php endif ?>


    <?php if (config('landing-donate')): ?>
    <section class="c-section">
        <div class="o-wrapper">
            <div class="o-flex o-flex--between o-flex--middle">
                <div class="o-flex__item">
                    <h2>Joel Media Unterstützen</h2>
                    <p class="u-mb0">Obwohl wir nicht ausdrücklich um Spenden bitten, werden wir fast ausschließlich durch Spenden finanziert.</p>
                    <p class="u-mb0">Unser lieber Gott hat unsere Arbeit bis zu diesem Tag immer treu gesegnet.</p>
                </div>
                <div class="o-flex__item">
                    <a href="<?= get_permalink( get_page_by_path( 'spenden' ) ) ?>"
                        class="c-btn c-btn--primary c-btn--large">
                        Zur Spendenseite
                        <br><small class="u-muted">Unsere Ausgaben und Spendenkonto</small>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <?php endif ?>

</main>

<?php get_footer() ?>
