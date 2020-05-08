<template>
  <div @mouseover="onMouseover">
    <JoStreamcheck :stream="options.stream">
      <template v-slot:default="{ live, loading }">
        <a
          href="/livestream"
          class="c-link c-link--block c-link--primary c-primary-nav__link"
          :class="{ 'ls-live': live }"
        >
          <span
            class="c-dot c-dot--border u-mr--"
            :class="{'c-dot--red': live, 'is-loading': loading}"
          ></span>
          <span class="u-hidden-until@desktop">
            Livestream
          </span>
          <span class="u-hidden-from@desktop">
            Live
          </span>
          <span class="u-ic-keyboard_arrow_down"></span>
        </a>
      </template>
    </JoStreamcheck>

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
          Livestream öffnen
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

<style lang="scss" scoped>
.ls-live {
  background-color: $c-green-4;
  color: $c-white;

  &:hover {
    background-color: $c-green-5;
    opacity: 1;
  }
}
</style>
