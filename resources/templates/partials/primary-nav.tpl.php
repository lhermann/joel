<?php
use function Tonik\Theme\App\config;
use function Tonik\Theme\App\template;
$menu = wp_get_nav_menu_items( wp_get_nav_menu_name( 'primary' ) );
use function Tonik\Theme\App\Helper\menu_item_is_active;
use function Tonik\Theme\App\Helper\menu_item_has_children;
?>

<nav id="nav" class="c-primary-nav <?= $style_modifier ?>">

    <ul class="c-primary-nav__list">

        <?php foreach ($menu as $key => $item): if( $item->menu_item_parent != 0 ) continue; ?>

            <li class="c-primary-nav__item">

                <a href="<?= $item->url ?>"
                    class="c-link c-link--block c-link--primary c-primary-nav__link
                    <?= menu_item_is_active($item, $menu) ? 'is-active' : '' ?>">
                    <?= $item->title ?>
                    <?php if(menu_item_has_children($item, $menu)): ?>
                        <span class="u-ic-keyboard_arrow_down"></span>
                    <?php endif; ?>
                </a>

                <?php if(menu_item_has_children($item, $menu)): ?>
                <ul class="c-primary-nav__dropdown">

                    <?php foreach ($menu as $key => $subitem): if( $subitem->menu_item_parent != $item->ID ) continue; ?>

                        <li class="c-primary-nav__dropdown-item u-text-center">
                            <a class="c-link c-link--block c-link--primary u-truncate
                                <?= menu_item_is_active($subitem) ? 'is-active' : '' ?>"
                                href="<?= $subitem->url ?>">
                                <?= $subitem->title ?>
                            </a>
                        </li>

                    <?php endforeach ?>

                </ul>
                <?php endif; ?>

            </li>

        <?php endforeach ?>

        <!-- Livestream -->
        <?php if (config('livestream')['enabled']): ?>
        <li class="c-primary-nav__item">

            <?php template('vue-components/livestream-dropdown-init', [
                'id' => 'livestream-dropdown',
                'style_modifier' => 'c-livestream-dropdown',
                'options' => ['stream' => 'joelmedia'],
                'params' => ['numberposts' => 3]
            ]) ?>

        </li>
        <?php endif ?>

    </ul><!--end c-primary-nav__list-->

</nav><!--end c-primary-nav-->
