<?php
use function Tonik\Theme\App\template;
use function Tonik\Theme\App\config;
$options = isset($options) ? str_replace('"', "'", json_encode($options)) : '{}';
$params = isset($params) ? str_replace('"', "'", json_encode($params)) : '{}';
?>

<!-- Vue livestream dropdown root component -->
<div id="<?= $id ?>"
  class="<?= $style_modifier ?>"
  data-vue="livestream-dropdown"
  :init="init(<?= $options ?>, <?= $params ?>)"
  @mouseover="onMouseover"
>

  <a href="<?= home_url( '/livestream/' ) ?>"
    class="c-link c-link--block c-link--primary c-primary-nav__link">
    <streamcheck :stream="options.stream">
      <template slot-scope="props">
        <span class="c-dot u-mr--"
          :class="{'c-dot--green': props.live, 'is-loading': props.loading}">
        </span>
      </template>
    </streamcheck>
    <span class="u-hidden-until@desktop"><?= __('Livestream', config('textdomain')) ?></span>
    <span class="u-hidden-from@desktop"><?= _x('Live', 'Short for livestream', config('textdomain')) ?></span>
    <span class="u-ic-keyboard_arrow_down"></span>
  </a>

  <ul class="c-primary-nav__dropdown" style="width: 500px">

    <li v-cloak
      v-for="event in events"
      :key="event.occurrence_id"
      class="c-primary-nav__dropdown-item"
      :class="{'u-bg-muted': !event.today}"
    >
      <event :event="event" url="<?= home_url( '/livestream/' ) ?>" />
    </li>

    <li v-if="!events.length"
      class="c-primary-nav__dropdown-item u-bg-muted u-p">
      <div class="c-spinner u-box-center"></div>
    </li>

    <li class="c-primary-nav__dropdown-item">
      <a href="<?= home_url( '/livestream/' ) ?>"
        class="c-link c-link--block c-link--primary u-truncate">
        <?= __('Go to livestream', config('textdomain')) //Livestream &Ouml;ffnen ?>
        <span class="u-ic-arrow_forward"></span>
      </a>
    </li>

  </ul>

</div>

