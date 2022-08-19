<template>
  <!-- eslint-disable vue/no-v-html -->
  <article v-if="props.item">
    <h2 class="text-lg leading-snug mb-2">
      <a :href="props.item.permalink" v-html="title" />
    </h2>
    <p class="mb-0" v-html="snippet" />
  </article>
</template>

<script setup>
import { computed } from 'vue'
import _get from 'lodash/get'

const props = defineProps({
  item: { type: Object, default: null },
})

const title = computed(() => {
  return _get(props.item, '_snippetResult.post_title.value') || ''
})

const snippet = computed(() => {
  return _get(props.item, '_snippetResult.content.value') || ''
})
</script>
