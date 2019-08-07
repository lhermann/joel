<?php
use function Tonik\Theme\App\template;
$json_options = isset($options) ? str_replace('"', "'", json_encode($options)) : '{}';
$json_params = isset($params) ? str_replace('"', "'", json_encode($params)) : '{}';
?>

<!-- Vue events root component -->
<div
    id="<?= $id ?>"
    class="<?= $style_modifier ?>"
    data-vue="events"
    test="<?= $json_options ?>"
    :init="init(<?= $json_options ?>, <?= $json_params ?>)"
>


    <events :params="params" />

</div>

