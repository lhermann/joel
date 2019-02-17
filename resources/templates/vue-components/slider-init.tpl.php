<?php
use function Tonik\Theme\App\template;
use function Tonik\Theme\App\asset_path;
$options = isset($options) ? str_replace('"', "'", json_encode($options)) : '{}';
?>

<!-- Vue slider root component -->
<div
    id="<?= $id ?>"
    class="<?= $style_modifier ?>"
    data-vue="slider"
    :init="init(<?= $options ?>)"
>

    <!-- Placeholder -->
    <div v-show="!loaded" class="c-slider">
        <ul class="c-slider__list">
            <li class="c-slider__item">
                <div class="c-slide c-slide-- c-slide--white" style="background-image: url('<?= asset_path('images/slide-dark-blue.svg') ?>');">
                    <a href="https://www.joelmedia.de/aufnahmen/" class="c-slide__link"></a>
                    <div class="o-wrapper c-slide__wrapper">
                        <div class="c-slide__body u-1/2">
                            <img src="https://www.joelmedia.de/wordpress/wp-content/themes/joel/public/images/jm-logo-white-01.svg" class="c-logo c-logo--hero" alt="Joel Media">
                            <h1 class="u-mb0 u-mt-">Joel Media Ministry e.V.</h1>
                            <p class="u-muted"><small>... das ewige Evangelium für Stuttgart, Deutschland und die Welt</small></p>
                            <p class="u-lead u-muted">Wöchentlich neue Videos über die Bibel,<br>Gesundheit und Zeitgeschehen</p>
                            <a href="https://www.joelmedia.de/aufnahmen/" class="c-btn c-btn--dark c-slide__btn">Erster Besuch? Hier geht's los<span class="u-ic-arrow_forward"></span></a>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>

    <slider-component
        v-show="loaded"
        :mode="mode"
        :slide-duration="slideDuration"
        :slide-transition="slideTransition"
        :teaser="teaser"
        @loaded="loaded = true"
    />

</div>

<!-- dependency components -->
<?php template('vue-components/slider/slider') ?>

