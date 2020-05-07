<template>
  <div class="c-slider">
    <div v-if="controls" class="c-slider__control c-slider__control--left">
      <button
        v-on:click="manuallyChangeSlide('previous')"
        class="c-btn c-btn--dark c-btn--bigicon c-btn--square c-btn--right c-slider__btn"
      >
        <span class="u-ic-keyboard_arrow_left"></span>
      </button>
    </div>

    <div v-if="controls" class="c-slider__control c-slider__control--right">
      <button
        v-on:click="manuallyChangeSlide('next')"
        class="c-btn c-btn--dark c-btn--bigicon c-btn--square c-btn--left c-slider__btn"
      >
        <span class="u-ic-keyboard_arrow_right"></span>
      </button>
    </div>

    <ul v-if="controls" class="c-slider__nav">
      <slider-nav
        v-for="(slide, i) in slides"
        :key="i"
        :index="i"
        :current-slide="currentSlide"
        @update:currentSlide="manuallyChangeSlide"
      />
    </ul>

    <ul class="c-slider__list">
      <slide
        v-for="(slide, i) in slides"
        :key="i"
        :slide="slide"
        :order="slideOrder[i]"
        :current-slide="currentSlide"
        :slide-transition="slideTransition"
      />
    </ul>

    <slider-teaser
      v-if="teaser"
      :slides="slides"
      :current-slide="currentSlide"
      :auto="isAutomatic"
    />
  </div>
</template>

<script>
import axios from 'axios'
import Slide from './slide.vue'
import SliderNav from './nav.vue'
import SliderTeaser from './teaser.vue'

export default {
  name: 'Slider',
  components: { Slide, SliderNav, SliderTeaser },
  props: {
    mode: String,
    slideDuration: Number,
    slideTransition: Number,
    teaser: Boolean,
    id: Number,
    params: Object,
  },
  data () {
    return {
      slides: [],
      count: 0,
      currentSlide: 0,
      slideOrder: [],
      slideChangeCount: 0,
      interval: null,
      isAutomatic: true,
    }
  },
  computed: {
    containerClass () {
      return {
        'is-automatic': this.isAutomatic,
      }
    },
    controls () {
      return this.count > 1
    },
  },
  methods: {
    changeSlide (i) {
      if (this.currentSlide !== i) this.slideChangeCount++
      this.currentSlide = i
      for (var i = 0; i < this.slideOrder.length; i++) {
        // decrease order of all slides
        this.slideOrder[i]--
      }
      // give current slide the highest order
      this.slideOrder[this.currentSlide] = this.count
    },
    manuallyChangeSlide (i) {
      this.stopAutomaticSlideChange()
      switch (i) {
        case 'next':
          this.nextSlide()
          break
        case 'previous':
          this.previousSlide()
          break
        default:
          this.changeSlide(i)
      }
    },
    nextSlide () {
      if (this.currentSlide >= this.count - 1) {
        this.changeSlide(0)
      } else {
        this.changeSlide(this.currentSlide + 1)
      }
    },
    previousSlide () {
      if (this.currentSlide <= 0) {
        this.changeSlide(this.count - 1)
      } else {
        this.changeSlide(this.currentSlide - 1)
      }
    },
    stopAutomaticSlideChange () {
      clearInterval(this.interval)
      this.isAutomatic = false
    },
  },
  mounted () {
    if (this.id) {
      axios
        .get(`wp/v2/slides/${this.id}`)
        .then(response => {
          this.slides = [response.data]
          this.count = 1
          this.slides[0].index = 0
          this.slideOrder[0] = 1
          this.$emit('loaded')
        })
        .catch(error => console.log({ error }))
    } else {
      axios
        .get('wp/v2/slides', this.params)
        .then(response => {
          this.slides = response.data
          this.count = response.data.length
          for (var i = 0; i < this.slides.length; i++) {
            this.slides[i].index = i
            this.slideOrder[i] = this.count - i
          }
          this.$emit('loaded')
        })
        .catch(error => console.log({ error }))
    }

    if (this.mode !== 'none') {
      this.isAutomatic = true
      this.interval = setInterval(
        function (context) {
          context.nextSlide()
          if (
            context.mode === 'initial' &&
            context.slideChangeCount >= context.count
          ) {
            context.stopAutomaticSlideChange()
          }
        },
        this.slideDuration,
        this,
      )
    }
  },
}
</script>
