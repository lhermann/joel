<?php
use function Tonik\Theme\App\template;
?>

<?php get_header() ?>

<?php template('partials/header') ?>

<main role="main" class="o-wrapper o-wrapper--slim u-mt+">

    <?php while (have_posts()): the_post() ?>

        <?php template('partials/post/content-simple'); ?>

    <?php endwhile; ?>

</main>

<?php get_footer() ?>
