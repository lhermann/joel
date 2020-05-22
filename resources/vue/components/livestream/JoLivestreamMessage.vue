<template>
  <div v-if="message" class="c-alert c-alert--blue" v-html="message">
  </div>
</template>

<script>
import axios from 'axios'

export default {
  props: {
    params: { type: Object, default: () => ({}) },
    options: { type: Object, default: () => ({}) },
  },
  data () {
    return {
      loading: false,
      intervalID: null,
      message: '',
    }
  },
  created () {
    this.getMessage()
    this.intervalID = setInterval(this.getMessage, 60 * 1000)
  },
  beforeDestory () {
    clearInterval(this.intervalID)
  },
  methods: {
    async getMessage () {
      this.loading = true
      try {
        const pageID = this.options ? this.options.pageID : 0
        const { data } = await axios.get(`/acf/v3/pages/${pageID}/alert`)
        this.message = data.alert
      } catch (error) {
        console.log(error) // eslint-disable-line
      }
      this.loading = false
    },
  },
}
</script>

<style scoped>
.c-alert ::v-deep p:last-child {
  margin-bottom: 0;
}
</style>
