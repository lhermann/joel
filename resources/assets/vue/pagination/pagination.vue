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
            v-on:click="previousPage"
          >
            <span class="u-ic-keyboard_arrow_left"></span>
            <span v-if="verbose" class="u-hidden-until@tablet">
              Vorherige Seite
            </span>
          </button>
        </li>
        <li
          v-if="!minimal"
          v-for="n in buttons"
          class="o-list-inline__item u-hidden-until@tablet"
        >
          <button
            v-if="n == 'left' || n == 'right'"
            class="c-btn c-btn--secondary c-btn--small c-btn--edgy
                      c-btn--square"
            :class="{ 'is-active': n === currentPage }"
            :disabled="isLoading"
            v-on:click="changeRange(n)"
          >
            ...
          </button>
          <button
            v-else
            class="c-btn c-btn--secondary c-btn--small c-btn--edgy
                      c-btn--square"
            :class="{ 'is-active': n === currentPage }"
            :disabled="isLoading"
            v-on:click="toPage(n)"
          >
            {{ n }}
          </button>
        </li>
        <li class="o-list-inline__item">
          <button
            class="c-btn c-btn--secondary c-btn--small c-btn--right u-ph-"
            :class="{ 'c-btn--square': !verbose }"
            :disabled="currentPage >= totalPages || isLoading"
            v-on:click="nextPage"
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
export default {
  name: "PaginationComponent",
  props: [
    "total",
    "perPage",
    "totalPages",
    "currentPage",
    "verbosity",
    "isLoading"
  ],
  data() {
    var range = 9;
    return {
      range: range,
      rangeCentre: Math.ceil(range / 2),
      rangeOffset: Math.floor(range / 2)
    };
  },
  computed: {
    verbose() {
      return this.verbosity === "verbose";
    },
    minimal() {
      return this.verbosity === "minimal";
    },
    buttons() {
      if (this.totalPages <= this.range) {
        return this.totalPages;
      } else {
        var arr = [1];
        for (var i = 1; i <= this.range - 2; i++) {
          arr.push(this.rangeCentre - this.rangeOffset + i);
        }
        if (arr[1] > 2) arr[1] = "left";
        if (arr[this.range - 2] < this.totalPages - 1)
          arr[this.range - 2] = "right";
        arr.push(this.totalPages);
        return arr;
      }
    },
    pageRangeDisplay() {
      let firstOfRange = 1 + (this.currentPage - 1) * this.perPage;
      let lastOfRange =
        this.total < this.perPage
          ? this.total
          : this.currentPage * this.perPage;
      return `${firstOfRange} - ${lastOfRange} von ${this.total}`;
    }
  },
  methods: {
    toPage(n) {
      document.activeElement.blur();
      if (n !== this.currentPage) this.$emit("to-page", n);
    },
    nextPage() {
      this.toPage(this.currentPage + 1);
    },
    previousPage() {
      this.toPage(this.currentPage - 1);
    },
    changeRange(direction) {
      document.activeElement.blur();
      if (direction === "left") {
        this.rangeCentre -= this.range - 4;
      } else {
        this.rangeCentre += this.range - 4;
      }
    }
  },
  watch: {
    // whenever question changes, this function will run
    currentPage(newValue, oldValue) {
      var offset = this.rangeOffset;
      if (newValue <= offset + 1) {
        this.rangeCentre = offset + 1;
      } else if (newValue > this.totalPages - offset) {
        this.rangeCentre = this.totalPages - offset;
      } else {
        this.rangeCentre = newValue;
      }
    }
  }
};
</script>
