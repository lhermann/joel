<?php
use function Tonik\Theme\App\template;
use function Tonik\Theme\App\config;
use function Tonik\Theme\App\asset_path;

$speakers = wp_get_object_terms( get_the_ID(), 'speakers' );
$series = wp_get_object_terms( get_the_ID(), 'series' )[0];
$topics = str_replace(
  '<a',
  '<a class="c-link c-link--dotted c-link--white"',
  get_the_term_list(
    get_the_ID(),
    'topics',
    '<span class="u-mh--">&middot;</span>'.__('Topics', config('textdomain')).': ',
    ', '
  )
);
$youtube = get_field("youtube_video");
if($youtube) {
  // Remove width and height
  $youtube = preg_replace('/width="\d+"\s*height="\d+"\s*/i', '', $youtube);
  // Extract URL
  preg_match_all('/src="(.+?)"/', $youtube, $matches);
  $url = count($matches) > 0 ? $matches[1][0] : '';
  // Use youtube-nocookie.com
  $url = str_replace("www.youtube.com", "www.youtube-nocookie.com", $url);
  // Add &modestbranding=1
  $url .= "&modestbranding=1";
  // Re-insert URL
  $youtube = preg_replace('/src=".+?"/', "src=\"$url\"", $youtube);
}
?>

<?php get_header() ?>

<main role="main" class="u-mb u-mb++@tablet">

  <?php if(have_posts()): the_post() ?>

  <div id="head"
    class="c-header-bg c-header-bg--offset u-pt+"
    style="background-image: url(<?= asset_path('images/header-blue.svg') ?>);">
    <div class="o-wrapper">
      <div class="u-box-center u-3/4@tablet u-2/3@desktop">


        <header class="u-white u-mb u-mb+@tablet">
          <h1 class="u-responsive u-mb0"><?php the_title() ?></h1>
          <?php foreach ($speakers as $i => $speaker): ?>
            <a class="c-link c-link--dotted c-link--white"
              href="<?= get_term_link( $speaker ) ?>"
            >
              <?= $speaker->name ?>
            </a><?= $i !== count($speakers)-1 ? ',' : '' ?>
          <?php endforeach ?>
          <span class="u-mh--">&middot;</span>
          <a class="c-link c-link--dotted c-link--white"
            href="<?= get_term_link( $series ) ?>"
          >
            <?= $series->name ?>
          </a>
          <?= $topics ?>
        </header>

        <div class="o-ratio o-ratio--16:9 u-shadow ">
          <?php if ($youtube): ?>
          <?= $youtube ?>
          <?php else: ?>
          <iframe id="player"
            class="o-ratio__content c-player"
            src="<?= config('url-prefix')['embed'].'0'.get_the_ID() ?>"
            frameborder="0"
            allowfullscreen>
          </iframe>
          <?php endif ?>
        </div>


      </div>
    </div>
  </div>

  <div class="o-wrapper">
    <div class="u-box-center u-3/4@tablet u-2/3@desktop">

      <section id="infobox" class="u-pv">

        <?php template('partials/recordings-meta', ['youtube' => $youtube]) ?>

      </section>

      <hr>

      <?php if ($podcast = get_field('podcast')): ?>
      <section id="podcast">

        <h2 class="u-h5 u-mb-">
          Podcast
          <small class="u-muted u-lighter u-ml-">
            Diese Aufnahme ist teil eines Podcasts
          </small>
        </h2>

        <?php template('partials/podcast',
          ['podcast' => get_term($podcast)]
        ) ?>

        <hr>

      </section>
      <?php endif ?>


      <?php if (get_the_content()): ?>
      <section id="content">

        <div class="u-mv+ u-text-center">

          <?php if (strlen(get_the_content()) > 600): ?>

            <div id="show-more" data-vue="toggle">
              <div class="c-wp-styles u-text-left u-mb-" :class="{ 'u-show-more': !toggled }">
                <?php the_content() ?>
              </div>

              <button class="c-btn c-btn--ghost c-btn--subtle c-btn--tiny u-ph" @click="toggle">
                <template v-if="toggled">
                  <span class="u-ic-minus"></span> verbergen
                </template>
                <template v-else>
                  <span class="u-ic-plus"></span> alles anzeigen
                </template>
              </button>
            </div>

          <?php else: ?>

            <div class="u-text-left u-mb-">
              <?php the_content() ?>
            </div>

          <?php endif ?>

        </div>

        <hr class="u-mv+">

      </section>
      <?php endif ?>

      <section id="next-video">

        <!-- <h2 class="u-h5 u-mb--">Nächstes Video</h2> -->
        <h2 class="u-h5 u-mb--">Weitere Aufnahmen</h2>

        <p class="u-text- u-muted">
          Serie:
          <a class="c-link c-link--dotted u-ml--"
            href="<?= get_term_link( $series ) ?>">
            <?= $series->name ?>
          </a>
        </p>

        <?php template('vue-components/medialist', [
          'id' => 'medialist-next-video',
          'options' => [
            'pagination' => 'normal'
          ],
          'params' => [
            'per_page' => 7,
            'series' => $series->term_id,
            'exclude' => get_the_ID()
          ]
        ]) ?>

        <hr class="u-mv+">

      </section>

      <?php if (false): ?>
      <section id="recommended">

        <?php template('vue-components/medialist', [
          'id' => 'medialist-recommended',
          'options' => [
            'pagination' => 'minimal'
          ],
          'params' => [
            'per_page' => 7,
            'series' => $series->term_id,
            'exclude' => get_the_ID()
          ]
        ]) ?>

        <hr class="u-mv+">

      </section>
      <?php endif ?>

      <section id="license">

        <div class="o-flag ">
          <div class="o-flag__img">
            <img src="<?= asset_path('images/licenses/by-nc-nd.eu.svg') ?>"
              style="max-width: 100px;">
          </div>
          <div class="o-flag__body u-text--">
            <h2 class="u-text u-mb0 u-muted">Lizenz</h2>
            Copyright ©2017 Joel Media Ministry e.V.
            <br>Dieses Werk ist lizenziert unter einer Creative Commons Namensnennung - Nicht kommerziell - Keine Bearbeitungen 4.0 International Lizenz.
          </div>
        </div>

    </div>
  </div>

  <?php endif ?>

</main>

<?php get_footer() ?>
