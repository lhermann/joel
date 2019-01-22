<?php
use function Tonik\Theme\App\template;
use function Tonik\Theme\App\config;
use Tonik\Theme\App\Store;
if( Store::isset_then_set('vue/slider/teaser') ) return;
?>


<!-- template for the teaser component -->
<?= '<script type="text/x-template" id="slider-teaser-component">' ?>

    <div class="c-slider__teaser-container u-hidden-until@tablet">
        <div class="o-wrapper u-h100">
            <div class="c-slider__teaser c-collapsible" :class="{'is-collapsed': teaserCollapsed}">
                <div class="c-collapsible__header">
                    <button class="c-btn c-btn--subtle c-btn--edgy u-defocus u-ph u-1/1"
                        @click="onCollapseClick" ref="button">
                        <div class="o-flex o-flex--middle o-flex--between">
                            <h5 class="c-collapsible__title u-m0">
                                <?= __('New Recordings', config('textdomain')) ?>
                            </h5>
                            <span class="u-ic-keyboard_arrow_down"
                                v-show="teaserCollapsed"></span>
                            <span class="u-ic-keyboard_arrow_up"
                                v-show="!teaserCollapsed"></span>
                        </div>
                    </button>
                </div>
                <div class="c-collapsible__body u-ph">

                    <medialist-component
                        class="u-1/1"
                        :init-options="options"
                        :init-params="params"
                    />

                    <div class="c-slider__teaser-btn u-text-center">
                        <a class="c-btn c-btn--small c-btn--subtle" href="<?= home_url( '/'._x(
                            'recordings',
                            'http route',
                            config('textdomain')
                        ).'/' ) ?>">
                            Alle Videos anzeigen
                            <span class="u-ic-arrow_forward"></span>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

<?= '</script>' ?>


<!-- dependency components -->
<?php template('vue-components/medialist/medialist') ?>
