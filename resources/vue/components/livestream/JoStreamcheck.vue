<template>
  <span>
    <slot :live="live" :loading="loading"></slot>
  </span>
</template>

<script>
import axios from 'axios'

export default {
  name: 'Streamcheck',
  data () {
    return {
      loading: false,
      live: false,
      intervalID: null,
    }
  },
  created () {
    this.intervalID = setInterval(this.getStatus, 60 * 1000)
  },
  beforeDestory () {
    clearInterval(this.intervalID)
  },
  methods: {
    async getStatus () {
      this.loading = true
      try {
        const { data } = await axios.get('//streamcheck.joelmedia.de/api/v1/status')
        this.live = data.live
      } catch (error) {
        console.log(error) // eslint-disable-line
      }
      this.loading = false
    },
  },
}
</script>
