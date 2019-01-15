<?php
use function Tonik\Theme\App\template;

global $wp_query;
?>

<?php get_header() ?>

<?php template('partials/header') ?>

<main role="main" class="o-wrapper o-wrapper--slim u-pv++">

    <header class="u-pv+">
        <h1>404 Seite nicht gefunden</h1>
    </header>

</main>

<?php get_footer() ?>
