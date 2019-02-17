<?php
use Tonik\Theme\App\Store;
if( Store::isset_then_set('vue/slider/slide') ) return;
?>

<!-- template for the slide component -->
<?= '<script type="text/x-template" id="slide-component">' ?>

    <li class="c-slider__item" :class="liCss" :style="liStyle">
        <div class="c-slide" :class="css" :style="slideStyle">

            <a v-if="acf.link" :href="acf.link.url" target="acf.link.target" class="c-slide__link"></a>

            <div class="o-wrapper c-slide__wrapper">

                <div class="c-slide__body u-1/2@tablet u-1/1">

                    <h1
                        v-if="acf.show_title"
                        class="u-mb-"
                        :style="{color: colors.title}"
                    >
                        {{ title.rendered }}
                    </h1>

                    <div
                        class="u-lead"
                        :class="{'u-muted': colors.theme === 'image'}"
                        :style="{color: colors.text}"
                        v-html="acf.content"
                    ></div>

                    <a
                        v-if="acf.link && acf.button_text"
                        class="c-btn c-btn--dark c-slide__btn"
                        :href="acf.link.url"
                        :target="acf.link.target"
                    >
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
