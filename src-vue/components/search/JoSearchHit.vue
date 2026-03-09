<template>
  <!-- eslint-disable vue/no-v-html -->
  <article class="c-medialist__item">
    <div
      class="o-media c-mediaitem"
      :class="'c-mediaitem--' + (item.post_type === 'recordings' ? 'video' : 'post')"
    >
      <a class="c-mediaitem__link" :href="item.permalink" :title="item.post_title" />

      <div class="o-media__img c-mediaitem__img">
        <a class="c-mediaitem__imglink" :href="item.permalink">
          <img
            v-if="thumbnail"
            :src="thumbnail"
            :alt="item.post_title"
            width="256"
            height="144"
            class="text-transparent"
          >
          <div v-if="badge" class="c-mediaitem__length">
            <div>{{ badge }}</div>
          </div>
        </a>
      </div>

      <div class="o-media__body c-mediaitem__body">
        <h3 class="c-mediaitem__title" v-html="highlightedTitle" />
        <ul v-if="item.post_type === 'recordings'" class="c-mediaitem__meta u-truncate">
          <li v-if="item.speakers" v-html="item.speakers" />
          <li v-if="item.views">{{ item.views }} Klicks</li>
          <li v-if="item.date_human">{{ item.date_human }}</li>
        </ul>
        <div
          v-else-if="snippet"
          class="c-mediaitem__content"
          v-html="snippet"
        />
      </div>
    </div>
  </article>
</template>

<script setup>
import { computed } from 'vue'
import _get from 'lodash/get'

const props = defineProps({
  item: { type: Object, required: true },
})

const highlightedTitle = computed(() => {
  return _get(props.item, '_highlightResult.post_title.value', props.item.post_title || '')
})

const snippet = computed(() => {
  return _get(props.item, '_snippetResult.content.value', '')
})

const thumbnail = computed(() => {
  if (props.item.post_type === 'recordings') {
    return props.item.thumbnail || ''
  }
  return _get(props.item, 'images.thumbnail.url', '')
})

const badge = computed(() => {
  if (props.item.post_type === 'recordings') {
    return props.item.length || ''
  }
  return props.item.post_date_formatted || ''
})
</script>
