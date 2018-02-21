<a class="c-branding <?= $style_modifier ?>" href="<?php bloginfo('url') ?>">
    <div class="c-branding__img">
        <img src="<?= wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ) , 'square80' )[0] ?>"
            class="c-logo "
            alt="<?php bloginfo('name') ?>">
    </div>
    <div class="c-branding__title"><?php bloginfo('name') ?></div>
</a><!-- end c-branding -->
