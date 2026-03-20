<?php
/**
 * Custom home page footer with 4-column layout.
 *
 * Col 1: hardcoded logo + description
 * Cols 2-4: widget areas footer_1, footer_2, footer_3
 * Below: copyright fineprint with legal/admin links
 */

$logo_id = get_theme_mod('custom_logo');
$logo_src = $logo_id ? wp_get_attachment_image_src($logo_id, 'full') : false;

// Shared Tailwind classes for widget nav columns
$widget_nav_classes = 'text-sm [&_h3]:text-white [&_h3]:font-semibold [&_h3]:mb-3 [&_ul]:list-none [&_ul]:m-0 [&_ul]:p-0 [&_li]:mb-1.5 [&_a]:text-gray-400 [&_a]:no-underline hover:[&_a]:text-white [&_a]:transition-colors';
?>

<footer class="bg-gray-900 text-gray-300 pt-12 pb-6" role="contentinfo">
  <div class="max-w-screen-xl mx-auto px-4">

    <!-- 4-column grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-10">

      <!-- Col 1: Logo + description (hardcoded) -->
      <div>
        <?php if ($logo_src): ?>
          <img
            src="<?= esc_url($logo_src[0]) ?>"
            alt="<?= esc_attr(get_bloginfo('name')) ?>"
            class="h-10 w-auto mb-3 brightness-0 invert"
          >
        <?php endif ?>
        <p class="text-sm text-gray-400 leading-relaxed">
          <?= esc_html(get_bloginfo('description')) ?>
        </p>
      </div>

      <!-- Col 2: Widget footer_1 -->
      <?php if (is_active_sidebar('footer_1')): ?>
        <nav class="<?= $widget_nav_classes ?>">
          <?php dynamic_sidebar('footer_1'); ?>
        </nav>
      <?php endif ?>

      <!-- Col 3: Widget footer_2 -->
      <?php if (is_active_sidebar('footer_2')): ?>
        <nav class="<?= $widget_nav_classes ?>">
          <?php dynamic_sidebar('footer_2'); ?>
        </nav>
      <?php endif ?>

      <!-- Col 4: Widget footer_3 -->
      <?php if (is_active_sidebar('footer_3')): ?>
        <nav class="<?= $widget_nav_classes ?>">
          <?php dynamic_sidebar('footer_3'); ?>
        </nav>
      <?php endif ?>

    </div>

    <!-- Fineprint -->
    <div class="border-t border-gray-700 pt-6 text-center text-sm text-gray-500">
      <?= esc_html(get_bloginfo('name')) ?> &copy; <?= date('Y') ?>
      <span class="mx-2">&middot;</span>
      <a href="<?= esc_url(home_url('/impressum/')) ?>" class="text-gray-500 no-underline hover:text-gray-300 transition-colors">Impressum</a>
      <span class="mx-2">&middot;</span>
      <a href="<?= esc_url(home_url('/datenschutzerklaerung/')) ?>" class="text-gray-500 no-underline hover:text-gray-300 transition-colors">Datenschutz</a>
      <span class="mx-2">&middot;</span>
      <a href="<?= esc_url(admin_url()) ?>" class="text-gray-500 no-underline hover:text-gray-300 transition-colors">Admin</a>
    </div>

  </div>
</footer>
