/*
 * Medialist Init
 * @author: Lukas Hermann
 */

import Vue from 'vue'
import axios from 'axios'
import last from 'lodash/last'
import trimEnd from 'lodash/trimEnd'
import filesize from 'filesize'
import differenceInSeconds from 'date-fns/differenceInSeconds'

/* Vue Instance
 **********************/
var vue = null
if (document.getElementById('vue-recording-status')) {
  vue = new Vue({
    el: '#vue-recording-status',
    name: 'Recording Status',
    data: {
      loading: true,
      postid: null,
      items: [],
      showDetails: false,
      interval: null,
      updated: Date.now(),
      now: Date.now(),
    },
    computed: {
      size () {
        return filesize(
          this.items.reduce(
            (total, item) =>
              total +
                            (item.location === 'remote'
                              ? Number(item.size)
                              : 0),
            0,
          ),
        )
      },
      progress () {
        if (!this.items.length) return '0%'
        const progress = this.items.reduce(
          (accum, item) =>
            accum +
                            (item.status <= 5 ? Number(item.status) : 0),
          0,
        )
        const total =
                        this.items.filter(item => item.status <= 5).length * 5
        return Math.ceil((progress / total) * 100) + '%'
      },
      done () {
        return trimEnd(this.progress, '%') >= 100
      },
      timeUntil () {
        return 30 - differenceInSeconds(this.now, this.updated)
      },
      error () {
        if (this.items.filter(item => item.status == 60).length) { return 'File incompatible' } else if (this.items.filter(item => item.status == 61).length) { return 'An error occured, check the logs' } else return ''
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
      this.postid = this.$el.attributes.postid.value
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
  })

  Vue.component('status-item', {
    template: `
        <tr>
            <td class="status_icon column">
                    <span class="c-dot" :class="dotClass">{{item.status}}</span>
                </td>
            </td>
            <td class="column">
                <small class="u-muted">
                    <span class="c-badge">{{item.location}}</span>
                    {{path}}
                </small>
                <br/>{{file}}
            </td>
            <td class="column u-text-right">{{size}}</td>
            <td class="column u-text--">{{item.resolution}}<br/>{{item.bitrate}} kb/s</td>
        </tr>
        `,
    props: ['item'],
    computed: {
      dotClass () {
        const status = Number(this.item.status)
        if (status < 5) return 'c-dot--yellow is-loading'
        else if (status >= 60) return 'c-dot--red'
        else return 'c-dot--green'
      },
      file () {
        return last(this.item.relative_url.split('/'))
      },
      path () {
        return (
          this.item.relative_url
            .split('/')
            .slice(0, -1)
            .join('/') + '/'
        )
      },
      size () {
        return filesize(this.item.size)
      },
    },
  })
}
export default vue
