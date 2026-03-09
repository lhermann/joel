<template>
  <AisInstantSearch
    :index-name="indexName"
    :search-client="searchClient"
    :routing="routing"
  >
    <div class="o-layout o-layout--large u-break-wrapper@until-tablet">
      <!-- Facets sidebar -->
      <aside class="o-layout__item u-border-right u-pr u-1/3 u-hidden-until@tablet">
        <AisMenu
          attribute="post_type_label"
          :sort-by="['isRefined:desc', 'count:desc', 'name:asc']"
          :limit="10"
        >
          <template v-slot="{ items, refine }">
            <section v-if="items.length" class="ais-facets">
              <h3 class="u-h4 u-mb-">Filter</h3>
              <ul>
                <li v-for="item in items" :key="item.value">
                  <a
                    class="u-truncate"
                    :class="{ 'is-active': item.isRefined }"
                    href="#"
                    @click.prevent="refine(item.value)"
                  >
                    {{ item.label }} ({{ item.count }})
                  </a>
                </li>
              </ul>
            </section>
          </template>
        </AisMenu>

        <AisMenu
          attribute="taxonomies.speakers"
          :sort-by="['count:desc']"
          :limit="10"
        >
          <template v-slot="{ items, refine }">
            <section v-if="items.length" class="ais-facets">
              <h3 class="u-h4 u-mb-">Sprecher</h3>
              <ul>
                <li v-for="item in items" :key="item.value">
                  <a
                    class="u-truncate"
                    :class="{ 'is-active': item.isRefined }"
                    href="#"
                    @click.prevent="refine(item.value)"
                  >
                    {{ item.label }} ({{ item.count }})
                  </a>
                </li>
              </ul>
            </section>
          </template>
        </AisMenu>

        <AisMenu
          attribute="taxonomies.series"
          :sort-by="['count:desc']"
          :limit="10"
        >
          <template v-slot="{ items, refine }">
            <section v-if="items.length" class="ais-facets">
              <h3 class="u-h4 u-mb-">Serien</h3>
              <ul>
                <li v-for="item in items" :key="item.value">
                  <a
                    class="u-truncate"
                    :class="{ 'is-active': item.isRefined }"
                    href="#"
                    @click.prevent="refine(item.value)"
                  >
                    {{ item.label }} ({{ item.count }})
                  </a>
                </li>
              </ul>
            </section>
          </template>
        </AisMenu>

        <AisHierarchicalMenu
          :attributes="[
            'taxonomies_hierarchical.topics.lvl0',
            'taxonomies_hierarchical.topics.lvl1',
            'taxonomies_hierarchical.topics.lvl2',
          ]"
          separator=" > "
          :sort-by="['count:desc']"
          :limit="10"
        >
          <template v-slot="{ items, refine }">
            <section v-if="items.length" class="ais-facets">
              <h3 class="u-h4 u-mb-">Themen</h3>
              <JoSearchTopicTree :items="items" :refine="refine" />
            </section>
          </template>
        </AisHierarchicalMenu>
      </aside>

      <!-- Main content -->
      <section class="o-layout__item u-2/3@tablet u-1/1">
        <header class="u-mb+">
          <JoSearchInput placeholder="Archiv durchsuchen ..." />
          <AisStats>
            <template v-slot="{ nbHits, processingTimeMS }">
              <div class="u-mt-" style="font-size: .85em; color: #888;">
                {{ nbHits }} Treffer in {{ processingTimeMS }}ms
              </div>
            </template>
          </AisStats>
        </header>

        <AisHits>
          <template v-slot:item="{ item }">
            <JoSearchHit :item="item" />
          </template>
        </AisHits>

        <AisConfigure :hits-per-page.camel="10" />

        <AisPagination
          class="u-mt+"
          :class-names="{
            'ais-Pagination-list': 'o-list-inline o-list-inline--1px',
            'ais-Pagination-item': 'o-list-inline__item c-btn c-btn--secondary c-btn--small c-btn--edgy c-btn--square',
            'ais-Pagination-item--selected': 'is-active',
            'ais-Pagination-item--firstPage': 'c-btn--left',
            'ais-Pagination-item--lastPage': 'c-btn--right',
          }"
        />
      </section>
    </div>
  </AisInstantSearch>
</template>

<script setup>
import JoSearchInput from './JoSearchInput.vue'
import JoSearchHit from './JoSearchHit.vue'
import JoSearchTopicTree from './JoSearchTopicTree.vue'
import {
  AisInstantSearch,
  AisHits,
  AisStats,
  AisConfigure,
  AisPagination,
  AisMenu,
  AisHierarchicalMenu,
} from 'vue-instantsearch/vue3/es'
import algoliasearch from 'algoliasearch/lite'
import { history } from 'instantsearch.js/es/lib/routers'

const props = defineProps({
  options: { type: Object, default: () => ({}) },
  params: { type: Object, default: () => ({}) },
})

const indexName = 'wp_searchable_posts'

const searchClient = algoliasearch(
  props.options.application_id,
  props.options.search_api_key,
)

const routing = {
  router: history({
    windowTitle ({ query }) {
      return query ? `Suche: ${query}` : 'Suche'
    },
    createURL ({ qsModule, routeState, location }) {
      const qs = qsModule.stringify(routeState, { addQueryPrefix: true })
      return `${location.pathname}${qs}`
    },
    parseURL ({ qsModule, location }) {
      return qsModule.parse(location.search, { ignoreQueryPrefix: true })
    },
  }),
  stateMapping: {
    stateToRoute (uiState) {
      const state = uiState[indexName] || {}
      return { s: state.query || '' }
    },
    routeToState (routeState) {
      return {
        [indexName]: {
          query: routeState.s || '',
        },
      }
    },
  },
}
</script>
