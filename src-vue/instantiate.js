import { createApp } from 'vue'

/* Instantiate Vue Components
 *****************************/

function mountComponent (el, component) {
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
  return instance
}

export default {
  instances: [],
  component (selector, componentOrFactory) {
    const elements = document.querySelectorAll(`[data-vue="${selector}"]`)
    if (!elements.length) return

    if (typeof componentOrFactory === 'function') {
      componentOrFactory().then(mod => {
        const component = mod.default || mod
        elements.forEach(el => {
          this.instances.push(mountComponent(el, component))
        })
      })
    } else {
      elements.forEach(el => {
        this.instances.push(mountComponent(el, componentOrFactory))
      })
    }
  },
  util (selector, component) {
    const elements = document.querySelectorAll(`[data-vue="${selector}"]`)
    elements.forEach(el => component(el))
  },
}
