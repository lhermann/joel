<?php
use function AppTheme\template;
use function AppTheme\config;
?>

<?php get_header() ?>
<?php template('partials/header') ?>

<main role="main" class="o-wrapper">

    <nav class="c-tabs u-break-wrapper@until-tablet u-4/5@tablet u-3/4@desktop u-box-center">

        <ul class="o-list-bare o-layout o-layout--flush">

            <li class="o-layout__item u-1/4 c-tabs__item ">
                <a class="c-tabs__link u-advent-sans u-text-center"
                    href="/<?= __('recordings', config('textdomain')) ?>/">
                    <?= __('Recordings', config('textdomain')) ?>
                </a>
            </li>
            <li class="o-layout__item u-1/4 c-tabs__item is-active">
                <a class="c-tabs__link u-advent-sans u-text-center"
                    href="/<?= __('series', config('textdomain')) ?>/">
                    <?= __('Series', config('textdomain')) ?>
                </a>
            </li>
            <li class="o-layout__item u-1/4 c-tabs__item ">
                <a class="c-tabs__link u-advent-sans u-text-center"
                    href="/<?= __('speakers', config('textdomain')) ?>/">
                    <?= __('Speakers', config('textdomain')) ?>
                </a>
            </li>
            <li class="o-layout__item u-1/4 c-tabs__item ">
                <a class="c-tabs__link u-advent-sans u-text-center"
                    href="/<?= __('topics', config('textdomain')) ?>/">
                    <?= __('Topics', config('textdomain')) ?>
                </a>
            </li>

        </ul><!--end c-primary-nav__list-->

    </nav>


    <section class="o-layout o-layout--center u-mt">
        <div class="o-layout__item u-4/5@tablet u-3/4@desktop">


            <?php template('partials/medialist', [
                'id' => 'medialist-series',
                'style_modifier' => '',
                'options' => [
                    'route' => 'series',
                    'header' => __('Series', config('textdomain')),
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
