<?php
use function Tonik\Theme\App\template;
if(!isset($args)) $args = [];
?>

<?php if (have_posts() && get_post()->post_content): the_post(); ?>
  <section id="landing-content" class="c-section <?= $style_modifier ?>">
      <div class="o-wrapper c-article">

          <?= the_content(); ?>

      </div>
  </section>
<?php endif; ?>
