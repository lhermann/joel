import Vue from 'vue'

export default function (element) {
  return new Vue({
    el: element,
    data () {
      return {
        toggled: false,
      }
    },
    methods: {
      toggle (state) {
        console.log('toggle me')
        if (state && typeof state === 'boolean') this.toggled = state
        else this.toggled = !this.toggled
      },
    },
  })
}
