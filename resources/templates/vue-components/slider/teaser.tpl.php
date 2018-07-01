<?php
use function AppTheme\template;
use AppTheme\Store;
if( Store::isset_then_set('vue-slider-teaser-component') ) return;
?>


<!-- template for the teaser component -->
<?= '<script type="text/x-template" id="slider-teaser-component">' ?>

    <div class="c-slider__teaser-container u-hidden-until@tablet">
        <div class="o-wrapper u-h-100">
            <div class="c-slider__teaser c-collapsible" :class="{'is-collapsed': teaserCollapsed}">
                <div class="c-collapsible__header">
                    <div class="c-collapsible__title u-h5">
                        Neue Videos
                    </div>
                    <div class="c-collapsible__btn">
                        <button class="c-btn c-btn--subtle c-btn--square u-defocus"
                            @click="onCollapseClick">
                            <span class="u-ic-unfold_less"
                                v-show="teaserCollapsed">
                            </span>
                            <span class="u-ic-unfold_more"
                                v-show="!teaserCollapsed">
                            </span>
                        </button>
                    </div>
                </div>
                <div class="c-collapsible__body u-center u-top u-ph">

                    <medialist-component
                        class="u-1/1"
                        :init-options="options"
                        :init-params="params"
                    />

                </div>
            </div>
        </div>
    </div>

<?= '</script>' ?>


<!-- dependency components -->
<?php template('vue-components/medialist/medialist') ?>
