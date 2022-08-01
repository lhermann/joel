import { createApp } from 'vue'

export default function (element) {
  const app = createApp({
    name: 'Toggle-Util',
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

  app.mount(element)

  return app
}
