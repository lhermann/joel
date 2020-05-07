<?php
use function Tonik\Theme\App\template;
use function Tonik\Theme\App\Helper\idHash;
if(!isset($args)) $args = [];
?>


<?php
  if ( eo_get_events([
    'showpastevents' => false,
    'post_status' => 'publish',
    'event-category' => $args['event-category'],
    'numberposts' => 1
  ]) ):
?>
<section id="event-list" class="c-section u-pt u-pb0 <?= $style_modifier ?>">

  <div class="o-wrapper">

    <h2 class="u-h3 u-mb-">
        <span class="u-text-middle u-mr-">Veranstaltungen</span>
    </h2>

    <?php template('vue-components/main', array_merge(
      [
        'component' => 'JoEvents',
        'id' => idHash($args),
      ],
      $args
    )); ?>

  </div>

  <hr class="u-m0 u-mt" />

</section>
<?php endif ?>
