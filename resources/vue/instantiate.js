import Vue from 'vue'

/* Instantiate Vue Components
 *****************************/
export default {
  instances: [],
  component (selector, component) {
    const elements = document.querySelectorAll(`[data-vue="${selector}"]`)
    elements.forEach(el => {
      this.instances.push(new Vue({
        el: el,
        render: h => h(component, {
          class: el.className,
          props: {
            params: el.dataset.params ? JSON.parse(el.dataset.params) : {},
            options: el.dataset.options ? JSON.parse(el.dataset.options) : {},
          },
        }),
      }))
    })
  },
  util (selector, component) {
    const elements = document.querySelectorAll(`[data-vue="${selector}"]`)
    elements.forEach(el => component(el))
  },
}
