<?php
use function AppTheme\template;
use AppTheme\Store;
if( Store::isset_then_set('vue-slider-component') ) return;
?>

<!-- template for the slide component -->
<?= '<script type="text/x-template" id="slider-component">' ?>

    <div class="c-slider">

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

            <slider-nav-component v-for="(slide, i) in slides"
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
                :slide-transition="slideTransition"
            />

        </ul>

        <slider-teaser-component v-if="teaser" />

    </div>

<?= '</script>' ?>

<!-- dependency components -->
<?php template('vue-components/slider/slide') ?>
<?php template('vue-components/slider/nav') ?>
<?php template('vue-components/slider/teaser') ?>
