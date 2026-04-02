<?php
use function Tonik\Theme\App\template;
?>

<?php get_header() ?>

<main role="main" class="max-w-prose mx-auto px-4 md:px-8 u-mv+">

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
    <br>Schreibe uns deine Frage an <?= cryptx_encrypt('fragen@joelmedia.de') ?>
  </p>

</main>

<?php get_footer() ?>
