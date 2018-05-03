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


            <?php template('vue-components/medialist', [
                'id' => 'medialist-recordings',
                'style_modifier' => '',
                'options' => [
                    'route' => 'recordings',
                    'title' => _x('Recordings', 'post type general name', config('textdomain')),
                    'sorting' => true,
                    'pagination' => 'verbose'
                ],
                'params' => [
                    'per_page' => 50
                ]
            ]) ?>


        </div>
    </section>

</main>

<?php get_footer() ?>
