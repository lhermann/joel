<?php
use function Tonik\Theme\App\template;
use function Tonik\Theme\App\Helper\idHash;
use function Tonik\Theme\App\config;
if(!isset($args)) $args = [];
?>

<section class="c-section u-pv">

    <div class="o-wrapper">

        <h2 class="u-h3 u-mb-">
            <span class="u-text-middle u-mr-">Neue Videos</span>
            <a class="c-btn c-btn--tiny c-btn--subtle u-text"
                href="<?= home_url( '/'._x('recordings', 'http route', config('textdomain')).'/' ) ?>">
                Alle Videos anzeigen
                <span class="u-ic-arrow_forward"></span>
            </a>
        </h2>

        <?php template(
            'vue-components/medialist',
            array_merge(
                [ 'id' => idHash($args) ],
                $args
            )
        ); ?>

    </div>

    <hr class="u-m0 u-mt-" />

</section>
