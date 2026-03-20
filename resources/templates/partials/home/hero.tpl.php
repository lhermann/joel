<?php
use function Tonik\Theme\App\config;
?>

<section class="relative bg-gradient-to-br from-[#082391] to-[#1455CC] text-white py-16 md:py-24">
  <div class="max-w-3xl mx-auto px-4 text-center">

    <!-- Logo -->
    <?php
    $logo_id = get_theme_mod('custom_logo');
    $logo_src = $logo_id ? wp_get_attachment_image_src($logo_id, 'full') : false;
    if ($logo_src):
    ?>
      <img
        src="<?= esc_url($logo_src[0]) ?>"
        alt="<?= esc_attr(get_bloginfo('name')) ?>"
        class="mx-auto mb-4 h-20 md:h-24 w-auto"
      >
    <?php endif ?>

    <!-- Slogan -->
    <h1 class="text-2xl md:text-3xl font-bold mb-8"><?= esc_html(get_bloginfo('description')) ?></h1>

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
          <button class="c-btn c-btn--primary mr-1 my-1 !rounded-md" type="button">
            Suchen
          </button>
        </div>
      </div>
    </div>

  </div>
</section>
