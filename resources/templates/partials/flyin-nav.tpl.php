<?php
use function AppTheme\template;
use function AppTheme\config;
use function AppTheme\render_menu_for_flyin;
?>

<aside id="mobileNav" class="c-flyin <?= $style_modifier ?>">

    <div class="c-flyin__inner">

        <header class="c-flyin__header">
            <h2 class="c-flyin__title"><?= __('Menu', config('textdomain')) ?></h2>
            <button class="c-btn c-btn--blocky c-btn--bigicon c-btn--square c-flyin__btn jsFlyinBtn"
                data-target="mobileNav" data-action="close">
                <span class="u-ic-close"></span>
            </button>
        </header>

        <div class="o-wrapper u-mt">

            <div>
                <?php template('partials/searchform', ['style_modifier' => 'c-search-bar--mobile u-mv']) ?>
                <hr class="u-break-wrapper u-hidden-from@mobile"/>
            </div>

            <nav class="u-mb u-hidden-from@tablet">

                <?php render_menu_for_flyin('primary'); ?>

            </nav>

            <nav class="u-mb">

                <?php render_menu_for_flyin('flyin'); ?>

            </nav>

        </div>

    </div>

</aside><!--end .c-flyin-->
