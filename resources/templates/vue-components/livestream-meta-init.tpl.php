<?php
use function Tonik\Theme\App\template;
$options = isset($options) ? str_replace('"', "'", json_encode($options)) : '{}';
$params = isset($params) ? str_replace('"', "'", json_encode($params)) : '{}';
?>

<!-- Vue livestream meta root component -->
<div id="<?= $id ?>"
    class="o-flex o-flex--middle o-flex--nowrap <?= $style_modifier ?>"
    data-vue="livestream-meta"
    :init="init(<?= $options ?>, <?= $params ?>)"
>

    <div class="o-flex__item u-lead" style="flex-shrink: 0">
        <stream-check :stream="options.stream">
            <template slot-scope="props">
                <span class="c-dot"
                    :class="{'c-dot--green': props.live, 'is-loading': props.loading}">
                </span>
                <strong v-if="props.live" v-cloak>ON AIR</strong>
                <strong v-else class="u-muted">OFFLINE</strong>
            </template>
        </stream-check>
        <!-- <span class="u-hidden-from@desktop"><br>0:24:13</span> -->
    </div>

    <!-- <div class="o-flex__item u-text-center u-lead u-pl- u-hidden-until@desktop">
        livestream already online since 0:24:13
    </div> -->

    <div v-cloak v-if="event" class="o-flex__item u-pl@desktop">
        <div class="o-flag">
            <div class="o-flag__img" v-html="event.thumbnail"></div>
            <div class="o-flag__body">
                <strong class="u-lead">{{event.post_title}}</strong>
                <br>Heute von {{start}} bis {{finish}}
            </div>
        </div>
    </div>
    <div v-cloak v-else-if="loading" class="o-flex__item u-ph+">
        <div class="c-spinner"></div>
    </div>
    <div v-cloak v-else class="o-flex__item u-pl@desktop">
        <strong class="u-muted">Keine Aufnahmen für heute geplant</strong>
    </div>

    <!-- <div class="o-flex__item u-text-right u-lead">
        <strong>20 <span class="u-hidden-until@tablet">Zuschauer</span></strong>
    </div> -->
</div>

<!-- dependency components -->
<?php template('vue-components/livestream/streamcheck') ?>

