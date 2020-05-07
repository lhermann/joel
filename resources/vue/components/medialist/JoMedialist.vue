<template>
  <div>
    <header v-if="title || tabs || sorting" class="u-pv">
      <div class="o-flex o-flex--middle o-flex--between">
        <div v-if="title" class="o-flex__item">
          <h2 class="u-mb0">{{ title }}</h2>
        </div>
        <div class="o-flex__item">
          <div class="c-spinner" v-show="isLoading"></div>
        </div>
        <div v-if="sorting" class="o-flex__item u-text-right">
          <JoSorting
            :options="sortingOptions"
            :current-option="currentSortingOption"
            @select="onSelectOption"
          />
        </div>
      </div>
    </header>

    <ul class="c-medialist" :class="medialistClass">
      <li
        v-for="item in items"
        :key="item.id"
        class="c-medialist__item"
      >
        <JoMediaitem :item="item" />
      </li>
    </ul>

    <!-- pagination -->
    <JoPagination
      v-if="pagination &amp;&amp; totalPages > 1"
      class="u-mv+"
      :total="total"
      :per-page="perPage"
      :total-pages="totalPages"
      :current-page="currentPage"
      :verbosity="pagination"
      :is-loading="isLoading"
      @to-page="onChangePage"
    />
  </div>
</template>

<script>
import axios from 'axios'
import JoMediaitem from './JoMediaitem.vue'
import JoPagination from '../pagination/JoPagination.vue'
import JoSorting from './JoSorting.vue'
import get from 'lodash/get'

export default {
  name: 'MedialistComponent',
  components: {
    JoMediaitem,
    JoPagination,
    JoSorting,
  },
  props: {
    options: Object,
    params: Object,
  },
  data () {
    return {
      namespace: 'wp/v2/',
      route: 'recordings',
      columns: 1,
      items: [],
      localParams: {
        page: 1,
        per_page: 10,
        order: null,
        orderby: null,
      },
      title: false,
      tabs: false,
      sorting: false,
      pagination: false, // false, 'minimal', 'normal', 'verbose'
      total: 0,
      totalPages: 1,
      isLoading: false,
      currentSortingOption: { label: '' },
    }
  },
  computed: {
    perPage () {
      return this.localParams.per_page
    },
    currentPage () {
      return this.localParams.page
    },
    medialistClass () {
      if (this.columns > 1) return ['c-medialist--' + this.columns + 'col']
      return []
    },
    sortingOptions () {
      const recordings = this.route === 'recordings'
      const speakers = this.route === 'speakers'
      const options = [
        {
          label: 'Neue zuerst',
          params: {
            order: 'desc',
            orderby: recordings ? 'date' : 'id',
          },
        },
        {
          label: 'Alte zuerst',
          params: {
            order: 'asc',
            orderby: recordings ? 'date' : 'id',
          },
        },
        {
          label: speakers ? 'Vorname (A-Z)' : 'Alphabetisch (A-Z)',
          params: {
            order: 'asc',
            orderby: recordings ? 'title' : 'name',
          },
        },
        speakers
          ? {
            label: 'Nachname (A-Z)',
            params: {
              order: 'asc',
              orderby: 'lastname',
            },
          }
          : {
            label: 'Alphabetisch (Z-A)',
            params: {
              order: 'desc',
              orderby: recordings ? 'title' : 'name',
            },
          },
      ]
      if (this.route === 'recordings') {
        options.push({
          label: 'Beliebtheit',
          namespace: 'wordpress-popular-posts/v1/',
          route: 'popular-posts',
          params: {
            post_type: 'recordings',
            range: 'all',
            limit: 50,
          },
        })
      } else {
        options.push({
          label: 'Anzahl Videos',
          params: {
            order: 'desc',
            orderby: 'count',
          },
        })
      }
      return options
    },
  },
  methods: {
    setOptions (payload) {
      for (const option in payload) {
        this[option] = payload[option]
      }
    },
    setParams (payload) {
      Object.assign(this.localParams, payload)
      this.setInitialSortingOption()
    },
    /**
     * Choose a sorting option that fits with the params
     */
    setInitialSortingOption () {
      const i = this.sortingOptions.findIndex(
        opt =>
          opt.params.orderby === get(this.localParams, 'orderby') &&
          opt.params.order === (get(this.localParams, 'order') || 'asc'),
      )
      this.currentSortingOption =
        i >= 0 ? this.sortingOptions[i] : this.sortingOptions[0]
    },
    requestRecordings () {
      this.isLoading = true
      // create 10 dummys
      const namespace = this.currentSortingOption.namespace || this.namespace
      const route = this.currentSortingOption.route || this.route
      const params = Object.assign(
        {},
        this.localParams,
        this.sorting ? this.currentSortingOption.params : {},
      )
      this.items = Array(this.localParams.per_page).fill(this.createDummy())
      axios
        .get(namespace + route, { params })
        .then(response => {
          this.isLoading = false
          this.items = response.data
          this.totalPages = parseInt(response.headers['x-wp-totalpages'])
          this.total = parseInt(response.headers['x-wp-total'])
        })
        .catch(error => {
          this.isLoading = false
          console.log(error)
        })
    },
    createDummy () {
      return {
        name: 'Loading ...',
        type: this.route,
        count: 'XXX',
        dummy: true,
      }
    },
    onChangePage (page) {
      this.localParams.page = page
      this.requestRecordings()
    },
    onSelectOption (option) {
      this.currentSortingOption = option
      this.localParams.page = 1
      this.requestRecordings()
    },
  },
  mounted () {
    this.setOptions(this.options)
    this.setParams(this.localParams)
    this.requestRecordings()
  },
}
</script>
