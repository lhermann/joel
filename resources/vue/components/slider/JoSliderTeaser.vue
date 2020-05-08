<template>
  <div class="c-slider__teaser-container u-hidden-until@tablet">
    <div class="o-wrapper u-h100">
      <div
        class="c-slider__teaser c-collapsible"
        :class="{ 'is-collapsed': collapsed }"
      >
        <div class="c-collapsible__header">
          <button
            class="c-btn c-btn--subtle c-btn--edgy u-defocus u-ph u-1/1"
            @click="onCollapseClick"
            ref="button"
          >
            <div class="o-flex o-flex--middle o-flex--between">
              <h5 class="c-collapsible__title u-m0">
                Neue Aufnahmen
              </h5>
              <span class="u-ic-keyboard_arrow_down" v-show="collapsed"></span>
              <span class="u-ic-keyboard_arrow_up" v-show="!collapsed"></span>
            </div>
          </button>
        </div>
        <transition name="collapse">
          <div v-show="!collapsed" class="c-collapsible__body u-ph u-pv0">
            <JoMedialist
              class="u-1/1"
              :options="options"
              :params="params"
            />

            <div class="c-slider__teaser-btn u-text-center">
              <a class="c-btn c-btn--small c-btn--subtle" href="/aufnahmen/">
                Alle Videos anzeigen
                <span class="u-ic-arrow_forward"></span>
              </a>
            </div>
          </div>
        </transition>
      </div>
    </div>
  </div>
</template>

<script>
import JoMedialist from '../medialist/JoMedialist'
import get from 'lodash/get'

export default {
  components: { JoMedialist },
  props: {
    slides: Array,
    currentSlide: Number,
    auto: Boolean,
  },
  data: function () {
    return {
      collapsed: false,
      options: {},
      params: {
        per_page: 5,
        series_exclude: 368, // mit Gott leben
      },
      userIntervened: false,
    }
  },
  methods: {
    onCollapseClick () {
      this.collapsed = !this.collapsed
      this.userIntervened = true
      this.$refs.button.blur()
    },
  },
  watch: {
    slides (slides) {
      this.collapsed =
        get(slides[this.currentSlide], 'acf.slide_type') !== 'teaser'
    },
    currentSlide (index) {
      if (this.userIntervened) return
      if (get(this.slides[index], 'acf.slide_type') === 'teaser') { this.collapsed = false } else if (!this.auto && index === 0) this.collapsed = false
      else this.collapsed = true
    },
  },
}
</script>
