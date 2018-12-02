<?php
use function Tonik\Theme\App\config;

if(!function_exists("eo_get_events")) return;

$event = eo_get_events([
    'numberposts' => 1,
    'showpastevents' => false,
    'post_status' => 'publish'
]);
$event = end($event);
$today = $event->StartDate == date('Y-m-d');
?>

<div class="o-flex o-flex--middle c-livestream-meta">

    <div class="o-flex__item u-lead">
        <div id="ls_streamcheck" data-vue="streamcheck" :init="init('joelmedia')">
            <span class="c-dot" :class="{'c-dot--green': live}"></span>
            <strong v-if="live" v-cloak>ON AIR</strong>
            <strong v-else class="u-muted">OFFLINE</strong>
        </div>
        <!-- <span class="u-hidden-from@desktop"><br>0:24:13</span> -->
    </div>

    <!-- <div class="o-flex__item u-text-center u-lead u-pl- u-hidden-until@desktop">
        0:24:13
    </div> -->
    <?php if ($today): ?>
    <div class="o-flex__item u-pl@desktop">
        <div class="o-flag">
            <div class="o-flag__img">
                <?= get_the_post_thumbnail( $event->ID, '72p', array( 'class' => 'u-rounded u-hidden-until@desktop', 'width' => '80px' )) ?>
            </div>
            <div class="o-flag__body">
                <strong class="u-lead"><?= $event->post_title ?></strong>
                <br><?= strftime('%k:%M', strtotime($event->StartTime)) ?> &ndash; <?= strftime('%k:%M', strtotime($event->FinishTime)) ?>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="o-flex__item u-pl@desktop">
        <strong class="u-muted">Keine Aufnahmen für heute geplant</strong>
    </div>
    <?php endif ?>
    <!-- <div class="o-flex__item u-text-right u-lead">
        <strong>20 <span class="u-hidden-until@tablet">Zuschauer</span></strong>
    </div> -->
</div>
