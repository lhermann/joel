<template>
  <nav
    class="o-layout o-layout--auto o-layout--middle"
    role="navigation"
    aria-label="Pagination Navigation"
  >
    <div class="o-layout__item">
      <ol class="o-list-inline o-list-inline--1px">
        <li class="o-list-inline__item">
          <button
            class="c-btn c-btn--secondary c-btn--small c-btn--left u-ph-"
            :class="{ 'c-btn--square': !verbose }"
            :disabled="currentPage <= 1 || isLoading"
            @click="previousPage"
          >
            <span class="u-ic-keyboard_arrow_left"></span>
            <span v-if="verbose" class="u-hidden-until@tablet">
              Vorherige Seite
            </span>
          </button>
        </li>
        <li
          v-for="n in minimal ? [] : buttons"
          :key="n"
          class="o-list-inline__item u-hidden-until@tablet"
        >
          <button
            v-if="n == 'left' || n == 'right'"
            class="c-btn c-btn--secondary c-btn--small c-btn--edgy
                      c-btn--square"
            :class="{ 'is-active': n === currentPage }"
            :disabled="isLoading"
            @click="changeRange(n)"
          >
            ...
          </button>
          <button
            v-else
            class="c-btn c-btn--secondary c-btn--small c-btn--edgy
                      c-btn--square"
            :class="{ 'is-active': n === currentPage }"
            :disabled="isLoading"
            @click="toPage(n)"
          >
            {{ n }}
          </button>
        </li>
        <li class="o-list-inline__item">
          <button
            class="c-btn c-btn--secondary c-btn--small c-btn--right u-ph-"
            :class="{ 'c-btn--square': !verbose }"
            :disabled="currentPage >= totalPages || isLoading"
            @click="nextPage"
          >
            <span v-if="verbose" class="u-hidden-until@tablet">
              Nächste Seite
            </span>
            <span class="u-ic-keyboard_arrow_right"></span>
          </button>
        </li>
      </ol>
      <!--end c-primary-nav__list-->
    </div>

    <div class="o-layout__item" v-show="isLoading">
      <div class="c-spinner"></div>
    </div>

    <div class="o-layout__item" v-if="!minimal">
      {{ pageRangeDisplay }}
    </div>
  </nav>
</template>

<script>
import min from 'lodash/min'
import max from 'lodash/max'
import clamp from 'lodash/clamp'

export default {
  name: 'PaginationComponent',
  props: [
    'total',
    'perPage',
    'totalPages',
    'currentPage',
    'verbosity',
    'isLoading',
  ],
  data () {
    return {
      range: 8,
      rangeOffset: 0,
      minOffset: 0,
    }
  },
  computed: {
    verbose () {
      return this.verbosity === 'verbose'
    },
    minimal () {
      return this.verbosity === 'minimal'
    },
    buttons () {
      if (this.totalPages <= this.range) {
        return this.totalPages
      } else {
        const buttons = [1]
        if (this.rangeOffset > 0) buttons.push('left')
        for (let i = 0; buttons.length < this.range - 1; i++) {
          buttons.push(this.rangeOffset + 2 + i)
        }
        if (buttons[this.range - 2] !== this.totalPages - 1) {
          buttons[this.range - 2] = 'right'
        }
        buttons.push(this.totalPages)
        return buttons
      }
    },
    pageRangeDisplay () {
      const firstOfRange = 1 + (this.currentPage - 1) * this.perPage
      const lastOfRange =
        this.total < this.perPage
          ? this.total
          : this.currentPage * this.perPage
      return `${firstOfRange} - ${lastOfRange} von ${this.total}`
    },
    maxOffset () {
      return max([this.totalPages - this.range + 1, 0])
    },
  },
  watch: {
    currentPage (newValue, oldValue) {
      this.setRangeOffset(newValue)
    },
  },
  mounted () {
    this.setRangeOffset(this.currentPage)
  },
  methods: {
    setRangeOffset (currentPage) {
      this.rangeOffset = clamp(
        currentPage - Math.floor(this.range / 2),
        this.minOffset,
        this.maxOffset,
      )
    },
    toPage (n) {
      document.activeElement.blur()
      if (n !== this.currentPage) this.$emit('to-page', n)
    },
    nextPage () {
      this.toPage(this.currentPage + 1)
    },
    previousPage () {
      this.toPage(this.currentPage - 1)
    },
    changeRange (direction) {
      document.activeElement.blur()
      const shift = this.range - 4
      if (direction === 'left') {
        this.rangeOffset = max([this.rangeOffset - shift, this.minOffset])
      } else {
        this.rangeOffset = min([this.rangeOffset + shift, this.maxOffset])
      }
    },
  },
}
</script>
