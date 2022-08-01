<template>
  <div class="o-flex o-flex--middle o-flex--nowrap c-livestream-meta">

    <div class="o-flex__item u-text+" style="flex-shrink: 0">
      <JoStreamcheck :stream="options.stream">
        <template v-slot:default="{ live, loading }">
          <span
            class="c-dot u-mr--"
            :class="{'c-dot--green': live, 'is-loading': loading}"
          ></span>
          <strong v-if="live" v-cloak>ON AIR</strong>
          <strong v-else class="u-muted">OFFLINE</strong>
        </template>
      </JoStreamcheck>
    </div>

    <div v-if="event" class="o-flex__item u-pl">
      <div class="o-flag">
        <div class="o-flag__img" v-html="event.thumbnail"></div>
        <div class="o-flag__body">
          <strong class="u-text+">{{ event.post_title }}</strong>
          <br>{{ day }} von {{ start }} bis {{ finish }}
        </div>
      </div>
    </div>

    <div v-else-if="loading" class="o-flex__item u-ph+">
      <div class="c-spinner"></div>
    </div>

    <div v-else class="o-flex__item u-pl">
      <strong class="u-muted">Keine Aufnahmen für heute geplant</strong>
    </div>
  </div>
</template>

<script>
import JoStreamcheck from './JoStreamcheck.vue'
import axios from 'axios'
import format from 'date-fns/format'
import locale from 'date-fns/locale/de'
import parseISO from 'date-fns/parseISO'
import isToday from 'date-fns/isToday'

export default {
  components: { JoStreamcheck },
  props: {
    params: { type: Object, default: () => ({}) },
    options: { type: Object, default: () => ({}) },
  },
  data () {
    return {
      event: null,
      loading: true,
    }
  },
  computed: {
    day () {
      if (isToday(this.event.StartDate)) {
        return 'Heute'
      } else {
        return format(parseISO(this.event.StartDate), 'iiii', { locale })
      }
    },
    start () {
      const time = this.event.StartDate + ' ' + this.event.StartTime
      return format(parseISO(time), 'k:mm', { locale })
    },
    finish () {
      const time = this.event.EndDate + ' ' + this.event.FinishTime
      return format(parseISO(time), 'k:mm', { locale })
    },
  },
  async mounted () {
    try {
      const { data } = await axios.get('joel/v1/events', { params: this.params })
      this.event = data.pop()
    } catch (error) {
      console.log({ error })
    }
    this.loading = false
  },
}
</script>
