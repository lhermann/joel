<?php
use function AppTheme\template;
?>

<header id="header" class="c-site-header <?= $style_modifier ?>" role="banner">

    <div class="o-wrapper">

        <div class="o-pack o-pack--middle c-site-header__wrapper">

            <div class="o-pack__item c-site-header__item">
                <?php template('partials/branding') ?>
            </div>

            <div class="o-pack__item c-site-header__item c-site-header__item--double">
                <?php template('partials/searchform', ['style_modifier' => 'c-search-bar--primary']) ?>
            </div>

            <div class="o-pack__item c-site-header__item u-hidden-until@tablet">
                <?php template('partials/primary-nav') ?>
            </div>

            <div class="o-pack__item c-site-header__item u-text-right">
                <button class="c-btn c-btn--blocky c-btn--bigicon c-btn--square c-site-header__hamburger jsFlyinBtn" data-target="mobileNav" data-action="open">
                    <span class="u-ic-menu"></span>
                </button>
            </div>

        </div>

    </div>

</header><!-- end c-header -->
