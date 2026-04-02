<?php
use function Tonik\Theme\App\template;
use function Tonik\Theme\App\config;
?>

<header id="header" class="c-site-header <?= $style_modifier ?>" role="banner">

  <div class="max-w-screen-xl mx-auto px-4 md:px-8">

    <div class="flex items-center gap-4 h-14">

      <div class="">
        <?php template('partials/branding') ?>
      </div>

      <div class="grow"></div>

      <div class="flex-none hidden sm:block">
        <?php template('partials/primary-nav') ?>
      </div>

      <div
        class="flex-none text-right"
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
