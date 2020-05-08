<template>
  <JoPagination
    class="u-mv+"
    :total="options.total"
    :per-page="options.perPage"
    :total-pages="options.totalPages"
    :current-page="options.currentPage"
    :verbosity="options.verbosity"
    @to-page="onPageClick"
  />
</template>

<script>
import JoPagination from './JoPagination'

export default {
  components: { JoPagination },
  props: {
    params: { type: Object, default: () => ({}) },
    options: { type: Object, default: () => ({}) },
  },
  methods: {
    onPageClick (page) {
      window.location = this.options.baseUrl + page
    },
  },
}
</script>
