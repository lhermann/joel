<?php
use function Tonik\Theme\App\template;
?>

<?php get_header() ?>

<main role="main" class="o-wrapper o-wrapper--slim u-mv+">

  <p class="mb-12">
    <a href="/boogle/">← Zurück</a>
  </p>

  <?php while (have_posts()): the_post() ?>

    <?php template('partials/post/content-answer'); ?>

  <?php endwhile; ?>

  <h2 class="text-lg leading-snug mb-2 mt-24">
    Eigene Frage stellen
  </h2>
  <p>
    Deine Frage wurde nicht richtig beantwortet? Wir fügen ständig neue Antworten hinzu.
    <br>Stelle deine Frage hier:
  </p>
  <?= do_shortcode('[contact-form-7 id="4274" title="Boogle Frage"]') ?>

</main>

<?php get_footer() ?>
