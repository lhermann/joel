<?php
use Tonik\Theme\App\Store;
use function Tonik\Theme\App\config;
if( Store::isset_then_set('vue/medialist/mediaitem') ) return;
?>

<!-- template for the mediaitem component -->
<?= '<script type="text/x-template" id="mediaitem-component">' ?>

    <div class="o-media c-mediaitem"
        :class="['c-mediaitem--' + item.type, {'c-mediaitem--dummy': isDummy}]"
        :href="item.link">

        <a class="c-mediaitem__link" :href="item.link"></a>

        <div class="o-media__img c-mediaitem__img">
            <a v-if="item.type === 'topics'"
                class="c-mediaitem__imglink" :href="item.link">
                <span class="u-ic-folder"></span>
            </a>
            <a v-else class="c-mediaitem__imglink" :href="item.link">
                <img :src="item.thumbnail">
                <div class="c-mediaitem__length">
                    <div v-html="length"></div>
                </div>
            </a>
        </div>

        <div class="o-media__body c-mediaitem__body">
            <a class="c-mediaitem__title u-truncate" :href="item.link" v-html="title"></a>
            <ul class="c-mediaitem__meta u-truncate">
                <li v-if="subtopics">
                    {{ subtopics }}
                </li>
                <li v-if="!isRecording">
                    {{ item.count }}
                    <template v-if="item.count === 1"><?= __('Recording', config('textdomain')) ?></template>
                    <template v-else><?= __('Recordings', config('textdomain')) ?></template>
                </li>
                <li v-if="item.speakers">
                    <template v-for="(speaker, i) in item.speakers">
                        <a :href="speaker.link">{{ speaker.name }}</a><!--
                        --><template v-if="i !== item.speakers.length - 1">, </template>
                    </template>
                </li>
                <li v-if="item.views">
                    {{ item.views }} <?= __('clicks', config('textdomain')) ?>
                </li>
                <li v-if="item.series_count">
                    {{ item.series_count }}
                    <template v-if="item.series_count === 1"><?= _x('Series', 'singular', config('textdomain')) ?></template>
                    <template v-else><?= _x('Series', 'plural', config('textdomain')) ?></template>
                </li>
                <li v-if="isRecording">
                    {{ item.date_human }}
                </li>
            </ul>
        </div>

    </div>

<?= '</script>' ?>
