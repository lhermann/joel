<template>
  <div id="algolia-search-box">
    <input
      v-model="query"
      type="search"
      :placeholder="placeholder"
      autofocus
    >
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
