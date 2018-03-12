<?php
use function AppTheme\template;
?>

<?php get_header() ?>

<?php template('partials/header') ?>

<main role="main">

    <section class="c-section c-section--flush">

        <?php template('partials/slider', [
            'id' => 'main-slider',
            'mode' => 'initial',
            'duration' => 4000
        ]) ?>

    </section>


    <section class="o-wrapper o-wrapper--no-padding c-section u-mt">

        <?php //template('partials/promo-list', ['style_modifier' => 'o-overflow--padding']) ?>

    </section>


    <section class="o-wrapper c-section">

        <?php //template('partials/medialist', ['style_modifier' => 'u-hidden-from@tablet']) ?>

        <pre>{{> organisms-medialist-3-columns }}</pre>

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
