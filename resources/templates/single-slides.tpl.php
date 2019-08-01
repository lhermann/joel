<?php
use function Tonik\Theme\App\template;
use function Tonik\Theme\App\config;
?>

<?php get_header() ?>

<main>

    <div class="o-wrapper u-text-center u-pt">
        <h1><?= __('Slide', config('textdomain')) ?>: <?php the_title() ?></h1>
    </div>

    <?php while (have_posts()): the_post() ?>

        <?php template('vue-components/slider', [
            'id' => 'main-slider',
            'options' => [
                'mode' => 'none',
                'teaser' => true,
                'id' => get_the_ID(),
                'placeholder' => false
            ]
        ]) ?>

    <?php endwhile; ?>

</main>

<?php get_footer() ?>
