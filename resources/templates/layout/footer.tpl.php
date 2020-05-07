<?php
use function Tonik\Theme\App\template;
use function Tonik\Theme\App\asset;
use function Tonik\Theme\App\asset_path;
// $polyfills_sha1 = sha1_file(asset('js/polyfills.js')->getPath());
?>

                <?php template('partials/debug') ?>

                <?php template('partials/footer') ?>

            </div><!-- .c-site-wrapper -->

        <?php template('partials/flyin-nav') ?>

        <?php /*
        <script id="polyfill">
            var modernBrowser = (
                'fetch' in window &&
                'assign' in Object
            );
            if ( !modernBrowser ) {
                var scriptElement = document.createElement('script');
                scriptElement.async = false;
                scriptElement.src = '<?= asset_path('js/polyfills.js').'?'.$polyfills_sha1 ?>';
                // document.head.appendChild(scriptElement);
                var refElement = document.getElementById('polyfill');
                refElement.parentNode.insertBefore(scriptElement, refElement.nextSibling);
            }
        </script>
        */ ?>

        <?php wp_footer(); ?>

    </body>
</html>
