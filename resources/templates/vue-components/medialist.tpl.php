<?php
use function Tonik\Theme\App\template;
$component = isset($component) ? $component : $id;
$options = isset($options) ? json_encode($options) : '{}';
$params = isset($params) ? json_encode($params) : '{}';
?>

<!-- Vue mountpoint -->
<section
  id="<?= $id ?>"
  class="<?= $style_modifier ?>"
  data-vue="JoMedialist"
  data-options='<?= $options ?>'
  data-params='<?= $params ?>'
>
  <!-- Placeholders -->
  <?php if ($options['title'] ?? false): ?>
  <header v-if="false" class="u-pv">
    <div class="o-flex o-flex--middle o-flex--between">
      <div class="o-flex__item">
        <h2 class="u-mb0"><?= $options['title'] ?? '' ?></h2>
      </div>
      <div class="o-flex__item">
        <div class="c-spinner"></div>
      </div>
    </div>
  </header>
  <?php endif ?>
  <ul v-if="false" class="c-medialist">
    <?php for ($i = 0; $i < 3; $i++): ?>
    <li class="c-medialist__item">
      <div class="o-media c-mediaitem c-mediaitem--<?= $options['route'] ?? 'video' ?> c-mediaitem--dummy">
        <div class="o-media__img c-mediaitem__img">
          <div class="c-mediaitem__length"><div>0:0</div></div>
        </div>
        <div class="o-media__body c-mediaitem__body">
          <div class="c-mediaitem__title"></div>
          <div class="c-mediaitem__meta"></div>
        </div>
      </div>
    </li>
    <?php endfor; ?>
  </ul>

</section>
