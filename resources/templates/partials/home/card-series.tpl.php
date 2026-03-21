<?php
/**
 * Single series card for the home page card rows.
 *
 * @var object $series Term object with additional ->episode_count property
 */
use function Tonik\Theme\App\Helper\fallback_img;
use function Tonik\Theme\App\Helper\get_terms_associated_with_term;

$term_id = $series->term_id;

// Thumbnail with srcset (wp_get_attachment_image_src returns false when no attachment exists)
$image_id = get_field('image', 'series_' . $term_id);
$thumb_180 = $image_id ? wp_get_attachment_image_src($image_id, '180p') : false;
$thumb_360 = $image_id ? wp_get_attachment_image_src($image_id, '360p') : false;
$thumbnail = fallback_img($thumb_360 ? $thumb_360[0] : '', '360p');
$srcset = ($thumb_180 && $thumb_360)
    ? esc_url($thumb_180[0]) . ' 320w, ' . esc_url($thumb_360[0]) . ' 640w'
    : '';

// Speakers associated with this series
$speakers = get_terms_associated_with_term($term_id, 'speakers');
$speaker_names = array_map(fn($s) => $s->name, $speakers);

// Episode count
$episode_count = $series->episode_count ?? $series->count ?? 0;
?>

<a
  href="<?= esc_url(get_term_link($series)) ?>"
  class="snap-start shrink-0 w-[240px] sm:w-[250px] md:w-[260px] block no-underline text-gray-800 group rounded-lg border border-transparent p-2 transition-all hover:border-[#5387DB] hover:shadow-[0_2px_5px_rgba(0,0,0,0.08)]"
>
  <!-- Thumbnail -->
  <div class="relative aspect-video rounded-lg bg-gray-200 mb-2">
    <img
      src="<?= esc_url($thumbnail) ?>"
      <?php if ($srcset): ?>srcset="<?= $srcset ?>" sizes="300px"<?php endif ?>
      alt="<?= esc_attr($series->name) ?>"
      class="w-full h-full object-cover rounded-lg"
      loading="lazy"
      width="640" height="360"
    >
    <?php if ($episode_count): ?>
      <span class="absolute bottom-1.5 right-1.5 px-1.5 py-0.5 rounded bg-black/75 text-white text-xs leading-snug font-medium">
        <?= (int) $episode_count ?> Folgen
      </span>
    <?php endif ?>
  </div>

  <!-- Text -->
  <h3 class="text-sm font-semibold leading-snug mb-1 line-clamp-2 group-hover:text-blue-700 transition-colors">
    <?= esc_html($series->name) ?>
  </h3>
  <p class="text-xs text-gray-500 m-0 truncate">
    <?= (int) $episode_count ?> Folgen
    <?php if ($speaker_names): ?>
      <span class="mx-1">&middot;</span>
      <?= esc_html(implode(', ', $speaker_names)) ?>
    <?php endif ?>
  </p>
</a>
