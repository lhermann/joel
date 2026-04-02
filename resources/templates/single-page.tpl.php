<?php
use function Tonik\Theme\App\template;
?>

<?php get_header() ?>

<main role="main" class="max-w-prose mx-auto px-4 md:px-8 u-mv+">

    <?php while (have_posts()): the_post() ?>

        <?php template('partials/post/content-simple'); ?>

    <?php endwhile; ?>

</main>

<?php get_footer() ?>
