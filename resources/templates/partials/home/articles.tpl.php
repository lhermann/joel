<?php
/**
 * Articles list section.
 *
 * @var WP_Query $articles_query Query with the latest articles
 */
?>

<section class="py-8 md:py-12 bg-gray-50">
  <div class="max-w-screen-xl mx-auto px-4">

    <div class="flex items-center gap-3 mb-4">
      <h2 class="text-xl md:text-2xl font-bold m-0">Artikel</h2>
      <a class="c-btn c-btn--tiny c-btn--subtle text-sm" href="<?= esc_url(get_permalink(get_page_by_path('artikel'))) ?>">
        Alle Artikel anzeigen
        <span class="u-ic-arrow_forward"></span>
      </a>
    </div>

    <ul class="list-none m-0 p-0 divide-y divide-gray-200">
      <?php while ($articles_query->have_posts()): $articles_query->the_post(); ?>
        <li class="py-3">
          <a href="<?= esc_url(get_permalink()) ?>" class="block no-underline group">
            <span class="text-base font-medium text-gray-800 group-hover:text-blue-700 transition-colors">
              <?= esc_html(get_the_title()) ?>
            </span>
            <span class="block text-sm text-gray-500 mt-0.5">
              <?= esc_html(get_the_date('j. F Y')) ?>
              <span class="mx-1">&middot;</span>
              <?= esc_html(get_the_author()) ?>
            </span>
          </a>
        </li>
      <?php endwhile; wp_reset_postdata(); ?>
    </ul>

  </div>
</section>
