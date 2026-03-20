<template>
  <div>
    <!-- Search input -->
    <div class="max-w-xl mx-auto">
      <div class="flex items-center bg-white rounded-lg shadow-lg">
        <span class="pl-4 text-gray-400 text-xl u-ic-search"></span>
        <input
          ref="input"
          v-model="inputText"
          type="text"
          class="flex-1 px-3 py-3 text-gray-800 bg-transparent border-none outline-none text-base placeholder:text-gray-400"
          :placeholder="currentPlaceholder"
          :disabled="streaming"
          @keydown.enter.prevent="sendMessage(inputText)"
        >
        <button
          class="c-btn c-btn--primary mr-1 my-1 !rounded-md"
          type="button"
          :disabled="!inputText.trim() || streaming"
          @click="sendMessage(inputText)"
        >
          <span v-if="streaming" class="c-spinner c-spinner--small"></span>
          <span v-else>Suchen</span>
        </button>
      </div>
    </div>

    <!-- Chat conversation (expands below hero) -->
    <div v-if="messages.length" class="max-w-3xl mx-auto mt-8 text-left">
      <template v-for="(msg, i) in messages" :key="i">
        <JoStudyCenterMessage
          :ref="i === messages.length - 1 ? 'lastMessage' : undefined"
          :role="msg.role"
          :text="msg.text"
          :sources="msg.sources"
          :loading="msg.loading"
        />
      </template>

      <!-- Follow-up input -->
      <div class="max-w-[700px] mx-auto mb-4">
        <div class="flex items-end gap-2">
          <textarea
            ref="followupInput"
            v-model="inputText"
            class="flex-1 resize-none border border-white/30 rounded-lg px-4 py-2 text-base leading-6 bg-white/10 text-white placeholder:text-white/50 overflow-y-auto focus:outline-none focus:border-white/60 disabled:opacity-60"
            placeholder="Weitere Frage stellen..."
            rows="1"
            :disabled="streaming"
            @keydown.enter.exact.prevent="sendMessage(inputText)"
            @input="autoGrow"
          />
          <button
            class="c-btn c-btn--small flex items-center justify-center shrink-0 !w-10 !h-10 !p-0 bg-white/20 hover:bg-white/30 text-white border-none disabled:opacity-50"
            :disabled="!inputText.trim() || streaming"
            @click="sendMessage(inputText)"
          >
            <span v-if="streaming" class="c-spinner c-spinner--small"></span>
            <span v-else class="u-ic-send"></span>
          </button>
        </div>
        <button
          v-if="!streaming"
          class="block mx-auto mt-2 text-xs text-white/50 bg-transparent border-none cursor-pointer hover:text-white/80 transition"
          @click="clearHistory"
        >
          Neues Gespräch
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import JoStudyCenterMessage from '../study-center/JoStudyCenterMessage.vue'

const STORAGE_KEY = 'hero-chat-messages'
const MAX_EXCHANGES = 4

const PLACEHOLDERS = [
  'Durchsuche das Archiv...',
  'Was sagt die Bibel über Vergebung?',
  'Predigten über Offenbarung',
  'Wer ist Christopher Kramp?',
  'Erkläre das Heiligtum',
  'Tägliche Andachten über Daniel',
]

