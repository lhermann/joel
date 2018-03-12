<?php
use function AppTheme\theme;
use function AppTheme\config;

if( !$slides = theme('slides') ) return;
$has_teaser = false;
?>


<!-- template for the navigation component -->
<script type="text/x-template" id="nav-component">
    <li :class="css" v-on:click="changeSlide">
        <button class="c-btn c-btn--dark c-slider__btn">
            <div class="c-slider__btn__fill"></div>
        </button>
    </li>
</script>

<!-- template for the slide component -->
<script type="text/x-template" id="slide-component">
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
</script>

<div id="<?= $id ?>"
    class="c-slider <?= $style_modifier ?> jsSlider"
    :class="containerClass"
    data-mode="<?= $mode ?>"
    data-duration="<?= $duration ?>">

    <div class="c-slider__control c-slider__control--left">
        <button v-on:click="manuallyChangeSlide('previous')"
            class="c-btn c-btn--dark c-btn--bigicon c-btn--square c-btn--right c-slider__btn">
            <span class="u-ic-keyboard_arrow_left"></span>
        </button>
    </div>

    <div class="c-slider__control c-slider__control--right">
        <button v-on:click="manuallyChangeSlide('next')"
            class="c-btn c-btn--dark c-btn--bigicon c-btn--square c-btn--left c-slider__btn">
            <span class="u-ic-keyboard_arrow_right"></span>
        </button>
    </div>

    <ul class="c-slider__nav">

        <nav-component v-for="(slide, i) in slides"
            :key="i"
            :index="i"
            :current-slide="currentSlide"
            v-on:clicked="manuallyChangeSlide"
        />

    </ul>

    <ul class="c-slider__list">

        <slide-component v-for="(slide, i) in slides"
            :key="i"
            :slide="slide"
            :order="slideOrder[i]"
            :current-slide="currentSlide"
        />

    </ul>

    <?php if ($has_teaser): ?>
        <div class="c-slider__teaser-container u-hidden-until@tablet">
            <div class="o-wrapper u-h-100">
                <div id="collapse-teaser" class="c-slider__teaser c-collapsible">
                    <div class="c-collapsible__header">
                        <div class="c-collapsible__title u-h5">
                            <?= __('New Recordings', config('textdomain')) ?>
                        </div>
                        <div class="c-collapsible__btn">
                            <button class="c-btn c-btn--blocky c-btn--square jsToggle"
                                data-target="#collapse-teaser"
                                data-class="is-collapsed">
                                <span class="u-ic-unfold_less"></span>
                                <span class="u-ic-unfold_more u-hidden"></span>
                            </button>
                        </div>
                    </div>
                    <div class="c-collapsible__body u-center">
                        organisms-medialist:u-1/1
                    </div>
                </div>
            </div>
        </div>
    <?php endif ?>

</div>

