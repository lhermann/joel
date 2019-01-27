<?php
use function Tonik\Theme\App\template;
use function Tonik\Theme\App\config;
?>

<?php get_header() ?>

<main role="main" class="o-wrapper">

    <?php template('partials/archive-tabs', [
        'style_modifier' => 'u-break-wrapper@until-tablet u-4/5@tablet u-3/4@desktop u-box-center'
    ]) ?>


    <section class="o-layout o-layout--center u-mt">
        <div class="o-layout__item u-4/5@tablet u-3/4@desktop">


            <?php template('vue-components/medialist-init', [
                'id' => 'medialist-topics',
                'style_modifier' => '',
                'options' => [
                    'route' => 'topics',
                    'title' => __('Topics', config('textdomain')),
                    'sorting' => true,
                    'pagination' => 'verbose',
                    'columns' => 2
                ],
                'params' => [
                    'hide_empty' => true,
                    'per_page' => 20,
                    'orderby' => 'name',
                    'parent' => 0
                ]
            ]) ?>


        </div>
    </section>

</main>

<?php get_footer() ?>
