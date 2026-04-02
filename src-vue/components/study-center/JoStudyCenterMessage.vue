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
      let html = marked.parse(this.text)
      // Convert [N], [N, M, ...], and [N: timestamp] citation markers to superscripts
      const supClass = 'text-[10px] text-blue-600/70 font-medium ml-px'
      html = html.replace(
        /\[(\d+(?:\s*,\s*\d+)*)(?::[^\]]*)?\]/g,
        (_, nums) => nums.split(',').map(n => `<sup class="${supClass}">${n.trim()}</sup>`).join(''),
      )
      return html
    },
  },
}
</script>
