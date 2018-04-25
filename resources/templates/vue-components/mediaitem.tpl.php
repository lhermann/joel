<?php
use AppTheme\Store;
if( Store::isset_then_set('vue-mediaitem-component') ) return;
?>

<!-- template for the mediaitem component -->
<script type="text/x-template" id="mediaitem-component">

    <div class="o-media c-mediaitem"
        :class="['c-mediaitem--' + item.type]"
        :href="item.link">

        <a class="c-mediaitem__link" :href="item.link"></a>

        <div class="o-media__img c-mediaitem__img">
            <a class="c-mediaitem__imglink" :href="item.link">
                <img :src="item.thumbnail">
                <div class="c-mediaitem__length">
                    <div v-html="length"></div>
                </div>
            </a>
        </div>

        <div class="o-media__body c-mediaitem__body">
            <a class="c-mediaitem__title u-truncate" :href="item.link" v-html="title"></a>
            <ul class="c-mediaitem__meta u-truncate">
                <li v-if="item.type === 'series'">
                    {{ item.count }} Videos
                </li>
                <li>
                    <template v-for="(speaker, i) in item.speakers">
                        <a :href="speaker.link">{{ speaker.name }}</a><!--
                        --><template v-if="i !== item.speakers.length - 1">, </template>
                    </template>
                </li>
                <li v-if="isRecording">
                    {{ item.date_human }}
                </li>
            </ul>
        </div>

    </div>

</script>
