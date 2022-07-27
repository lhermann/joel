<template>
  <div id="vue-recording-status" postid="<?= $post->ID ?>">
    <div v-if="loading" class="u-text-center u-m">
      <div class="c-spinner"></div>
    </div>
    <div v-if="!loading" v-cloak>
      <div class="o-flex o-flex--unit o-flex--middle">
        <div class="o-flex__item">
          <span class="dashicons dashicons-video-alt3"></span>
          1:05:14
        </div>
        <div class="o-flex__item">
          <span class="dashicons dashicons-admin-page"></span>
          {{items.length}} Dateien
        </div>
        <div class="o-flex__item">
          <span class="dashicons dashicons-category"></span>
          {{size}}
        </div>
        <div v-if="!done" class="o-flex__item u-yellow u-text--">
          Next update in {{timeUntil}} seconds
        </div>
        <div v-if="error" class="o-flex__item u-red">
          <span class="dashicons dashicons-warning"></span> {{error}}
        </div>
        <div class="o-flex__spacer"></div>
        <div class="o-flex__item">
          <button class="button" @click.prevent="showDetails = !showDetails">+</button>
        </div>
      </div>
      <div class="c-progress c-progress--green">
        <div class="c-progress__bar " role="progressbar" :style="{'width': progress}">
          {{progress}}
        </div>
      </div>
    </div>
    <div v-cloak class="status-details" v-show="showDetails">
      <table class="widefat fixed c-table">
        <thead>
          <tr>
            <th scope="col" class="column" width="12">
              <label class="screen-reader-text" for="status_icon">Status</label>
            </th>
            <th scope="col" class="column" width="100%">File</th>
            <th scope="col" class="column" width="80">Size</th>
            <th scope="col" class="column" width="80">Stats</th>
          </tr>
        </thead>
        <tbody>
          <JoRecordingStatusItem
            v-for="(item, i) in items"
            :key="item.ID"
            :class="{'alternate': i % 2}"
            :item="item"
          />
        </tbody>
      </table>
      <div class="u-muted u-text-center">
        Die Originaldatei wird für zwei Monate gespeichert, bevor sie gelöscht wird.
      </div>
    </div>
  </div>
</template>

<script>
import JoRecordingStatusItem from './JoRecordingStatusItem.vue'
import axios from 'axios'
import trimEnd from 'lodash/trimEnd'
import filesize from 'filesize'
import differenceInSeconds from 'date-fns/differenceInSeconds'

export default {
  components: { JoRecordingStatusItem },
  props: {
    params: { type: Object, default: () => ({}) },
    options: { type: Object, default: () => ({}) },
  },
  data () {
    return {
      loading: true,
      postid: null,
      items: [],
      showDetails: false,
      interval: null,
      updated: Date.now(),
      now: Date.now(),
    }
  },
  computed: {
    size () {
      return filesize(this.items.reduce(
        (acc, item) => acc + (item.location === 'remote' ? Number(item.size) : 0),
        0,
      ))
    },
    progress () {
      if (!this.items.length) return '0%'
      const progress = this.items.reduce(
        (acc, item) => acc + (item.status <= 5 ? Number(item.status) : 0),
        0,
      )
      const total = this.items.filter(item => item.status <= 5).length * 5
      return Math.ceil((progress / total) * 100) + '%'
    },
    done () {
      return trimEnd(this.progress, '%') >= 100
    },
    timeUntil () {
      return 30 - differenceInSeconds(this.now, this.updated)
    },
    error () {
      if (this.items.filter(item => item.status === 60).length) {
        return 'File incompatible'
      } else if (this.items.filter(item => item.status === 61).length) {
        return 'An error occured, check the logs'
      } else {
        return ''
      }
    },
  },
  methods: {
    request () {
      axios
        .get('/wp-json/joel/v1/recording-status/' + this.postid)
        .then(response => (this.items = response.data))
        .catch(error => console.log({ error }))
        .then(() => (this.loading = false))
    },
    updateNow () {
      this.now = Date.now()
      setTimeout(() => this.updateNow(), 1000)
    },
  },
  beforeMount () {
    this.postid = this.options.postid
    this.request()
    this.updateNow()
    this.interval = setInterval(() => {
      this.request()
      this.updated = Date.now()
    }, 30000)
  },
  destroyed () {
    clearInterval(this.interval)
  },
}
</script>
