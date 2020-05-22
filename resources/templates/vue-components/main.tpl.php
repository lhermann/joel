<?php
use function Tonik\Theme\App\template;
use function Tonik\Theme\App\config;
$id = isset($id) ? $id : $component;
$component = isset($component) ? $component : $id;
$options = isset($options) ? json_encode($options) : '{}';
$params = isset($params) ? json_encode($params) : '{}';
?>

<!-- Vue mountpoint -->
<div
  id="<?= $id ?>"
  class="<?= $style_modifier ?>"
  data-vue="<?= $component ?>"
  data-options='<?= $options ?>'
  data-params='<?= $params ?>'
></div>
