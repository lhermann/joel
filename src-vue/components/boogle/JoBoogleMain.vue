<template>
  <AisInstantSearch
    class="mb-12"
    index-name="wp_posts_answer"
    :search-client="searchClient"
  >
    <!-- Search Box -->
    <div class="mb-12">
      <JoBoogleSearch class="mb-2" />

      <!-- TODO: Choose indexes -->
      <!-- <div class="text-sm text-neutral-600 space-x-2">
        <label for="include-text">
          <input id="include-text" type="checkbox" checked> Texte
        </label>
        <label for="include-videos">
          <input id="include-videos" type="checkbox"> Videos
        </label>
      </div> -->

      <AisStats class="text-xs">
        <template v-slot:default="{ page, nbPages, nbHits }">
          {{ nbHits }} Ergebnisse, Seite {{ page + 1 }}/{{ nbPages }}
        </template>
      </AisStats>
    </div>
    <!-- <AisSearchBox /> -->


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

    <AisConfigure
      v-bind="{
        hitsPerPage: 10,
      }"
    />
    <AisPagination
      class="mt-12"
      :class-names="{
        'ais-Pagination': '',
        'ais-Pagination--noRefinement': '',
        'ais-Pagination-list': 'flex justify-center space-x-px m-0',
        'ais-Pagination-item': 'text-neutral-800 hover:text-neutral-200 bg-neutral-200 hover:bg-neutral-700 transition-color font-normal',
        'ais-Pagination-item--firstPage': 'rounded-l',
        'ais-Pagination-item--lastPage': 'rounded-r',
        'ais-Pagination-item--previousPage': '',
        'ais-Pagination-item--nextPage': '',
        'ais-Pagination-item--page': '',
        'ais-Pagination-item--selected': '!text-neutral-200 !bg-neutral-700',
        'ais-Pagination-item--disabled': '!text-neutral-400 hover:!text-neutral-400 hover:!bg-gray-200',
        'ais-Pagination-link': 'block w-9 h-9 leading-9 text-center text-inherit hover:text-inherit focus:text-inherit',
      }"
    />
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
import {
  AisInstantSearch,
  AisHits,
  AisStats,
  AisConfigure,
  AisPagination,
} from 'vue-instantsearch/vue3/es';
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

</script>

<style>

</style>
