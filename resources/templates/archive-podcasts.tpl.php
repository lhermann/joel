<?php
use function Tonik\Theme\App\template;
$terms = get_terms( ['taxonomy' => 'podcasts'] );
?>

<?php get_header() ?>

<main role="main" class="o-wrapper o-wrapper--slim u-pt+">

    <h2>Podcasts</h2>

    <p>Podcasts können mit <a href="https://www.apple.com/de/itunes/" target="_blank">iTunes</a>, <a href="https://www.spotify.com/de/" target="_blank">Spotify</a>, <a href="https://www.stitcher.com/" target="_blank">Stitcher</a> und <a href="http://www.spiegel.de/netzwelt/apps/die-besten-podcast-apps-fuer-ios-und-android-a-993542.html" target="_blank">vielen weiteren Apps</a> abboniert werden. Einmal abboniert lädt die App automatisch alle neue Aufnahmen herunter sobald diese verfügbar sind.</p>

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
