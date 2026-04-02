<?php
/**
 * Custom home page footer with 4-column layout.
 *
 * Col 1: hardcoded logo + description
 * Cols 2-4: widget areas footer_1, footer_2, footer_3
 * Below: copyright fineprint with legal/admin links
 */

// Shared Tailwind classes for widget nav columns
$widget_nav_classes = 'text-sm [&_h3]:text-white [&_h3]:font-semibold [&_h3]:mb-3 [&_ul]:list-none [&_ul]:m-0 [&_ul]:p-0 [&_li]:mb-1.5 [&_a]:text-gray-400 [&_a]:no-underline hover:[&_a]:text-white [&_a]:transition-colors';
?>

<footer class="bg-gray-900 text-gray-300 pt-12 pb-6" role="contentinfo">
  <div class="max-w-screen-xl mx-auto px-4 md:px-8">

    <!-- 4-column grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-10">

      <!-- Col 1: Logo + name + description (hardcoded) -->
      <div>
        <img
          src="<?= esc_url(\Tonik\Theme\App\asset_path('images/jm-logo-white-01.svg')) ?>"
          alt="Joel Media Ministry"
          class="h-10 w-auto mb-3"
        >
        <p class="text-white font-semibold mb-1">Joel Media Ministry e.V.</p>
        <p class="text-sm text-slate-500 leading-relaxed">
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
    <div class="border-t border-slate-700 pt-6 text-center text-sm !text-slate-500">
      <ul class="c-site-footer__list u-mb0">
        <li><?php bloginfo( 'name' ); ?> &copy; <?= date('Y') ?></li>
        <?php wp_nav_menu( [
          'theme_location' => 'footer',
          'container' => false,
          'items_wrap' => '%3$s'
        ] ); ?>
      </ul>
    </div>

  </div>
</footer>
