 <?php
use function Tonik\Theme\App\template;
if(!isset($args)) $args = [];
?>

<section class="c-section <?= $style_modifier ?>">

    <?php template('partials/newsletter-form') ?>

</section>
