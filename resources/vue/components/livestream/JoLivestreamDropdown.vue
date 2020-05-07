<template>
  <div @mouseover="onMouseover">
    <a href="/livestream"
      class="c-link c-link--block c-link--primary c-primary-nav__link">
      <JoStreamcheck :stream="options.stream">
        <template slot-scope="props">
          <span class="c-dot u-mr--"
            :class="{'c-dot--green': props.live, 'is-loading': props.loading}">
          </span>
        </template>
      </JoStreamcheck>
      <span class="u-hidden-until@desktop">
        Livestream
        <!-- <?= __('Livestream', config('textdomain')) ?> -->
      </span>
      <span class="u-hidden-from@desktop">
        Live
        <!-- <?= _x('Live', 'Short for livestream', config('textdomain')) ?> -->
      </span>
      <span class="u-ic-keyboard_arrow_down"></span>
    </a>

    <ul class="c-primary-nav__dropdown" style="width: 500px">

      <li v-cloak
        v-for="event in events"
        :key="event.occurrence_id"
        class="c-primary-nav__dropdown-item"
        :class="{'u-bg-muted': !event.today}"
      >
        <JoEvent :event="event" url="/livestream" />
      </li>

      <li v-if="!events.length"
        class="c-primary-nav__dropdown-item u-bg-muted u-p">
        <div class="c-spinner u-box-center"></div>
      </li>

      <li class="c-primary-nav__dropdown-item">
        <a href="/livestream"
          class="c-link c-link--block c-link--primary u-truncate">
          Go to livestream
          <!-- <?= __('Go to livestream', config('textdomain')) ?> -->
          <span class="u-ic-arrow_forward"></span>
        </a>
      </li>

    </ul>
  </div>
</template>

<script>
import JoStreamcheck from './JoStreamcheck'
import JoEvent from './JoEvent'
import axios from 'axios'

export default {
  components: { JoStreamcheck, JoEvent },
  props: {
    params: { type: Object, default: () => ({}) },
    options: { type: Object, default: () => ({}) },
  },
  data () {
    return {
      events: [],
    }
  },
  methods: {
    async request () {
      this.params.thumbnail_size = '72p'
      try {
        const { data } = await axios.get('joel/v1/events', { params: this.params })
        this.events = data
      } catch (error) {
        console.log(error) // eslint-disable-line
      }
    },
    onMouseover () {
      if (!this.events.length) this.request()
    },
  },
}
</script>
