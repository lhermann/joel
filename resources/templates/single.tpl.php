<?php
use function Tonik\Theme\App\template;
use function Tonik\Theme\App\config;
?>

<?php get_header() ?>

<?php template('partials/header') ?>

<main role="main" class="o-wrapper o-wrapper--slim o-wrapper--flush">

    <div class="o-ratio o-ratio--16:9">

        <?= get_the_post_thumbnail(null, '360p', ['class' => 'o-ratio__content']) ?>

    </div>

    <div class="u-p u-mt">

        <?php while (have_posts()): the_post() ?>

            <?php template(['partials/post/content', get_post_format()]); ?>

        <?php endwhile; ?>

    </div>


    <div class="u-center u-mv+">
        <a class="c-btn c-btn--secondary" href="<?= get_permalink( get_page_by_path( 'artikel' ) ) ?>">
            <span class="u-ic-arrow_back"></span>
            Zurück zum Blog
        </a>
    </div>


    <?php if(config('comments')): ?>
    <div class="u-p">

        <hr>
        <?php comments_template(); ?>

    </div>
    <?php endif ?>

</main>

<?php get_footer() ?>
