<?php
use function Tonik\Theme\App\template;

$query = new WP_Query( ['posts_per_page' => 3] );
?>
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
