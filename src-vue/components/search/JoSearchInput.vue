<template>
  <div class="relative">
    <div class="algolia-search-icon">
      <span class="u-ic u-ic-search" />
    </div>
    <input
      v-model="query"
      class="c-search-bar__input"
      type="search"
      :placeholder="placeholder"
      autofocus
    >
    <button
      v-if="query"
      class="absolute right-0 h-10 w-10"
      @click="query = ''"
    >
      &times;
    </button>
  </div>
</template>

<script>
import { connectSearchBox } from 'instantsearch.js/es/connectors'
import { createWidgetMixin } from 'vue-instantsearch/vue3/es'
import _debounce from 'lodash/debounce'

export default {
  mixins: [createWidgetMixin({ connector: connectSearchBox })],
  props: {
    placeholder: { type: String, default: 'Suchen ...' },
  },
  data () {
    return {
      localQuery: '',
    }
  },
  computed: {
    query: {
      get () {
        return this.localQuery
      },
      set (val) {
        this.localQuery = val
        this.debouncedRefine(val)
      },
    },
  },
  watch: {
    'state.query' (val) {
      // Sync from instantsearch state (e.g. URL routing sets initial query)
      if (val !== this.localQuery) {
        this.localQuery = val
      }
    },
  },
  methods: {
    debouncedRefine: _debounce(function (val) {
      this.state.refine(val)
    }, 300),
  },
}
</script>
