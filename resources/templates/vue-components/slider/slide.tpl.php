<?php
use AppTheme\Store;
if( Store::isset_then_set('vue-slider-slide-component') ) return;
?>

<!-- template for the slide component -->
<?= '<script type="text/x-template" id="slide-component">' ?>

    <li class="c-slider__item" :class="liCss" :style="liStyle">
        <div class="c-slide" :class="css" :style="style">

            <a v-if="acf.url" :href="acf.url" class="c-slide__link"></a>

            <div class="o-wrapper c-slide__wrapper">

                <div class="c-slide__body u-1/2@tablet u-1/1">

                    <h1 v-if="acf.show_title" class="u-mb-">{{ title.rendered }}</h1>

                    <div v-if="acf.use_custom_html" v-html="acf.custom_html"></div>
                    <p v-else class="u-lead u-muted">{{ acf.lead_text }}</p>

                    <a v-if="acf.button_text" class="c-btn c-btn--dark c-slide__btn" :href="acf.url">
                        {{ acf.button_text }} <span class="u-ic-arrow_forward"></span>
                    </a>

                </div>

                <div v-if="media" class="c-slide__media u-1/2@tablet u-hidden-until@tablet">
                    <div class="o-ratio o-ratio--16:9 u-box-shadow">
                        <a v-if="media.acf_fc_layout === 'image'" class="o-ratio__content" :href="acf.url">
                            <img :src="media.image.sizes['360p']">
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </li>

<?= '</script>' ?>
