<template>
  <a
    :href="permalink"
    class="flex items-start gap-2 p-2 border border-gray-200 rounded bg-white no-underline text-gray-800 transition hover:border-gray-400 hover:shadow-sm"
  >
    <!-- Thumbnail for recordings -->
    <div v-if="isRecording && source.thumbnail" class="relative shrink-0 w-[120px] max-sm:w-[80px]">
      <img
        :src="source.thumbnail"
        width="160"
        height="90"
        alt=""
        class="block w-full h-auto rounded-sm"
      >
      <span
        v-if="timestampLabel"
        class="absolute bottom-1 right-1 px-1 rounded-sm bg-black/75 text-white text-xs leading-snug"
      >
        {{ timestampLabel }}
      </span>
    </div>

    <div class="flex-1 min-w-0">
      <span class="text-xs text-blue-900 font-semibold mr-0.5">[{{ source.ref }}]</span>
      <span class="text-sm font-medium leading-tight">{{ source.title }}</span>
      <span v-if="source.speaker" class="block text-xs text-gray-500 mt-0.5">
        {{ source.speaker }}
      </span>
      <span v-if="source.excerpt" class="block text-xs text-gray-500 mt-0.5">
        {{ source.excerpt }}
      </span>
    </div>
  </a>
</template>

<script>
export default {
  props: {
    source: { type: Object, required: true },
  },
  computed: {
    isRecording () {
      return this.source.post_type === 'recordings'
    },
    permalink () {
      let url = this.source.permalink || '#'
      if (this.isRecording && this.source.start_seconds) {
        url += `?t=${this.source.start_seconds}`
      }
      return url
    },
    timestampLabel () {
      const s = this.source.start_seconds
      if (!s) return null
      const min = Math.floor(s / 60)
      const sec = String(s % 60).padStart(2, '0')
      return `ab ${min}:${sec}`
    },
  },
}
</script>
