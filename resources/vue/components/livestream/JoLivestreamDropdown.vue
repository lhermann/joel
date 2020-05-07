<template>
  <div @mouseover="onMouseover">
    <a href="/livestream"
      class="c-link c-link--block c-link--primary c-primary-nav__link">
      <JoStreamcheck :stream="options.stream">
        <template v-slot:default="{ live, loading }">
          <span
            class="c-dot u-mr--"
            :class="{'c-dot--green': live, 'is-loading': loading}"
          ></span>
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

      <li
        v-for="event in events"
        :key="event.occurrence_id"
        class="c-primary-nav__dropdown-item"
        :class="{'u-bg-muted': !event.today}"
      >
        <JoEvent :event="event" url="/livestream" />
      </li>

      <li
        v-if="!events.length"
        class="c-primary-nav__dropdown-item u-bg-muted u-center u-p"
      >
        <div class="c-spinner"></div>
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
