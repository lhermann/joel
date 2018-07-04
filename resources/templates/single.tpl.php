<?php
use function Tonik\Theme\App\template;
?>

<?php get_header() ?>

<?php template('partials/header') ?>

<main role="main" class="o-wrapper o-wrapper--slim u-pt+">

    <?php while (have_posts()): the_post() ?>

        <?php template(['partials/post/content', get_post_format()]); ?>

    <?php endwhile; ?>

</main>

<?php get_footer() ?>
