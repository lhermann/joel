<?php
use function Tonik\Theme\App\template;
?>

<?php get_header() ?>

<main role="main" class="o-wrapper o-wrapper--slim u-mb+">

    <div class="u-center u-mv">
      <?= get_the_post_thumbnail(null, 'medium', ['class' => 'u-rounded u-shadow-3']) ?>
    </div>

    <?php while (have_posts()): the_post() ?>

        <?php template('partials/post/content-event'); ?>

    <?php endwhile; ?>


    <hr class="u-mt+" />

    <div class="u-pb+">
      <h2 class="u-h3 u-mb-">
          <span class="u-text-middle u-mr-">Weitere Veranstaltungen:</span>
      </h2>

      <?php template('vue-components/main', [
          'component' => 'JoEvents',
          'id' => 'vue-events',
          'style_modifier' => '',
          'params' => [
            'numberposts' => 3,
            'event-category' => 'veranstaltung',
            'exclude' => get_the_ID()
          ]
      ]) ?>
    </div>

</main>

<?php get_footer() ?>
