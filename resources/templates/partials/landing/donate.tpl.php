<?php
use function Tonik\Theme\App\template;
if(!isset($args)) $args = [];
?>

<section class="c-section <?= $style_modifier ?>">
  <div class="o-wrapper">
    <div class="o-flex o-flex--between o-flex--middle">
      <div class="o-flex__item">
          <h2>Joel Media Unterstützen</h2>
          <p class="u-mb0">Obwohl wir nicht ausdrücklich um Spenden bitten, werden wir fast ausschließlich durch Spenden finanziert.</p>
          <p class="u-mb0">Unser lieber Gott hat unsere Arbeit bis zu diesem Tag immer treu gesegnet.</p>
      </div>
      <div class="o-flex__item">
          <a href="<?= get_permalink( get_page_by_path( 'spenden' ) ) ?>"
              class="c-btn c-btn--primary c-btn--large">
              Zur Spendenseite
              <br><small class="u-muted">Unsere Ausgaben und Spendenkonto</small>
          </a>
      </div>
    </div>
  </div>
</section>
