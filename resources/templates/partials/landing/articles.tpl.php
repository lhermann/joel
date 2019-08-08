<?php
use function Tonik\Theme\App\template;
if(!isset($args)) $args = [];

$query = new WP_Query( ['posts_per_page' => 3] );
?>

<section class="c-section c-section--alt <?= $style_modifier ?>">
    <div class="o-wrapper">

      <h2>
          <span class="u-text-middle">Artikel</span>
          <a class="c-btn c-btn--tiny c-btn--subtle u-text u-ml-"
              href="<?= get_permalink( get_page_by_path( 'artikel' ) ) ?>">
              Alle Artikel anzeigen
              <span class="u-ic-arrow_forward"></span>
          </a>
      </h2>

      <div class="o-grid ">
          <div class="o-grid__item o-grid__item--hero">

              <?php if ($query->have_posts()): $query->the_post(); ?>

                  <?php template('partials/post/excerpt', [
                      'style_modifier' => 'c-excerpt--expand',
                      'hero' => true
                  ]) ?>

              <?php endif ?>

          </div>
          <div class="o-grid__item">

              <?php if ($query->have_posts()): $query->the_post(); ?>

                  <?php template('partials/post/excerpt', [
                      'style_modifier' => 'c-excerpt--expand',
                      'hero' => false
                  ]) ?>

              <?php endif ?>

          </div>
          <div class="o-grid__item">

              <?php if ($query->have_posts()): $query->the_post(); ?>

                  <?php template('partials/post/excerpt', [
                      'style_modifier' => 'c-excerpt--expand',
                      'hero' => false
                  ]) ?>

              <?php endif ?>

          </div>
      </div>

    </div>
</section>
