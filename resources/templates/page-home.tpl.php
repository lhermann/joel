<?php
use function Tonik\Theme\App\template;
use function Tonik\Theme\App\config;

get_header();
?>

<main role="main">

  <?php // ─── Hero ─────────────────────────────────────────────── ?>
  <?php template('partials/home/hero') ?>

  <?php // ─── New Videos ───────────────────────────────────────── ?>
  <?php
  $new_videos = new WP_Query([
      'post_type' => 'recordings',
      'posts_per_page' => 12,
      'orderby' => 'date',
      'order' => 'DESC',
  ]);

  if ($new_videos->have_posts()):
      ob_start();
      while ($new_videos->have_posts()):
          $new_videos->the_post();
          template('partials/home/card-video', ['post' => $new_videos->post]);
      endwhile;
      wp_reset_postdata();
      $cards_html = ob_get_clean();

      template('partials/home/card-row', [
          'title' => 'Neue Videos',
          'cards_html' => $cards_html,
          'link' => home_url('/' . _x('recordings', 'http route', config('textdomain')) . '/'),
          'link_text' => 'Alle Videos anzeigen',
      ]);
  endif;
  ?>

  <?php // ─── Popular Videos ──────────────────────────────────── ?>
  <?php
  $popular_posts = [];
  if (class_exists('WordPressPopularPosts\Query')) {
      $wpp = new \WordPressPopularPosts\Query([
          'post_type' => 'recordings',
          'limit' => 12,
          'range' => 'last30days',
      ]);
      $popular_posts = $wpp->get_posts();
  }

  if ($popular_posts):
      ob_start();
      foreach ($popular_posts as $pop_post):
          $post_obj = get_post($pop_post->id);
          if ($post_obj):
              template('partials/home/card-video', ['post' => $post_obj]);
          endif;
      endforeach;
      $cards_html = ob_get_clean();

      template('partials/home/card-row', [
          'title' => 'Beliebte Videos',
          'cards_html' => $cards_html,
          'link' => home_url('/' . _x('recordings', 'http route', config('textdomain')) . '/'),
          'link_text' => 'Alle Videos anzeigen',
      ]);
  endif;
  ?>

  <?php // ─── New Series ──────────────────────────────────────── ?>
  <?php
  global $wpdb;

  $series_results = $wpdb->get_results("
      SELECT t.term_id, t.name, t.slug, tt.count AS episode_count,
             MAX(p.post_date) AS latest_episode
      FROM {$wpdb->terms} t
      JOIN {$wpdb->term_taxonomy} tt ON tt.term_id = t.term_id AND tt.taxonomy = 'series'
      JOIN {$wpdb->term_relationships} tr ON tr.term_taxonomy_id = tt.term_taxonomy_id
      JOIN {$wpdb->posts} p ON p.ID = tr.object_id AND p.post_status = 'publish' AND p.post_type = 'recordings'
      GROUP BY t.term_id
      HAVING episode_count > 0
      ORDER BY latest_episode DESC
      LIMIT 12
  ");

  if ($series_results):
      ob_start();
      foreach ($series_results as $series_row):
          $series_term = get_term($series_row->term_id, 'series');
          if (!$series_term || is_wp_error($series_term)) continue;
          $series_term->episode_count = (int) $series_row->episode_count;
          template('partials/home/card-series', ['series' => $series_term]);
      endforeach;
      $cards_html = ob_get_clean();

      template('partials/home/card-row', [
          'title' => 'Neue Serien',
          'cards_html' => $cards_html,
          'link' => home_url('/' . _x('series', 'http route', config('textdomain')) . '/'),
          'link_text' => 'Alle Serien anzeigen',
      ]);
  endif;
  ?>

  <?php // ─── Tägliche Andachten ──────────────────────────────── ?>
  <?php
  $andachten = new WP_Query([
      'post_type' => 'recordings',
      'posts_per_page' => 12,
      'orderby' => 'date',
      'order' => 'DESC',
      'tax_query' => [[
          'taxonomy' => 'series',
          'terms' => [368],
      ]],
  ]);

  if ($andachten->have_posts()):
      ob_start();
      while ($andachten->have_posts()):
          $andachten->the_post();
          template('partials/home/card-video', ['post' => $andachten->post]);
      endwhile;
      wp_reset_postdata();
      $cards_html = ob_get_clean();

      $andachten_term = get_term(368, 'series');
      $andachten_link = !is_wp_error($andachten_term) ? get_term_link($andachten_term) : '';

      template('partials/home/card-row', [
          'title' => 'Tägliche Andachten',
          'cards_html' => $cards_html,
          'link' => $andachten_link,
          'link_text' => 'Alle Andachten anzeigen',
      ]);
  endif;
  ?>

  <?php // ─── Articles ────────────────────────────────────────── ?>
  <?php
  $articles = new WP_Query(['posts_per_page' => 5]);
  if ($articles->have_posts()):
      template('partials/home/articles', ['articles_query' => $articles]);
  endif;
  ?>

  <?php // ─── Donate CTA ──────────────────────────────────────── ?>
  <?php template('partials/home/donate') ?>

  <?php // ─── Footer ──────────────────────────────────────────── ?>
  <?php template('partials/home/footer') ?>

</main>

<style>
  .page-template-page-home .c-site-footer { display: none; }

  /* Card row scroll button transitions (Vue-style enter/leave) */
  .enter-from, .leave-to { opacity: 0; }
  .enter-to, .leave-from { opacity: 1; }
  .enter-from, .enter-to, .leave-from, .leave-to {
    transition: opacity 300ms ease;
  }
</style>

<?php get_footer() ?>
