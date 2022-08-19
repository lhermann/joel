<?php
use function Tonik\Theme\App\template;
use function Tonik\Theme\App\config;
use function Tonik\Theme\App\asset_path;
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

  <!-- Search Form -->
  <div class="mb-12">
    <div class="relative mb-2">
      <input
        class="placeholder:text-neutral-400 hover:border-blue-500 h-10 pr-12"
        type="text"
        placeholder="Nach Bibelantworten suchen ..."
      >
      <button
        class="absolute right-0 h-10 w-10"
      >
        <span class="text-xl u-ic u-ic-search"></span>
      </button>
    </div>
    <div class="text-sm text-neutral-600 space-x-2">
      <label for="include-text">
        <input id="include-text" type="checkbox" checked> Texte
      </label>
      <label for="include-videos">
        <input id="include-videos" type="checkbox"> Videos
      </label>
    </div>
  </div>

  <!-- Answer Entries -->
  <ul class="m-0">
    <li>
      <article>
        <h2 class="text-xl mb-2">
          <a href="#">Woran erkennt man das Wirken des Heiligen Geistes?</a>
        </h2>
        <p>Der Heilige Geist schreibt das Gesetz Gottes in das Herz des Gläubigen (2. Kor 3,3) und bewirkt dadurch echten Glaubensgehorsam (Hes 36,26.27) ...</p>
      </article>
    </li>
  </ul>

</main>

<?php get_footer() ?>
