<?php
use function Tonik\Theme\App\template;
use function Tonik\Theme\App\config;
?>

<header id="header" class="c-site-header <?= $style_modifier ?>" role="banner">

  <div class="o-wrapper">

    <div class="c-site-header__flex">

      <div class="c-site-header__item">
        <?php template('partials/branding') ?>
      </div>

      <?php if (config('searchbar')): ?>
      <div class="c-site-header__item u-grow u-shrink u-hidden-until@desktop">
        <?php template('partials/searchform', ['style_modifier' => 'c-search-bar--primary']) ?>
      </div>
      <?php endif ?>

      <div class="c-site-header__item u-shrink u-hidden-until@tablet">
        <?php template('partials/primary-nav') ?>
      </div>

      <div
        class="c-site-header__item u-text-right u-shrink-0 <?= config('mobile-menu-always') ? '' : 'u-hidden-from@tablet' ?>"
      >
        <button class="c-btn c-btn--subtle c-btn--edgy c-btn--bigicon
          c-btn--square c-site-header__hamburger jsFlyinBtn"
          data-target="mobileNav"
          data-action="open"
        >
          <span class="u-ic-menu"></span>
        </button>
      </div>

    </div>

  </div>

</header><!-- end c-header -->
