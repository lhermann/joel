<?php
use function Tonik\Theme\App\template;
$terms = get_terms( ['taxonomy' => 'podcasts', 'orderby' => 'term_id', 'order' => 'DESC'] );
$terms = array_filter( $terms, fn($term) => !get_field('hidden', 'podcasts_' . $term->term_id) );
?>

<?php get_header() ?>

<main role="main" class="o-wrapper o-wrapper--slim u-pt+">

    <h2>Podcasts</h2>

    <p>Podcasts können mit <a href="https://www.apple.com/de/apple-podcasts/" target="_blank">Apple Podcasts</a>, <a href="https://www.spotify.com/" target="_blank">Spotify</a> und vielen weiteren Podcast-Apps abonniert werden. Einmal abonniert lädt die App automatisch alle neuen Aufnahmen herunter, sobald diese verfügbar sind.</p>

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
