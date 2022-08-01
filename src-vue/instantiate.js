import { createApp } from 'vue'

/* Instantiate Vue Components
 *****************************/
export default {
  instances: [],
  component (selector, component) {
    const elements = document.querySelectorAll(`[data-vue="${selector}"]`)
    elements.forEach(el => {

      const instance = createApp(
        component,
        {
          params: el.dataset.params ? JSON.parse(el.dataset.params) : {},
          options: el.dataset.options ? JSON.parse(el.dataset.options) : {},
        },
      )
      instance.config.globalProperties.$joel = Object.assign(
        { templatePath: '', assetPath: '' },
        window._joel,
      )
      instance.mount(el)
      this.instances.push(instance)
    })
  },
  util (selector, component) {
    const elements = document.querySelectorAll(`[data-vue="${selector}"]`)
    elements.forEach(el => component(el))
  },
}
