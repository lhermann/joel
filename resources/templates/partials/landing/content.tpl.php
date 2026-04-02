<?php
use function Tonik\Theme\App\template;
if(!isset($args)) $args = [];
?>

<?php if (have_posts() && get_post()->post_content): the_post(); ?>
  <section id="landing-content" class="c-section <?= $style_modifier ?>">
      <div class="max-w-screen-xl mx-auto px-4 md:px-8 c-article">

          <?= the_content(); ?>

      </div>
  </section>
<?php endif; ?>
