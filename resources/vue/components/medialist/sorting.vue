<template>
  <div>
    <span>Sortieren:</span>
    <button
      ref="reference"
      id="sorting-dropdown"
      class="c-btn c-btn--dropdown c-btn--tiny c-btn--secondary u-ml--"
      v-on:click="onButtonClick"
      aria-haspopup="true"
      :aria-expanded="show ? 'true' : 'false'"
    >
      {{ currentOption.label }}
    </button>

    <ul
      ref="popper"
      class="c-dropdown c-dropdown--round"
      :class="{ 'is-visible': fadeIn }"
      v-show="show"
      aria-labelledby="sorting"
    >
      <li
        v-for="option in options"
        class="c-dropdown__item"
        :class="{ 'is-active': isCurrent(option) }"
      >
        <button class="c-dropdown__link" v-on:click="onSelectOption(option)">
          {{ option.label }}
        </button>
      </li>
    </ul>
  </div>
</template>

<script>
import Popper from 'popper.js'

export default {
  name: 'SortingComponent',
  props: ['options', 'currentOption'],
  data () {
    return {
      popper: null,
      show: false,
      fadeIn: false,
    }
  },
  computed: {},
  methods: {
    isCurrent (option) {
      return this.currentOption && option.label === this.currentOption.label
    },
    onButtonClick () {
      this.show = !this.show
      this.$nextTick(() => {
        this.popper.scheduleUpdate()
      })
    },
    onSelectOption (option) {
      this.$emit('select', option)
      this.show = false
    },
    onOutsideClick (event) {
      if (this.show && !this.$el.contains(event.target)) {
        this.show = false
      }
    },
  },
  watch: {
    show (newState) {
      setTimeout(() => {
        this.fadeIn = newState
      }, 20)
    },
  },
  mounted () {
    this.popper = new Popper(this.$refs.reference, this.$refs.popper, {
      placement: 'bottom-end',
    })
  },
  created () {
    document.addEventListener('click', this.onOutsideClick)
  },
  destroyed () {
    document.removeEventListener('click', this.onOutsideClick)
  },
}
</script>