export default {
  components: { JoStudyCenterMessage },
  props: {
    options: { type: Object, default: () => ({}) },
  },
  data () {
    return {
      inputText: '',
      messages: [],
      streaming: false,
      currentPlaceholder: PLACEHOLDERS[0],
      placeholderIndex: 0,
    }
  },
  mounted () {
    this.loadHistory()
    this.startPlaceholderRotation()
  },
  beforeUnmount () {
    clearInterval(this._placeholderTimer)
  },
  methods: {
    startPlaceholderRotation () {
      this._placeholderTimer = setInterval(() => {
        this.placeholderIndex = (this.placeholderIndex + 1) % PLACEHOLDERS.length
        this.currentPlaceholder = PLACEHOLDERS[this.placeholderIndex]
      }, 4000)
    },

    async sendMessage (text) {
      text = text.trim()
      if (!text || this.streaming) return

      this.inputText = ''
      this.$nextTick(() => this.resetTextarea())

      this.messages.push({ role: 'user', text })
      this.messages.push({ role: 'assistant', text: '', sources: null, loading: true })

      this.streaming = true
      this.scrollLastMessageIntoView()

      try {
        await this.streamResponse()
      } catch (err) {
        console.error('Hero search error:', err)
        const last = this.messages[this.messages.length - 1]
        if (last.role === 'assistant') {
          last.loading = false
          last.text = last.text || 'Etwas ist schiefgelaufen. Bitte versuche es erneut.'
        }
      }

      this.streaming = false
      this.saveHistory()
    },

    async streamResponse () {
      const apiUrl = this.options.api_url || ''
      const historyMessages = this.buildHistory()

      const response = await fetch(apiUrl + '/api/chat', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ messages: historyMessages }),
      })

      if (!response.ok) {
        const data = await response.json().catch(() => ({}))
        throw new Error(data.message || `HTTP ${response.status}`)
      }

      const reader = response.body.getReader()
      const decoder = new TextDecoder()
      const assistant = this.messages[this.messages.length - 1]
      let buffer = ''

      while (true) {
        const { done, value } = await reader.read()
        if (done) break

        buffer += decoder.decode(value, { stream: true })
        const lines = buffer.split('\n')
        buffer = lines.pop()

        let eventType = null
        for (const line of lines) {
          if (line.startsWith('event: ')) {
            eventType = line.slice(7).trim()
          } else if (line.startsWith('data: ') && eventType) {
            const data = JSON.parse(line.slice(6))
            if (eventType === 'chunk') {
              if (assistant.loading) assistant.loading = false
              assistant.text += data.text
            } else if (eventType === 'done') {
              assistant.loading = false
              const sources = (data.sources || []).sort((a, b) => a.ref - b.ref)
              const refRemap = {}
              sources.forEach((s, i) => {
                refRemap[s.ref] = i + 1
                s.ref = i + 1
              })
              assistant.text = assistant.text.replace(
                /\[(\d+)\]/g,
                (match, num) => refRemap[num] ? `[${refRemap[num]}]` : match,
              )
              assistant.sources = sources
              this.scrollLastMessageIntoView()
            } else if (eventType === 'error') {
              assistant.loading = false
              assistant.text = data.message || 'Ein Fehler ist aufgetreten.'
            }
            eventType = null
          }
        }
      }

      if (assistant.loading) assistant.loading = false
    },

    buildHistory () {
      const apiMessages = this.messages
        .filter(m => m.text && !m.loading)
        .map(m => ({ role: m.role, content: m.text }))
      return apiMessages.slice(-(MAX_EXCHANGES * 2))
    },

    saveHistory () {
      try {
        const data = this.messages
          .filter(m => m.text && !m.loading)
          .map(m => ({ role: m.role, text: m.text, sources: m.sources || null }))
        localStorage.setItem(STORAGE_KEY, JSON.stringify(data))
      } catch (e) { /* quota exceeded */ }
    },

    loadHistory () {
      try {
        const stored = localStorage.getItem(STORAGE_KEY)
        if (stored) this.messages = JSON.parse(stored)
      } catch (e) { /* corrupted */ }
    },

    scrollLastMessageIntoView () {
      if (this._scrollRAF) return
      this._scrollRAF = requestAnimationFrame(() => {
        this._scrollRAF = null
        const el = this.$refs.lastMessage?.[0]?.$el
        if (!el) return
        const rect = el.getBoundingClientRect()
        if (rect.top >= 0 && rect.top < window.innerHeight) return
        el.scrollIntoView({ behavior: 'smooth', block: 'start' })
      })
    },

    autoGrow () {
      const el = this.$refs.followupInput
      if (!el) return
      el.style.height = 'auto'
      el.style.height = Math.min(el.scrollHeight, 120) + 'px'
    },

    resetTextarea () {
      const el = this.$refs.followupInput
      if (!el) return
      el.style.height = 'auto'
    },

    clearHistory () {
      this.messages = []
      localStorage.removeItem(STORAGE_KEY)
      this.$nextTick(() => this.$refs.input?.focus())
    },
  },
}
</script>
