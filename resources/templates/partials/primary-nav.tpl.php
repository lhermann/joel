<?php
use function Tonik\Theme\App\config;
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

                        <li class="c-primary-nav__dropdown__item u-text-center">
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

            <a href="../../patterns/04-pages-homepage/04-pages-homepage.html" class="c-link c-link--block c-link--primary
                c-primary-nav__link ">
                    <span class="c-primary-nav__signal"></span>
                <span class="u-hidden-until@desktop">Livestream</span>
                <span class="u-hidden-from@desktop">Live</span>
                <span class="u-ic-keyboard_arrow_down"></span>
            </a>

            <ul class="c-primary-nav__dropdown">
                    <li class="c-primary-nav__dropdown__item">
                        <a class="o-box o-box--natural c-link c-link--block c-link--primary" href="#">
                            <span class="c-badge c-badge--success">live</span>
                            <strong>Heute</strong> um <strong>17:30</strong>
                            <br><small>Predigt mit Markus Witte</small>
                        </a>
                    </li>
                    <li class="o-box o-box--natural c-primary-nav__dropdown__item
                        c-primary-nav__dropdown__item--alternate">
                        <strong class="u-lead">Dienstag</strong>
                        <span class="u-muted">· 20. Juni · 19:30</span>
                        <br><small>Der Ersehnte &amp; Offenbarung Vers für Vers</small>
                    </li>
                    <li class="o-box o-box--natural c-primary-nav__dropdown__item
                        c-primary-nav__dropdown__item--alternate">
                        <strong class="u-lead">Dienstag</strong>
                        <span class="u-muted">· 20. Juni · 19:30</span>
                        <br><small>Der Ersehnte &amp; Offenbarung Vers für Vers</small>
                    </li>
                    <li class="c-primary-nav__dropdown__item">
                        <a class="c-link c-link--block c-link--primary u-truncate" href="../../patterns/04-pages-homepage/04-pages-homepage.html">
                            Livestream Seite <span class="u-ic-arrow_forward"></span>
                        </a>
                    </li>
            </ul>

        </li>
        <?php endif ?>

    </ul><!--end c-primary-nav__list-->

</nav><!--end c-primary-nav-->
