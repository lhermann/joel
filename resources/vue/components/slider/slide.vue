<template>
  <li class="c-slider__item" :class="liCss" :style="{ 'z-index': order }">
    <div
      class="c-slide"
      :class="[`c-slide--${acf.slide_type}`, `c-slide--${acf.color_scheme}`]"
      :style="{ 'background-image': `url(${colors.bg})` }"
    >
      <a
        v-if="acf.link"
        :href="acf.link.url"
        :target="acf.link.target"
        class="c-slide__link"
      ></a>

      <div class="o-wrapper c-slide__wrapper">
        <div class="c-slide__body u-1/2@tablet u-1/1">
          <h1
            v-if="acf.show_title"
            class="u-mb-"
            :style="{ color: colors.title }"
            v-html="title.rendered"
          ></h1>

          <div
            class="u-text+"
            :class="{ 'u-muted': colors.theme === 'image' }"
            :style="{ color: colors.text }"
            v-html="acf.content"
          ></div>

          <a
            v-if="acf.link && acf.button_text"
            class="c-btn c-btn--dark c-slide__btn"
            :href="acf.link.url"
            :target="acf.link.target"
          >
            {{ acf.button_text }}
            <span class="u-ic-arrow_forward"></span>
          </a>
        </div>

        <div
          v-if="media"
          class="c-slide__media u-1/2@tablet u-hidden-until@tablet"
        >
          <div class="o-ratio o-ratio--16:9 u-shadow">
            <a
              v-if="media.acf_fc_layout === 'image'"
              class="o-ratio__content"
              :href="acf.url"
            >
              <img :src="media.image.sizes['360p']" />
            </a>
          </div>
        </div>
      </div>
    </div>
  </li>
</template>

<script>
import get from 'lodash/get'

const colors = {
  'dark-blue': ['#ffffff', '#d1e4f9'],
  teal: ['#ffffff', '#8aeec6'],
  red: ['#ffffff', '#ff9a99'],
  green: ['#2d4000', '#629303'],
  blue: ['#001f5c', '#004fb8'],
  image: ['#ffffff', '#ffffff'],
}

export default {
  name: 'Slide',
  props: {
    slide: Object,
    order: Number,
    currentSlide: Number,
    slideTransition: Number,
  },
  data () {
    return Object.assign({ liCss: [] }, this.slide)
  },
  computed: {
    colors () {
      const bg = get(this.acf, 'colors', '')
      const match = /slide-(.+?).\w{1,4}$/.exec(bg)
      const theme = get(match, '1', bg)
      if (theme === 'image') {
        return {
          theme,
          bg: get(this.acf, 'background_image.sizes.bg3x1', ''),
          title: get(this.acf, 'text_color', 'black'),
          text: get(this.acf, 'text_color', 'black'),
        }
      } else {
        return {
          theme,
          bg,
          title: colors[theme][0],
          text: colors[theme][1],
        }
      }
    },
    media () {
      if (
        this.acf.slide_type === 'media-left' ||
        this.acf.slide_type === 'media-right'
      ) {
        return this.acf.media_content[0]
      } else {
        return false
      }
    },
  },
  methods: {
    animate (css) {
      this.liCss.push(css)
      setTimeout(
        function (obj) {
          obj.liCss = []
        },
        this.slideTransition,
        this,
      )
    },
  },
  watch: {
    currentSlide: function (newVal, oldVal) {
      if (newVal === this.index && newVal > oldVal) {
        this.animate('is-entering')
      } else if (oldVal === this.index && newVal < oldVal) {
        this.animate('is-leaving')
      }
    },
  },
}
</script>
