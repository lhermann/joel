<?php
use function AppTheme\template;
use function AppTheme\config;
?>

<?php get_header() ?>
<?php template('partials/header') ?>

<main role="main" class="o-wrapper">

    <nav class="c-tabs u-break-wrapper@until-tablet u-4/5@tablet u-3/4@desktop u-box-center">

        <ul class="o-list-bare o-layout o-layout--flush">

            <li class="o-layout__item u-1/4 c-tabs__item is-active">
                <a class="c-tabs__link u-advent-sans u-text-center"
                    href="/<?= __('recordings', config('textdomain')) ?>/">
                    <?= __('Recordings', config('textdomain')) ?>
                </a>
            </li>
            <li class="o-layout__item u-1/4 c-tabs__item">
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


            <!-- <header class="u-mb+">
                <div class="o-pack o-pack--auto o-pack--middle">
                    <div class="o-pack__item">
                        <h2 class="u-mb0">
                            <?= __('Recordings', config('textdomain')) ?>
                        </h2>
                    </div>
                    <div class="o-pack__item u-text-right u-hidden-until@tablet">
                        {{> organisms-sorting }}
                    </div>
                </div>

                <ul class="o-pack o-pack--1px u-mb0 u-mt u-hidden-from@tablet">
                    {{# sortOptions }}
                    {{# mobile }}
                    <li class="o-pack__item">
                        <button class="c-btn c-btn--secondary c-btn--tiny c-btn--edgy u-1/1 {{btnClass}}">
                            {{label}}
                            {{# direction }}<span class="u-ic-keyboard_arrow_{{direction}}"></span>{{/ direction }}
                        </button>
                    </li>
                    {{/ mobile }}
                    {{/ sortOptions }}
                </ul>

            </header> -->


            <?php template('partials/medialist', [
                'id' => 'medialist-recordings',
                'style_modifier' => '',
                'options' => [
                    'route' => 'recordings',
                    'header' => __('Videos', config('textdomain')),
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
