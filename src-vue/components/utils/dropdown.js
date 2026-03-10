import { createApp } from 'vue/dist/vue.esm-bundler'
import { createPopper } from '@popperjs/core'
import { ref, nextTick, onMounted, onBeforeUnmount } from 'vue'

export default function (element) {
  const buttonElement = element.querySelector('[data-ref="button"]')
  const dropdownElement = element.querySelector('[data-ref="dropdown"]')

  const app = createApp({
    name: 'DropdownUtil',
    setup () {
      const visible = ref(false)
      let popper = null

      onMounted(() => {
        document.addEventListener('click', onOutsideClick)
        popper = createPopper(buttonElement, dropdownElement, {
          placement: dropdownElement.dataset.placement,
        })
      })
      onBeforeUnmount(() => {
        document.removeEventListener('click', onOutsideClick)
      })

      function toggleDropdown (event, force = null) {
        visible.value = force === null ? !visible.value : force
        nextTick(() => {
          popper.update()
        })
      }
      function onOutsideClick (event) {
        if (visible.value && !element.contains(event.target)) {
          toggleDropdown(event, false)
        }
      }

      return {
        toggleDropdown,
        visible,
      }
    },
  })

  app.mount(element)

  return app
}
