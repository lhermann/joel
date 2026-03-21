<?php
/**
 * Single recording card for the home page card rows.
 *
 * @var WP_Post $post The recording post object
 */
use function Tonik\Theme\App\Helper\fallback_img;
use function Tonik\Theme\App\Legacy\get_video_length;
use function Tonik\Theme\App\asset_path;

$post_id = $post->ID;

// Thumbnail with srcset (wp_get_attachment_image_src returns false when no attachment exists)
$thumb_id = get_field('thumbnail', $post_id);
$thumb_180 = $thumb_id ? wp_get_attachment_image_src($thumb_id, '180p') : false;
$thumb_360 = $thumb_id ? wp_get_attachment_image_src($thumb_id, '360p') : false;
$thumbnail = fallback_img($thumb_360 ? $thumb_360[0] : '', '360p');
$srcset = ($thumb_180 && $thumb_360)
    ? esc_url($thumb_180[0]) . ' 320w, ' . esc_url($thumb_360[0]) . ' 640w'
    : '';

// Duration
$length = get_video_length($post_id);

// Speakers
$speakers = wp_get_post_terms($post_id, 'speakers');
$speaker_names = array_map(fn($s) => $s->name, $speakers);

// Views
$views = function_exists('wpp_get_views') ? wpp_get_views($post_id) : 0;

// Date
$date = get_the_date('j. M Y', $post);

// "Neu" badge for posts ≤7 days old
$is_new = (time() - get_post_time('U', true, $post)) <= 7 * DAY_IN_SECONDS;
?>

<a
  href="<?= esc_url(get_permalink($post)) ?>"
  class="snap-start shrink-0 w-[240px] sm:w-[250px] md:w-[260px] block no-underline text-gray-800 group rounded-lg border border-transparent p-2 transition-all hover:border-[#5387DB] hover:shadow-[0_2px_5px_rgba(0,0,0,0.08)]"
>
  <!-- Thumbnail -->
  <div class="relative aspect-video rounded-lg bg-gray-200 mb-2">
    <img
      src="<?= esc_url($thumbnail) ?>"
      <?php if ($srcset): ?>srcset="<?= $srcset ?>" sizes="300px"<?php endif ?>
      alt="<?= esc_attr(get_the_title($post)) ?>"
      class="w-full h-full object-cover rounded-lg"
      loading="lazy"
      width="640" height="360"
    >
    <?php if ($is_new): ?>
      <img
        src="<?= esc_url(asset_path('images/neu-badge.png')) ?>"
        alt="Neu"
        class="absolute -top-0.5 -left-0.5 w-10 h-10 z-10"
      >
    <?php endif ?>
    <?php if ($length): ?>
      <span class="absolute bottom-1.5 right-1.5 px-1.5 py-0.5 rounded bg-black/75 text-white text-xs leading-snug font-medium">
        <?= esc_html($length) ?>
      </span>
    <?php endif ?>
  </div>

  <!-- Text -->
  <h3 class="text-sm font-semibold leading-snug mb-1 line-clamp-2 group-hover:text-blue-700 transition-colors">
    <?= esc_html(get_the_title($post)) ?>
  </h3>
  <p class="text-xs text-gray-500 m-0 truncate">
    <?php if ($speaker_names): ?>
      <?= esc_html(implode(', ', $speaker_names)) ?>
      <span class="mx-1">&middot;</span>
    <?php endif ?>
    <?php if ($views): ?>
      <?= number_format($views, 0, ',', '.') ?> Klicks
      <span class="mx-1">&middot;</span>
    <?php endif ?>
    <?= esc_html($date) ?>
  </p>
</a>
