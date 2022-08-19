<template>
  <div class="relative">
    <div
      class="absolute left-0 h-10 w-10 flex items-center justify-center"
    >
      <span class="text-xl u-ic u-ic-search" />
    </div>
    <input
      v-model="query"
      class="placeholder:text-neutral-400 hover:border-blue-500 h-10 px-10"
      type="search"
      placeholder="Nach Bibelantworten suchen ..."
    >
    <button
      v-if="query"
      class="absolute right-0 h-10 w-10"
      @click="query = ''"
    >
      ×
    </button>
  </div>
</template>

<script>
// Debounced search widget
// https://www.algolia.com/doc/guides/building-search-ui/going-further/improve-performance/vue/?client=Vue+3#debouncing

import { connectSearchBox } from 'instantsearch.js/es/connectors';
import { createWidgetMixin } from 'vue-instantsearch/vue3/es';
import _debounce from 'lodash/debounce'

export default {
  mixins: [createWidgetMixin({ connector: connectSearchBox })],
  props: {
    delay: { type: Number, default: 600 },
  },
  data: () => ({
    timerId: null,
    localQuery: '',
  }),
  computed: {
    query: {
      get () {
        return this.localQuery
      },
      set (val) {
        this.localQuery = val
        this.debouncedQuery(val)
      },
    },
  },
  methods: {
    debouncedQuery: _debounce(function (val) {
      this.state.refine(val)
    }, 300),
  },
};
</script>
