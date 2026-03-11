<template>
  <div class="flex flex-col flex-1 min-h-0">
    <div ref="messages" class="flex-1 overflow-y-auto px-4 pt-4">

      <!-- Welcome state -->
      <div v-if="messages.length === 0" class="flex flex-col items-center justify-center text-center min-h-full">
        <h1 class="text-3xl mb-2">Studienzentrum</h1>
        <p class="text-gray-500 mb-8">Stelle Fragen zum Archiv, zu Predigten und zur Bibel</p>
        <div class="flex flex-wrap justify-center gap-2 max-w-xl mb-6">
          <button
            v-for="chip in exampleChips"
            :key="chip"
            class="px-4 py-2 border border-gray-300 rounded-full text-sm bg-white cursor-pointer transition hover:bg-gray-50 hover:border-gray-400"
            @click="sendMessage(chip)"
          >
            {{ chip }}
          </button>
        </div>
        <!-- Input inline in welcome state -->
        <div class="flex items-end w-full max-w-[700px] gap-2">
          <textarea
            ref="input"
            v-model="inputText"
            class="flex-1 resize-none border border-gray-300 rounded-lg px-4 py-2 text-base leading-6 overflow-y-auto focus:outline-none focus:border-blue-500 disabled:opacity-60"
            placeholder="Stelle eine Frage..."
            rows="1"
            :disabled="streaming"
            @keydown="onKeydown"
            @input="autoGrow"
          />
          <button
            class="shrink-0 flex items-center justify-center c-btn c-btn--small c-btn--green !rounded-lg !w-10 !h-10 !p-0 disabled:opacity-50"
            :disabled="!inputText.trim() || streaming"
            @click="sendMessage(inputText)"
          >
            <span v-if="streaming" class="c-spinner c-spinner--small" />
            <span v-else class="u-ic-send" />
          </button>
        </div>
      </div>

      <!-- Conversation -->
      <template v-if="messages.length">
        <template v-for="(msg, i) in messages" :key="i">
          <JoStudyCenterMessage
            :role="msg.role"
            :text="msg.text"
            :sources="msg.sources"
            :loading="msg.loading"
          />
        </template>

        <!-- Input below conversation -->
        <div class="max-w-[700px] mx-auto mb-4">
          <div class="flex items-end gap-2">
            <textarea
              ref="input"
              v-model="inputText"
              class="flex-1 resize-none border border-gray-300 rounded-lg px-4 py-2 text-base leading-6 overflow-y-auto focus:outline-none focus:border-blue-500 disabled:opacity-60"
              placeholder="Stelle eine Frage..."
              rows="1"
              :disabled="streaming"
              @keydown="onKeydown"
              @input="autoGrow"
            />
            <button
              class="c-btn c-btn--small c-btn--green flex items-center justify-center shrink-0 !w-10 !h-10 !p-0 disabled:opacity-50"
              :disabled="!inputText.trim() || streaming"
              @click="sendMessage(inputText)"
            >
              <span v-if="streaming" class="c-spinner c-spinner--small" />
              <span v-else class="u-ic-send" />
            </button>
          </div>
          <button
            v-if="!streaming"
            class="block mx-auto mt-2 text-xs text-gray-400 bg-transparent border-none cursor-pointer hover:text-gray-600 transition"
            @click="clearHistory"
          >
            Neues Gespräch
          </button>
        </div>
      </template>

      <div ref="scrollAnchor" />
    </div>
  </div>
</template>

<script>
import JoStudyCenterMessage from './JoStudyCenterMessage.vue'

const STORAGE_KEY = 'study-center-messages'
const MAX_EXCHANGES = 4

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
      exampleChips: [
        'Was sagt der Prophet Joel über Vergebung?',
        'Erkläre das Heiligtum im Kontext von Römer 8',
        'Was ist die Taufe?',
        'Predigten über das Buch Jesaja',
      ],
    }
  },
  mounted () {
    this.loadHistory()
  },
  methods: {
    async sendMessage (text) {
      text = text.trim()
      if (!text || this.streaming) return

      this.inputText = ''
      this.$nextTick(() => this.resetTextarea())

      // Add user message
      this.messages.push({ role: 'user', text })

      // Add assistant placeholder
      this.messages.push({ role: 'assistant', text: '', sources: null, loading: true })

      this.streaming = true
      this.scrollToBottom()

      try {
        await this.streamResponse()
      } catch (err) {
        console.error('Study Center error:', err)
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
        buffer = lines.pop() // keep incomplete line

        let eventType = null
        for (const line of lines) {
          if (line.startsWith('event: ')) {
            eventType = line.slice(7).trim()
          } else if (line.startsWith('data: ') && eventType) {
            const data = JSON.parse(line.slice(6))
            if (eventType === 'chunk') {
              if (assistant.loading) assistant.loading = false
              assistant.text += data.text
              this.scrollToBottom()
            } else if (eventType === 'done') {
              assistant.loading = false
              // Renumber sources sequentially (LLM may skip numbers)
              const sources = (data.sources || []).sort((a, b) => a.ref - b.ref)
              const refRemap = {}
              sources.forEach((s, i) => {
                refRemap[s.ref] = i + 1
                s.ref = i + 1
              })
              // Renumber citations in text
              assistant.text = assistant.text.replace(
                /\[(\d+)\]/g,
                (match, num) => refRemap[num] ? `[${refRemap[num]}]` : match,
              )
              assistant.sources = sources
              this.scrollToBottom()
            } else if (eventType === 'error') {
              assistant.loading = false
              assistant.text = data.message || 'Ein Fehler ist aufgetreten.'
            }
            eventType = null
          }
        }
      }

      // If still loading after stream ends (no done event)
      if (assistant.loading) assistant.loading = false
    },

    buildHistory () {
      const apiMessages = this.messages
        .filter(m => m.text && !m.loading)
        .map(m => ({ role: m.role, content: m.text }))

      const maxMessages = MAX_EXCHANGES * 2
      return apiMessages.slice(-maxMessages)
    },

    saveHistory () {
      try {
        const data = this.messages
          .filter(m => m.text && !m.loading)
          .map(m => ({ role: m.role, text: m.text, sources: m.sources || null }))
        localStorage.setItem(STORAGE_KEY, JSON.stringify(data))
      } catch (e) { /* quota exceeded, ignore */ }
    },

    loadHistory () {
      try {
        const stored = localStorage.getItem(STORAGE_KEY)
        if (stored) this.messages = JSON.parse(stored)
      } catch (e) { /* corrupted data, ignore */ }
    },

    scrollToBottom () {
      if (this._scrollRAF) return
      this._scrollRAF = requestAnimationFrame(() => {
        this._scrollRAF = null
        this.$refs.scrollAnchor?.scrollIntoView({ behavior: 'smooth' })
      })
    },

    onKeydown (e) {
      if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault()
        this.sendMessage(this.inputText)
      }
    },

    autoGrow () {
      const el = this.$refs.input
      if (!el) return
      el.style.height = 'auto'
      el.style.height = Math.min(el.scrollHeight, 120) + 'px'
    },

    resetTextarea () {
      const el = this.$refs.input
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
