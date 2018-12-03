<?php
use Tonik\Theme\App\Store;
if( Store::isset_then_set('vue/slider/nav') ) return;
?>

<!-- template for the navigation component -->
<?= '<script type="text/x-template" id="slider-nav-component">' ?>

    <li :class="css" v-on:click="changeSlide">
        <button class="c-btn c-btn--dark c-slider__btn">
            <div class="c-slider__btn__fill"></div>
        </button>
    </li>

<?= '</script>' ?>
