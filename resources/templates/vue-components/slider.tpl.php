<?php
use function Tonik\Theme\App\template;
use function Tonik\Theme\App\asset_path;
use function Tonik\Theme\App\config;
use function Tonik\Theme\App\asset;

$options = isset($options) ? json_encode($options) : '{}';
$params = isset($params) ? json_encode($params) : '{}';
?>

<!-- Vue mountpoint -->
<div
  id="<?= $id ?>"
  class="<?= $style_modifier ?>"
  data-vue="JoSlider"
  data-options='<?= $options ?>'
  data-params='<?= $params ?>'
></div>

<!-- Placeholder -->
<div class="c-slider c-slider--placeholder">
  <ul class="c-slider__list">
    <li class="c-slider__item">
      <div
        class="c-slide c-slide-- c-slide--white"
        style="background-image: url('<?= asset_path('images/slide-dark-blue.svg') ?>');"
      >
        <a href="/aufnahmen/" class="c-slide__link"></a>
        <div class="o-wrapper c-slide__wrapper">
          <div class="c-slide__body u-1/2">
            <img
              src="<?= asset('images/jm-logo-white-01.svg')->getUri() ?>"
              class="c-logo c-logo--hero" alt="Joel Media"
            >
            <h1 class="u-mb0 u-mt-">Joel Media Ministry e.V.</h1>
            <p class="u-muted"><small>... das ewige Evangelium für Stuttgart, Deutschland und die Welt</small></p>
            <p class="u-text+ u-muted">Wöchentlich neue Videos über die Bibel,<br>Gesundheit und Zeitgeschehen</p>
          </div>
        </div>
      </div>
    </li>
  </ul>
</div>

