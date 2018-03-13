<?php
/*
<mediaitem-component
    :key="i"
    :item="item"
/>
*/
$params = str_replace('"', "'", json_encode($params));
?>

<!-- Vue medialist root component -->
<div id="<?= $id ?>"
    class="<?= $style_modifier ?> jsMedialist"
    :params="setParams(<?= $params ?>)">

    <ul class="c-medialist">

        <li class="c-medialist__item" v-for="(item, i) in recordings">

            <mediaitem-component
                :key="i"
                :item="item"
            />

        </li>

    </ul>

    <!-- pagination -->

</div>


<!-- template for the mediaitem component -->
<script type="text/x-template" id="mediaitem-component">

    <div class="o-media c-mediaitem"
        :class="['c-mediaitem--' + type]"
        :href="item.link">

        <a class="c-mediaitem__link" :href="item.link"></a>

        <div class="o-media__img c-mediaitem__img">
            <a class="c-mediaitem__imglink" :href="item.link">
                <img :src="item.thumbnail">
                <div class="c-mediaitem__length">
                    <div>{{ item.length }}</div>
                </div>
            </a>
        </div>

        <div class="o-media__body c-mediaitem__body">
            <a class="c-mediaitem__title u-truncate" :href="item.link" v-html="title"></a>
            <ul class="c-mediaitem__meta u-truncate">
                <li v-for="(speaker, i) in item.speakers">
                    <a :href="speaker.link">{{ speaker.name }}</a><template v-if="i !== item.speakers.length - 1">,</template>
                </li>
                <li>
                    {{ item.date_human }}
                </li>
            </ul>
        </div>

    </div>

</script>


<?php /*
<!-- template for the mediaitem component -->
<script type="text/x-template" id="mediaitem-component">

    <div class="o-media c-mediaitem"
        :class="['c-mediaitem--' + type]"
        :href="item.link">

        <a class="c-mediaitem__link" :href="item.link"></a>

        <div class="o-media__img c-mediaitem__img">
            <a class="c-mediaitem__imglink" :href="item.link">
                <img :src="item.thumbnail">
                <div class="c-mediaitem__length">
                    <div>{{item.length}}</div>
                </div>
            </a>
        </div>

        <div class="o-media__body c-mediaitem__body">
            <a class="c-mediaitem__title u-truncate" :href="item.link" v-html="title"></a>
            <ul class="c-mediaitem__meta u-truncate">
                <li v-for="(speaker, i) in item.speakers">
                    <a :href="speaker.link">{{speaker.name}}</a><template v-if="i !== item.speakers.length - 1">,</template>
                </li>
                <li>
                    {{item.date_human}}
                </li>
            </ul>
        </div>

    </div>

</script>
*/ ?>


