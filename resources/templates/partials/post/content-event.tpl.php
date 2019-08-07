<?php
use function Tonik\Theme\App\template;
use function Tonik\Theme\App\config;
?>
<article class="c-article">

    <header class="c-article__header">

        <h1 class="u-roboto"><?php the_title(); ?></h1>

        <table class="c-article__meta u-mb" style="width: auto;">
          <tr>
            <td class="u-pr-"><span class="u-ic-date"></span> Beginn:</td>
            <td><?= eo_get_the_start('l, j. F Y' ); ?></td>
          </tr><tr>
            <td class="u-pr-"><span class="u-ic-date"></span> Ende:</td>
            <td><?= eo_get_the_end('l, j. F Y' ); ?></td>
          </tr><tr>
            <td class="u-pr-"><span class="u-ic-room"></span> Ort:</td>
            <td>Michelsberg Tagungsstätte</td>
          </tr>
        </table>

        <div class="u-hidden-visually">
            <time datetime="<?php the_time('c'); ?>"><?php the_date(); ?></time>
        </div>

    </header>


    <div class="c-article__body c-wp-styles">

        <?php the_content(); ?>


        <?php if(eo_venue_has_latlng()): ?>
          <h2><span class="u-ic-room"></span> <?php eo_venue_name() ?></h2>
          <p>
            <?php foreach (eo_get_venue_address() as $key => $value) {
              if($value)
                printf("%s%s",
                  $value,
                  !in_array($key, ['postcode', 'country']) ? ", " : " "
                );
            } ?>
          </p>
          <?php echo eo_get_venue_map(); ?>
        <?php endif; ?>

    </div>

    <hr class="u-mt" />

    <?php template('partials/landing/event-list', [
      'style_modifier' => 'u-pb+'
    ]) ?>

</article>
