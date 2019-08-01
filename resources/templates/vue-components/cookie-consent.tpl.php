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
        <div class="o-flex o-flex--middle o-flex--small o-flex--nowrap">
            <div v-if="!doNotTrack" class="o-flex__item">
                Joel Media Ministry e.V. verwendet Cookies. Manche Cookies sind für die Grundfunktionen dieser Seite, andere erfassen wie du diese Seite verwendest mithilfe von Matomo.
                Siehe unsere <a class="c-link c-link--dotted" href="<?= home_url( '/datenschutzerklaerung/' ) ?>">Datenschutzerklärung</a>.
            </div>
            <div v-else class="o-flex__item">
                Die "Do Not Track"-Einstellung deines Browsers ist aktiv. Nur notwendige Cookies werden gesetzt.
                Weitere Infos in der <a class="c-link c-link--dotted" href="<?= home_url( '/datenschutzerklaerung/' ) ?>">Datenschutzerklärung</a>.
            </div>
            <div class="o-flex__spacer"></div>
            <div v-if="!doNotTrack" class="o-flex__item">
                <button @click="deny" class="c-link c-link--dotted" style="white-space: nowrap;">
                    Nur notwendige Cookies erlauben
                </button>
            </div>
            <div class="o-flex__item">
                <button @click="allow" class="c-btn c-btn--small c-btn--green">OK</button>
            </div>
        </div>
    </div>
</div>

