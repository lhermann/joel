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
      loading: true,
      live: false,
    }
  },
  async mounted () {
    try {
      const { data } = await axios.get('//streamcheck.joelmedia.de/api/v1/status')
      this.live = data.live
    } catch (error) {
      console.log(error) // eslint-disable-line
    }
    this.loading = false
  },
}
</script>
