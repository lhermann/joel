<?php
use function Tonik\Theme\App\config;
use function Tonik\Theme\App\asset_path;
?>

<a class="c-branding <?= $style_modifier ?>" href="<?php bloginfo('url') ?>">
    <div class="c-branding__img">
        <img src="<?= wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ) , 'square80' )[0] ?>"
            class="c-logo "
            alt="<?php bloginfo('name') ?>">
    </div>
    <?php if (config('sta-branding')): ?>
      <div class="c-branding__sda">
        <div class="c-branding__denomination">
          <img src="<?= asset_path('images/sda-text.svg') ?>">
        </div>
        <div class="c-branding__church">
          <?php bloginfo('name') ?>
        </div>
      </div>
    <?php else: ?>
      <div class="c-branding__title"><?php bloginfo('name') ?></div>
    <?php endif; ?>
</a><!-- end c-branding -->
