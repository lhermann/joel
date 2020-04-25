<?php
use function Tonik\Theme\App\template;
$options = isset($options) ? str_replace('"', "'", json_encode($options)) : '{}';
$params = isset($params) ? str_replace('"', "'", json_encode($params)) : '{}';
?>

<!-- Vue livestream meta root component -->
<div id="<?= $id ?>"
  class="c-cookie-consent <?= $style_modifier ?>"
  data-vue="cookie-consent"
  :init="init(<?= $options ?>)"
  v-cloak
  v-if="!hasCookie"
>
  <div class="o-wrapper">
    <div class="o-flex o-flex--tiny o-flex--middle o-flex--wrap">
      <div class="o-flex__item u-1/1 u-2/3@desktop">
        <template v-if="!doNotTrack">
          <?php bloginfo('name') ?> verwendet Cookies. Manche Cookies sind für die Grundfunktionen dieser Seite, andere erfassen wie du diese Seite verwendest mithilfe von Matomo.
        </template>
        <template v-else>
          Die "Do Not Track"-Einstellung deines Browsers ist aktiv. Nur notwendige Cookies werden gesetzt.
        </template>
          Weitere Infos in der <a class="c-link c-link--dotted" href="<?= home_url( '/datenschutzerklaerung/' ) ?>">Datenschutzerklärung</a>.
      </div>
      <div class="o-flex__item u-1/1 u-1/3@desktop u-text-right">
        <button v-if="!doNotTrack" class="c-link c-link--dotted u-nowrap" @click="deny">
          Nur notwendige Cookies erlauben
        </button>
        <button class="c-btn c-btn--small c-btn--green u-ml--" @click="allow">
          OK
        </button>
      </div>
    </div>


    <!-- <div class="o-flex o-flex--middle o-flex--small o-flex--wrap">
      <div class="o-flex__item u-shrink u-2/3@tablet">
        <template v-if="!doNotTrack">
          <?php bloginfo('name') ?> verwendet Cookies. Manche Cookies sind für die Grundfunktionen dieser Seite, andere erfassen wie du diese Seite verwendest mithilfe von Matomo.
        </template>
        <template v-else>
          Die "Do Not Track"-Einstellung deines Browsers ist aktiv. Nur notwendige Cookies werden gesetzt.
        </template>
          Weitere Infos in der <a class="c-link c-link--dotted" href="<?= home_url( '/datenschutzerklaerung/' ) ?>">Datenschutzerklärung</a>.
      </div>
      <div class="o-flex__spacer u-hidden-until@tablet"></div>
      <div v-if="!doNotTrack" class="o-flex__item u-shrink-0">
        <button @click="deny" class="c-link c-link--dotted" style="white-space: nowrap;">
          Nur notwendige Cookies erlauben
        </button>
      </div>
      <div class="o-flex__item u-shrink-0">
        <button @click="allow" class="c-btn c-btn--small c-btn--green">OK</button>
      </div>
    </div> -->
  </div>
</div>

