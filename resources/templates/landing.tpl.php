<?php
use function Tonik\Theme\App\theme;
use function Tonik\Theme\App\template;
use function Tonik\Theme\App\config;
?>

<?php get_header() ?>

<main role="main">

  <?php
    foreach (config('landing') as $section => $args):

    $style_modifier = "";
    if(array_key_exists('style_modifier', $args)) {
      $style_modifier = $args['style_modifier'];
      unset($args['style_modifier']);
    }
  ?>

    <?php //print('<pre>'); var_dump($args); print('</pre>'); ?>

    <?php template('partials/landing/'.$section, [
      'style_modifier' => $style_modifier,
      'args' => $args
    ]) ?>

  <?php endforeach ?>

</main>

<?php get_footer() ?>
