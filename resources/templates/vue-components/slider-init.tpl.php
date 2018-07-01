<?php
use function AppTheme\template;
$options = isset($options) ? str_replace('"', "'", json_encode($options)) : '{}';
?>

<!-- Vue slider root component -->
<div v-cloak
    id="<?= $id ?>"
    class=" <?= $style_modifier ?> jsSlider"
    :init="init(<?= $options ?>)"
>

    <slider-component
        :mode="mode"
        :slide-duration="slideDuration"
        :slide-transition="slideTransition"
        :teaser="teaser"
    />

</div>

<!-- dependency components -->
<?php template('vue-components/slider/slider') ?>

