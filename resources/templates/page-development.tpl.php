<?php
use function Tonik\Theme\App\template;
?>

<?php get_header() ?>

<main role="main" class="o-wrapper u-pv++">

  <?php template('vue-components/events', [
      'id' => 'vue-events',
      'params' => ['numberposts' => 3, 'event-category' => 'veranstaltung']
  ]) ?>


  <ul class="o-list-bare o-flex o-flex--large o-flex-wrap">
    <li class="o-flex__item u-1/3">
      <div class="c-card c-card--clickable">
        <div class="c-card__content">
          <div class="o-media">
            <div class="o-media__img">
              <img class="u-rounded" style="max-width: 120px; max-height: 120px;" src="http://localhost:8081/wp-content/uploads/2019/08/ENAD-2019-Conference-Flyer-212x300.jpg" alt="" srcset="http://localhost:8081/wp-content/uploads/2019/08/ENAD-2019-Conference-Flyer-212x300.jpg 212w, http://localhost:8081/wp-content/uploads/2019/08/ENAD-2019-Conference-Flyer-85x120.jpg 85w, http://localhost:8081/wp-content/uploads/2019/08/ENAD-2019-Conference-Flyer-768x1087.jpg 768w, http://localhost:8081/wp-content/uploads/2019/08/ENAD-2019-Conference-Flyer-724x1024.jpg 724w" sizes="(max-width: 212px) 100vw, 212px">
            </div>
            <div class="o-media__body">
              <div class="u-text- u-medium u-brand">Samstag, 3. November 2019</div>
              <div class="u-text+ u-semibold">
                <a href="http://localhost:8081/aufnahmen/mit-gott-leben-sprueche-111-15/" class="c-link">
                  ENAD Conference 2019
                </a>
              </div>
              <div class="u-text- u-muted"><span class="u-ic-room"></span> Michelsberg</div>
              <div class="u-text-- u-mt-">
                The most appropriate use for get_posts is to create an array of posts based on a set of parameters. It retrieves a list of recent posts or posts matching this criteria. get_posts can also be used to create Multiple Loops, though a more direct reference to WP_Query using new WP_Query is preferred in this case.
              </div>
            </div>
          </div>
        </div>
      </div>
    </li>
  </ul>

</main>

<?php get_footer() ?>
