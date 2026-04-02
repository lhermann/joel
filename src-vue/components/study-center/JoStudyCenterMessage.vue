<template>
  <div class="max-w-[700px] mx-auto mb-4">

    <div
      class="px-4 py-2"
      :class="role === 'user'
        ? 'bg-blue-50 border border-blue-100 rounded-xl rounded-br-none ml-8'
        : 'bg-white border border-gray-200 rounded-xl rounded-bl-none'"
    >
      <!-- Loading dots -->
      <div v-if="loading" class="c-study-dots flex gap-1 py-1">
        <span class="block w-2 h-2 rounded-full bg-gray-400" />
        <span class="block w-2 h-2 rounded-full bg-gray-400" />
        <span class="block w-2 h-2 rounded-full bg-gray-400" />
      </div>

      <!-- Text content -->
      <div
        v-else-if="role === 'assistant'"
        class="c-study-content leading-6"
        v-html="renderedHtml"
        @click="onCiteClick"
      />
      <div v-else class="leading-6">
        {{ text }}
      </div>
    </div>

    <!-- Source cards -->
    <div v-if="sources && sources.length" class="flex flex-col gap-2 mt-2">
      <JoStudyCenterCard
        v-for="source in sources"
        :key="source.ref"
        :source="source"
      />
    </div>

  </div>
</template>

<script>
import { marked } from 'marked'
import { renderCitationSups } from '../../utils/citations.js'
import JoStudyCenterCard from './JoStudyCenterCard.vue'

// Configure marked
const renderer = new marked.Renderer()
const originalLink = renderer.link.bind(renderer)
renderer.link = function (args) {
  const html = originalLink(args)
  // Open external links in new tab
  if (args.href && args.href.startsWith('http')) {
    return html.replace('<a ', '<a target="_blank" rel="noopener" ')
  }
  return html
}

marked.setOptions({
  renderer,
  breaks: true,
  gfm: true,
})

export default {
  components: { JoStudyCenterCard },
  props: {
    role: { type: String, required: true },
    text: { type: String, default: '' },
    sources: { type: Array, default: null },
    loading: { type: Boolean, default: false },
  },
  computed: {
    renderedHtml () {
      if (!this.text) return ''
      const html = marked.parse(this.text)
      return renderCitationSups(html)
    },
  },
  methods: {
    onCiteClick (e) {
      const ref = e.target.dataset?.citeRef
      if (!ref) return
      e.preventDefault()
      const card = this.$el.querySelector(`#cite-card-${ref}`)
      if (!card) return
      const rect = card.getBoundingClientRect()
      if (rect.top < 0 || rect.bottom > window.innerHeight) {
        card.scrollIntoView({ behavior: 'smooth', block: 'nearest' })
      }
      card.classList.add('cite-highlight')
      setTimeout(() => card.classList.remove('cite-highlight'), 1500)
    },
  },
}
</script>
