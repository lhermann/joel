<?php
use function Tonik\Theme\App\template;
$options = isset($options) ? str_replace('"', "'", json_encode($options)) : '{}';
$params = isset($params) ? str_replace('"', "'", json_encode($params)) : '{}';
?>

<!-- Vue medialist root component -->
<section v-cloak id="<?= $id ?>"
    class="<?= $style_modifier ?>"
    data-vue="medialist"
    :init="init(<?= $options ?>, <?= $params ?>)">

    <medialist-component :init-options="options" :init-params="params" />

</section>

<!-- dependency components -->
<?php template('vue-components/medialist/medialist') ?>
