<?php
use function Tonik\Theme\App\template;
use function Tonik\Theme\App\config;
$options = isset($options) ? json_encode($options) : '{}';
$params = isset($params) ? json_encode($params) : '{}';
?>

<!-- Vue livestream dropdown root component -->
<div id="<?= $id ?>"
  class="<?= $style_modifier ?>"
  data-vue="livestream-dropdown"
  data-options='<?= $options ?>'
  data-params='<?= $params ?>'
></div>

