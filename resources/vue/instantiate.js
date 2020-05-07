import Vue from 'vue'

/* Instantiate Vue Components
 *****************************/
export default {
  instances: [],
  add (selector, component) {
    const elements = document.querySelectorAll(`[data-vue="${selector}"]`)
    elements.forEach(el => {
      this.instances.push(new Vue({
        el: el,
        render: h => h(component, {
          props: {
            params: el.dataset.params ? JSON.parse(el.dataset.params) : {},
            options: el.dataset.options ? JSON.parse(el.dataset.options) : {},
          },
        }),
      }))
    })
  },
}
