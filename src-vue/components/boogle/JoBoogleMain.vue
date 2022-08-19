<template>
  <AisInstantSearch
    class="mb-12"
    index-name="wp_posts_answer"
    :search-client="searchClient"
  >
    <!-- Search Box -->
    <JoBoogleSearch class="mb-12" />
    <!-- <AisSearchBox /> -->

    <!-- TODO: Choose indexes -->
    <!-- <div class="text-sm text-neutral-600 space-x-2">
      <label for="include-text">
        <input id="include-text" type="checkbox" checked> Texte
      </label>
      <label for="include-videos">
        <input id="include-videos" type="checkbox"> Videos
      </label>
    </div> -->

    <!-- Search Results -->
    <AisHits
      :class-names="{
        'ais-Hits': '',
        'ais-Hits-list': 'm-0 space-y-6',
        'ais-Hits-item': '',
      }"
    >
      <template v-slot:item="{ item }">
        <JoBoogleAnswerItem :item="item" />
      </template>
    </AisHits>
  </AisInstantSearch>
  <div>

    <!-- Answers -->
    <JoBoogleAnswerList />
  </div>
</template>

<script setup>
import JoBoogleSearch from './JoBoogleSearch.vue'
import JoBoogleAnswerList from './JoBoogleAnswerList.vue'
import JoBoogleAnswerItem from './JoBoogleAnswerItem.vue'
import { AisInstantSearch, AisSearchBox, AisHits } from 'vue-instantsearch/vue3/es';
import algoliasearch from 'algoliasearch/lite';
// import { onMounted, ref } from 'vue'

const props = defineProps({
  options: { type: Object, default: () => ({}) },
  params: { type: Object, default: () => ({}) },
})

const searchClient = algoliasearch(
  props.options.application_id,
  props.options.search_api_key,
)
// console.log({ searchClient, props })
// const searchClient = ref(null)

// onMounted(() => {
//   searchClient.value = algoliasearch(
//     props.options.application_id,
//     props.options.search_api_key,
//   )
// })

</script>

<style>

</style>
