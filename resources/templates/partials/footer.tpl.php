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
                    <nav class="o-layout__item u-1/4@desktop u-1/2@mobile">
                        <h3 class="c-site-footer__heading u-truncate">Joel Media</h3>
                        <ul class="c-site-footer__list">
                            <?php wp_nav_menu( [
                                'theme_location' => 'footer_sitemap',
                                'container' => false,
                                'items_wrap' => '%3$s'
                            ] ); ?>
                        </ul>
                    </nav>
                    <nav class="o-layout__item u-1/4@desktop u-1/2@mobile">
                        <h3 class="c-site-footer__heading u-truncate">Videoarchiv</h3>
                        <ul class="c-site-footer__list">
                            <li><a class="u-truncate" href="../../patterns/04-pages-archive-videos/04-pages-archive-videos.html">Videos</a></li>
                            <li><a class="u-truncate" href="">Themen</a></li>
                            <li><a class="u-truncate" href="">Sprecher</a></li>
                            <li><a class="u-truncate" href="">Serien</a></li>
                        </ul>
                    </nav>
                    <nav class="o-layout__item u-1/4@desktop u-1/2@mobile">
                        <h3 class="c-site-footer__heading u-truncate">Themenseiten</h3>
                        <ul class="c-site-footer__list">
                            <li><a class="u-truncate" href="#">Prophetie &amp; Zeitgeschehen</a></li>
                            <li><a class="u-truncate" href="#">Erweckung &amp; Evangelium</a></li>
                            <li><a class="u-truncate" href="#">Gesundheit &amp; Familie</a></li>
                            <li><a class="u-truncate" href="#">Tägliche Andachten</a></li>
                            <li><a class="u-truncate" href="#">Fragen zur Bibel</a></li>
                        </ul>
                    </nav>
                    <nav class="o-layout__item u-1/4@desktop u-1/2@mobile">
                        <h3 class="c-site-footer__heading u-truncate">Joel Media Social</h3>
                        <ul class="c-site-footer__list">
                            <li><a class="u-truncate" href="http://youtube.com/c/JoelMediaMinistryeV"><span class="u-ic-youtube"></span> YouTube</a></li>
                            <li><a class="u-truncate" href="https://www.facebook.com/joelmediatv/"><span class="u-ic-facebook"></span> Facebook</a></li>
                        </ul>
                    </nav>
                    <hr class="c-site-footer__hr u-mb+ u-hidden-from@tablet">
                </div>
            </div>
            <nav class="o-layout__item u-1/4@tablet">
                <h3 class="c-site-footer__heading u-truncate">Joel Media Projekte</h3>
                <ul class="c-site-footer__list">
                    <li><a class="u-truncate" href="http://hacksawridge.de">hacksawridge.de</a></li>
                    <li><a class="u-truncate" href="http://fackelderreformation.de">fackelderreformation.de</a></li>
                    <li><a class="u-truncate" href="http://weltengeschichte.de">weltengeschichte.de</a></li>
                </ul>
            </nav>
        </div>
    </section>

    <section class="c-site-footer__copyright u-text-center">
        <ul class="c-site-footer__list c-site-footer__list--inline c-site-footer__list--middot u-mb0">
            <li>Joel Media Ministry e.V. &copy; <?= date('Y') ?></li>
            <?php wp_nav_menu( [
                'theme_location' => 'footer',
                'container' => false,
                'items_wrap' => '%3$s'
            ] ); ?>
        </ul>


    </section>

</footer>
