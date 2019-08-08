<?php
use function Tonik\Theme\App\template;
use function Tonik\Theme\App\Helper\idHash;
if(!isset($args)) $args = [];
?>

<section id="slider" class="c-section c-section--flush <?= $style_modifier ?>">

    <?php template(
        'vue-components/slider',
        array_merge(
          [ 'id' => idHash($args) ],
          $args
        )
    ); ?>

</section>
