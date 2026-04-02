<?php
/**
 * Reusable horizontal card row section.
 *
 * @var string $title     Section heading
 * @var string $cards_html Pre-rendered card HTML
 * @var string $link      Optional "show all" URL
 * @var string $link_text Optional "show all" label
 */
$link = $link ?? '';
$link_text = $link_text ?? 'Alle anzeigen';
?>

<section class="py-8 md:py-12">
  <div class="max-w-screen-xl mx-auto px-4 md:px-8">

    <!-- Heading -->
    <div class="flex items-center gap-3 mb-4">
      <h2 class="text-xl md:text-2xl font-bold m-0"><?= esc_html($title) ?></h2>
      <?php if ($link): ?>
        <a class="c-btn c-btn--tiny c-btn--subtle text-sm" href="<?= esc_url($link) ?>">
          <?= esc_html($link_text) ?>
          <span class="u-ic-arrow_forward"></span>
        </a>
      <?php endif ?>
    </div>

    <!-- Scroll container -->
    <div class="relative" data-vue="cardRowScroll">
      <!-- Left arrow -->
      <button
        data-ref="prev"
        class="group hidden md:flex absolute z-20 left-0 inset-y-0 w-16 items-center justify-center text-white bg-gradient-to-l from-transparent via-white/30 to-white"
        aria-label="Zurück scrollen"
      >
        <div class="p-2 rounded-full bg-blue-700/70 backdrop-blur-sm text-white opacity-0 group-hover:opacity-100 group-hover:-translate-x-1 transition-all">
          <span class="u-ic-arrow_back text-2xl"></span>
        </div>
      </button>

      <!-- Cards -->
      <div
        data-ref="scroller"
        class="flex gap-2 overflow-x-auto scroll-smooth snap-x snap-mandatory pb-2 -mx-2"
        style="scrollbar-width: none; -ms-overflow-style: none;"
      >
        <?= $cards_html ?>
      </div>

      <!-- Right arrow -->
      <button
        data-ref="next"
        class="group hidden md:flex absolute z-20 right-0 inset-y-0 w-16 items-center justify-center bg-gradient-to-r from-transparent via-white/30 to-white"
        aria-label="Weiter scrollen"
      >
        <div class="p-2 rounded-full bg-blue-700/70 backdrop-blur-sm text-white opacity-0 group-hover:opacity-100 group-hover:translate-x-1 transition-all">
          <span class="u-ic-arrow_forward text-2xl"></span>
        </div>
      </button>
    </div>

  </div>
</section>
