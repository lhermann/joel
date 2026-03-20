<?php
use function Tonik\Theme\App\config;
use function Tonik\Theme\App\asset_path;
?>

<section class="relative text-white py-16 md:py-24 bg-cover bg-center bg-no-repeat"
  style="background-image: url('<?= esc_url(asset_path('images/slide-dark-blue.svg')) ?>');"
>
  <div class="max-w-3xl mx-auto px-4 text-center">

    <!-- Logo -->
    <img
      src="<?= esc_url(asset_path('images/jm-logo-white-01.svg')) ?>"
      alt="<?= esc_attr(get_bloginfo('name')) ?>"
      class="mx-auto mb-4 h-20 md:h-24 w-auto"
    >

    <!-- Title -->
    <h1 class="text-2xl md:text-3xl font-bold mb-0 mt-2">Joel Media Ministry e.V.</h1>
    <p class="text-sm text-[#d1e4f9] mb-4">
      <small><?= esc_html(get_bloginfo('description')) ?></small>
    </p>
    <p class="text-base text-white/90 mb-8">
      Wöchentlich neue Videos über die Bibel,<br>
      Gesundheit und Zeitgeschehen
    </p>

    <!-- Search input (Vue hydration target) -->
    <div
      data-vue="JoHeroSearch"
      data-options='<?= esc_attr(json_encode(['api_url' => config('study-center-url')])) ?>'
    >
      <div class="max-w-xl mx-auto">
        <div class="flex items-center bg-white rounded-lg shadow-lg">
          <span class="pl-4 text-gray-400 text-xl u-ic-search"></span>
          <input
            type="text"
            class="flex-1 px-3 py-3 text-gray-800 bg-transparent border-none outline-none text-base placeholder:text-gray-400"
            placeholder="Durchsuche das Archiv..."
          >
          <button class="rounded bg-blue-700" type="button">
            Suchen
          </button>
        </div>
      </div>
    </div>

  </div>
</section>
