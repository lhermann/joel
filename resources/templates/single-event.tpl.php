<?php
use function Tonik\Theme\App\template;
?>

<?php get_header() ?>

<main role="main" class="o-wrapper o-wrapper--slim u-mv+">

    <?php while (have_posts()): the_post() ?>

        <?php template('partials/post/content-event'); ?>

    <?php endwhile; ?>

</main>

<?php get_footer() ?>
