<?php
use function AppTheme\template;
use function AppTheme\menu_item_is_active;
?>

<?php get_header() ?>


<div id="siteWrapper" class="c-site-wrapper">

    <?php template('partials/header') ?>

    <main role="main">

        <section class="c-section c-section--flush">

            <?php //template('partials/slider') ?>

            <?php //var_dump(wp_get_nav_menu_items('primary')); ?>

            <?php $menu = wp_get_nav_menu_items('primary');
                var_dump(menu_item_is_active($menu[0], $menu)); ?>

        </section>


        <section class="o-wrapper o-wrapper--no-padding c-section u-mt">

            <?php //template('partials/promo-list', ['style_modifier' => 'o-overflow--padding']) ?>

        </section>


        <section class="o-wrapper c-section">

            <?php //template('partials/medialist', ['style_modifier' => 'u-hidden-from@tablet']) ?>

            <pre>{{> organisms-medialist-3-columns }}</pre>

        </section>


        <section class="c-section">

            <?php //template(['partials/slider', 'quotes']) ?>

        </section>


        <section class="o-wrapper c-section">

            <h2><a class="c-link c-link--dotted" href="#">Blog</a></h2>
            <?php //template('partials/article-grid') ?>

        </section>

        <section class="c-section c-section--alt u-spacer-top">

            <?php //template('partials/newsletter-form') ?>

        </section>

    </main>

    <?php //template('partials/footer') ?>

</div><!-- .c-site-wrapper -->


<?php get_footer() ?>
