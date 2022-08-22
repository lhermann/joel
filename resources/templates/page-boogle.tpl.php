<?php
use function Tonik\Theme\App\template;
use function Tonik\Theme\App\config;
use function Tonik\Theme\App\asset_path;
$settings = new Algolia_Settings();
?>

<?php get_header() ?>

<main class="o-wrapper !max-w-prose u-pv+" role="main">

  <header class="mb-12">
    <div class="flex items-center justify-center mb-1">
      <img src="<?= asset_path('images/boogle-logo.svg') ?>" alt="Logo">
      <h1 class="font-semibold mb-0 ml-2">Boogle</h1>
    </div>
    <p class="text-center mb-0">Die Suchmaschine für präzise Bibelantworten</p>
  </header>


  <!-- Boogle Search and List -->
  <div class="mb-24">
    <?php template('vue-components/main', [
      'component' => 'JoBoogleMain',
      'id' => 'JoBoogleMain',
      'style_modifier' => '',
      'options' => [
        'application_id' => $settings->get_application_id(),
        'search_api_key' => $settings->get_search_api_key(),
      ],
    ]) ?>
  </div>

  <h2 class="text-lg leading-snug mb-2">
    Eigene Frage stellen
  </h2>
  <p>Die richtige Antwort auf deine Frage war nicht dabei? Wir fügen ständig neue Antworten hinzu. Stelle deine Frage hier:</p>
  <?= do_shortcode('[contact-form-7 id="4274" title="Boogle Frage"]') ?>

</main>

<?php get_footer() ?>
