<?php
use function Tonik\Theme\App\template;
use function Tonik\Theme\App\config;

// Hide footer for full-height chat layout
add_filter('body_class', function ($classes) {
  $classes[] = 'is-fullscreen-app';
  return $classes;
});
?>

<?php get_header() ?>

<main class="flex flex-col flex-1 min-h-0" role="main">

  <?php template('vue-components/main', [
    'component' => 'JoStudyCenter',
    'id' => 'JoStudyCenter',
    'style_modifier' => 'flex flex-col flex-1 min-h-0',
    'options' => [
      'api_url' => config('study-center-url'),
    ],
  ]) ?>

</main>

<?php get_footer() ?>
