<?php
use function Tonik\Theme\App\config;

if(!function_exists("eo_get_events")) return;

$events = eo_get_events([
  'showpastevents' => false,
  'event_end_before' => '+'.config('livestream')['program-timeframe'].' week',
  'post_status' => 'publish',
  'event-category' => 'livestream'
]);
$last_event = null;

setlocale(LC_ALL, 'de_DE');
?>

<h2>Programm</h2>

<ul class="c-program ">

  <?php foreach ($events as $key => $event): ?>
    <?php
      $prev = $key > 0 ? $events[$key - 1] : null;
      $next = $key < count($events) - 1 ? $events[$key + 1] : null;
      $today = $event->StartDate == date('Y-m-d');
      $now = $today
        && strtotime($event->StartTime) <= current_time('timestamp')
        && current_time('timestamp') <= strtotime($event->FinishTime);
    ?>

    <?php if (is_null($prev) || $prev->StartDate !== $event->StartDate): ?>
    <li class="c-program__item <?= $today ? 'is-today' : '' ?>">
      <div class="c-program__box">
        <div class="c-program__week"><?= strftime('%a', strtotime($event->StartDate)) ?></div>
        <div class="c-program__date"><?= utf8_encode(strftime('%e. %b', strtotime($event->StartDate))) ?></div>
      </div>
      <ul class="c-program__eventlist">
    <?php endif ?>

        <li class="c-program__event <?= $now ? 'is-now' : '' ?>">
          <div class="c-program__line"></div>
          <div class="c-program__dot"></div>
          <div class="c-program__time">
            <?= strftime('%e. %b', strtotime($event->StartTime)) ?>,
            <strong><?= strftime('%k:%M Uhr', strtotime($event->StartTime)) ?></strong>
          </div>
          <div class="c-program__title"><?= $event->post_title ?></div>
        </li>

    <?php if (is_null($next) || $next->StartDate !== $event->StartDate): ?>
      </ul>
    </li>
    <?php endif ?>

  <?php endforeach ?>

  <li class="c-program__item u-fill"></li>

</ul>
