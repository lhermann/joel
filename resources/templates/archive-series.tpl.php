<?php
use function AppTheme\template;
use function AppTheme\config;
?>

<?php get_header() ?>
<?php template('partials/header') ?>

<main role="main" class="o-wrapper">

    <?php template('partials/archive-tabs', [
        'style_modifier' => 'u-break-wrapper@until-tablet u-4/5@tablet u-3/4@desktop u-box-center'
    ]) ?>


    <section class="o-layout o-layout--center u-mt">
        <div class="o-layout__item u-4/5@tablet u-3/4@desktop">


            <?php template('partials/medialist', [
                'id' => 'medialist-series',
                'style_modifier' => '',
                'options' => [
                    'route' => 'series',
                    'title' => __('Series', config('textdomain')),
                    'sorting' => true,
                    'pagination' => 'verbose',
                    'columns' => 2
                ],
                'params' => [
                    'hide_empty' => true,
                    'per_page' => 20,
                    'orderby' => 'name'
                ]
            ]) ?>


        </div>
    </section>

</main>

<?php get_footer() ?>
