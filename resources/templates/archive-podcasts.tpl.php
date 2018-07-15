<?php
use function Tonik\Theme\App\template;

$terms = get_terms( ['taxonomy' => 'podcasts'] );

?>

<?php get_header() ?>
<?php template('partials/header') ?>

<main role="main" class="o-wrapper o-wrapper--slim u-pt+">

    <h2>Podcasts</h2>

    <p>Podcasts können mit <a>iTunes</a>, <a>Stitcher</a> und <a>vielen anderen Apps</a> abboniert werden. Einmal abboniert lädt die App automatisch alle neue Aufnahmen herunter sobald diese verfügbar sind.</p>

    <ul class="o-list-bare">

        <?php foreach ($terms as $i => $term): ?>

            <li>

                <hr/>

                <?php template('partials/podcast', ['podcast' => $term]) ?>

            </li>

        <?php endforeach ?>

    </ul>

</main>

<?php get_footer() ?>
