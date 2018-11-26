<footer id="footer" class="c-site-footer " role="contentinfo">

    <h2 class="u-hidden-visually">Footer</h2>

    <section class="o-wrapper u-text-center u-pt u-hidden-from@tablet">
        <a class="c-btn c-btn--secondary" href="#header">
            Zurück zum Anfang <span class="u-ic-arrow_upward"></span>
        </a>
        <hr class="c-site-footer__hr u-mb0">
    </section>

    <section class="o-wrapper c-site-footer__nav">
        <div class="o-layout o-layout--large">
            <div class="o-layout__item u-3/4@tablet">
                <div class="o-layout">
                    <?php if ( is_active_sidebar( 'footer_1' ) ) : ?>
                        <nav class="o-layout__item u-1/4@desktop u-1/2@mobile">
                            <?php dynamic_sidebar( 'footer_1' ); ?>
                        </nav>
                    <?php endif; ?>
                    <?php if ( is_active_sidebar( 'footer_2' ) ) : ?>
                        <nav class="o-layout__item u-1/4@desktop u-1/2@mobile">
                            <?php dynamic_sidebar( 'footer_2' ); ?>
                        </nav>
                    <?php endif; ?>
                    <?php if ( is_active_sidebar( 'footer_3' ) ) : ?>
                        <nav class="o-layout__item u-1/4@desktop u-1/2@mobile">
                            <?php dynamic_sidebar( 'footer_3' ); ?>
                        </nav>
                    <?php endif; ?>
                    <?php if ( is_active_sidebar( 'footer_4' ) ) : ?>
                        <nav class="o-layout__item u-1/4@desktop u-1/2@mobile">
                            <?php dynamic_sidebar( 'footer_4' ); ?>
                        </nav>
                    <?php endif; ?>
                    <hr class="c-site-footer__hr u-mb+ u-hidden-from@tablet">
                </div>
            </div>
            <nav class="o-layout__item u-1/4@tablet">
                <?php dynamic_sidebar( 'footer_right' ); ?>
            </nav>
        </div>
    </section>

    <section class="c-site-footer__copyright u-text-center">
        <ul class="c-site-footer__list u-mb0">
            <li>Joel Media Ministry e.V. &copy; <?= date('Y') ?></li>
            <?php wp_nav_menu( [
                'theme_location' => 'footer',
                'container' => false,
                'items_wrap' => '%3$s'
            ] ); ?>
        </ul>


    </section>

</footer>
