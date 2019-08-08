<?php
use function Tonik\Theme\App\theme;
use function Tonik\Theme\App\template;
use function Tonik\Theme\App\config;
?>

<?php get_header() ?>

<main role="main">

    <?php foreach (config('landing') as $section => $args): ?>

        <?php print('<pre>'); var_dump($args); print('</pre>'); ?>

        <?php template(
            'partials/landing/'.$section,
            [ 'args' => $args ]
        ) ?>

    <?php endforeach ?>

</main>

<?php get_footer() ?>
