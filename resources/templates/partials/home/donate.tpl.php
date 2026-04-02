<?php
/**
 * Donation CTA section.
 */
?>

<section class="py-12 md:py-16">
  <div class="max-w-screen-xl mx-auto px-4 md:px-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 bg-gray-50 rounded-xl p-6 md:p-10">
      <div>
        <h2 class="text-xl md:text-2xl font-bold mb-2">Joel Media Unterstützen</h2>
        <p class="text-gray-600 m-0">
          Obwohl wir nicht ausdrücklich um Spenden bitten, werden wir fast ausschließlich durch Spenden finanziert.
          <br class="hidden md:block">
          Unser lieber Gott hat unsere Arbeit bis zu diesem Tag immer treu gesegnet.
        </p>
      </div>
      <div class="shrink-0">
        <a href="<?= esc_url(get_permalink(get_page_by_path('spenden'))) ?>"
           class="c-btn c-btn--primary c-btn--large">
          Zur Spendenseite
        </a>
      </div>
    </div>
  </div>
</section>
