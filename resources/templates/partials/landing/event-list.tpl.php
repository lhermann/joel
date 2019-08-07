<?php
use function Tonik\Theme\App\template;
?>
<div class="<?= $style_modifier ?>">
    <h2 class="u-h3 u-mb-">
        <span class="u-text-middle u-mr-">Veranstaltungen</span>
    </h2>

    <?php template('vue-components/events', [
        'id' => 'vue-events',
        'params' => ['numberposts' => 3, 'event-category' => 'veranstaltung']
    ]) ?>
</div>
