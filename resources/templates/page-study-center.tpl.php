<?php
use function Tonik\Theme\App\template;
use function Tonik\Theme\App\config;

?>

<?php get_header() ?>

<main class="flex flex-col c-study-center" role="main">

  <?php template('vue-components/main', [
    'component' => 'JoStudyCenter',
    'id' => 'JoStudyCenter',
    'style_modifier' => 'flex flex-col flex-1',
    'options' => [
      'api_url' => config('study-center-url'),
      'recording_count' => (int) wp_count_posts('recordings')->publish,
    ],
  ]) ?>

</main>

<?php get_footer() ?>
