<template>
  <div class="c-slider">

    <div v-if="controls" class="c-slider__control c-slider__control--left">
      <button
        @click="manuallyChangeSlide('previous')"
        class="c-btn c-btn--dark c-btn--bigicon c-btn--square c-btn--right c-slider__btn"
      >
        <span class="u-ic-keyboard_arrow_left"></span>
      </button>
    </div>

    <div v-if="controls" class="c-slider__control c-slider__control--right">
      <button
        @click="manuallyChangeSlide('next')"
        class="c-btn c-btn--dark c-btn--bigicon c-btn--square c-btn--left c-slider__btn"
      >
        <span class="u-ic-keyboard_arrow_right"></span>
      </button>
    </div>

    <ul v-if="controls" class="c-slider__nav">
      <JoSliderNav
        v-for="(slide, i) in slides"
        :key="i"
        :index="i"
        :current-slide="currentSlide"
        @update:currentSlide="manuallyChangeSlide"
      />
    </ul>

    <ul v-if="!loading" class="c-slider__list">
      <JoSlide
        v-for="(slide, i) in slides"
        :key="i"
        :slide="slide"
        :order="slideOrder[i]"
        :current-slide="currentSlide"
        :slide-transition="slideTransition"
      />
    </ul>

    <JoSliderTeaser
      v-if="!loading && teaser"
      :slides="slides"
      :current-slide="currentSlide"
      :auto="isAutomatic"
    />
  </div>
</template>

<script>
import axios from 'axios'
import JoSlide from './JoSlide.vue'
import JoSliderNav from './JoSliderNav.vue'
import JoSliderTeaser from './JoSliderTeaser.vue'

export default {
  components: {
    JoSlide,
    JoSliderNav,
    JoSliderTeaser,
  },
  props: {
    params: { type: Object, default: () => ({}) },
    options: { type: Object, default: () => ({}) },
  },
  data () {
    return {
      loading: true,
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
    mode () {
      return this.options.mode || 'none'
    },
    slideDuration () {
      return this.options.slideDuration || 5000
    },
    slideTransition () {
      return this.options.slideTransition || 800
    },
    teaser () {
      return this.options.teaser || false
    },
    id () {
      return this.options.id || null
    },
    controls () {
      return this.count > 1
    },
  },
  async mounted () {
    this.getSlides()

    if (this.mode !== 'none') {
      this.isAutomatic = true
      this.interval = setInterval(() => {
        this.nextSlide()
        if (
          this.mode === 'initial' &&
          this.slideChangeCount >= this.count
        ) {
          this.stopAutomaticSlideChange()
        }
      }, this.slideDuration)
    }
  },
  methods: {
    async getSlides () {
      try {
        if (this.id) {
          const { data } = await axios.get(`wp/v2/slides/${this.id}`)
          this.slides = [data]
        } else {
          const { data } = await axios.get('wp/v2/slides', this.params)
          this.slides = data
        }
        this.count = this.slides.length

        for (let i = 0; i < this.slides.length; i++) {
          this.slides[i].index = i
          this.slideOrder[i] = this.count - i
        }
      } catch (error) {
        console.log({ error })
      }
      this.loading = false
    },
    changeSlide (i) {
      if (this.currentSlide !== i) this.slideChangeCount++
      this.currentSlide = i
      for (let i = 0; i < this.slideOrder.length; i++) {
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
}
</script>

<style lang="scss">
@import "../../../styles/_settings.scss";

/**
 * 1. Why does z-index start with 20? Because each slide will get it's own
 *    z-index assigned, starting with 1. Now we have space for 19 slides,
 *    that should be enough.
 */

/* Keep in sync with slider.js */
$slide-transition: 0.7s;
$slide-duration: 5s;

.c-slider {
  position: relative;
  height: $unit * 22;
  overflow: hidden;
  // background-color: $c-background;

  &--placeholder {
    margin-top: -$unit * 22;
    z-index: 0;
  }

  &--quote {
    height: $unit * 12;
  }
}

.c-slider__btn {
  opacity: 0.4;

  &:focus,
  &:hover {
    opacity: 0.6;
  }
}

.c-slider__control,
.c-slider__nav {
  position: absolute;
  z-index: 22; /* 1 */
}

.c-slider__control {
  display: flex;
  height: 100%;
  align-items: center;
}

.c-slider__control--left {
  left: 0;
  padding: $unit $unit $unit 0;
}

.c-slider__control--right {
  right: 0;
  padding: $unit 0 $unit $unit;
}

.c-slider__nav {
  bottom: 0;
  margin: 0;
  width: 100%;
  text-align: center;

  > li {
    display: inline-block;
    cursor: pointer;
    padding: $unit-small 0 $unit;
    line-height: 0;
    margin-right: $unit-tiny;

    &:hover .c-slider__btn {
      opacity: 0.6;
    }
  }

  .c-slider__btn {
    padding: 0;
    width: 60px;
    height: 4px;
    overflow: hidden;
  }
}

.c-slider__btn__fill {
  transform: translateX(-100%);
  background-color: $c-black;
  height: 100%;

  .is-active & {
    transform: translateX(0%);

    .is-automatic & {
      transition: $slide-duration transform linear;
    }
  }
}

.c-slider__list {
  margin: 0;
  height: 100%;
  list-style: none;
}

.c-slider__item {
  position: absolute;
  width: 100%;
  height: 100%;
  overflow: hidden;

  &:first-child {
  z-index: 20; /* 1 */ // First slide on top
}

&.is-entering {
  animation: $slide-transition ease-in-out c-slide-container--enter;
}

&.is-leaving {
  animation: $slide-transition ease-in-out c-slide-container--leave;
}
}

.c-slider__teaser-container {
  position: absolute;
  top: 0;
  bottom: 0;
  width: 100%;
}

.c-slider__teaser {
  position: relative;
  z-index: 23; /* 1 */
  background-color: $c-white;
  width: 50%;
  max-width: $unit * 20;
  margin-left: auto;
  transition: $global-transition;

  &.is-collapsed {
    background-color: transparent;
    height: auto;
  }
}

.c-slider__teaser-btn {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
}

/* Teaser transition */
.collapse-enter-active,
.collapse-leave-active {
  transition: all ease $global-transition;
}
.collapse-enter {
  transform: translateY(-$unit-small); opacity: 0;
}
.collapse-leave-to {
  transform: translateY(-$unit-small); opacity: 0;
}

@keyframes c-slide-container--enter {
  0% {
    transform: translateX(100%);
  }

  100% {
    transform: translateX(0);
  }
}

@keyframes c-slide-container--leave {
  0% {
    transform: translateX(0);
    z-index: 21; /* 1 */
  }

  100% {
    transform: translateX(100%);
    z-index: 21; /* 1 */
  }
}
</style>
