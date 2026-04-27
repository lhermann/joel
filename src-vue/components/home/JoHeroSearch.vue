<template>
  <div>
    <!-- Search input (stays in hero) -->
    <div class="max-w-xl mx-auto relative">
      <div class="flex gap-2 items-center bg-white rounded-lg shadow-lg p-1">
        <span class="pl-2 text-gray-400 text-xl u-ic-search" />
        <input
          ref="input"
          v-model="inputText"
          type="text"
          class="flex-1 h-10 p-0 text-gray-800 bg-transparent border-none outline-none text-base placeholder:text-gray-400"
          :placeholder="currentPlaceholder"
          :disabled="streaming"
          @input="onSearchInput"
          @keydown.enter.prevent="sendFromHero(inputText)"
          @keydown.down.prevent="navigateSuggestion(1)"
          @keydown.up.prevent="navigateSuggestion(-1)"
          @keydown.escape="suggestionsOpen = false"
          @blur="suggestionsOpen = false"
          @focus="suggestionsOpen = true"
        >
        <button
          class="block h-10 px-4 rounded bg-blue-700 disabled:bg-blue-500 enabled:hover:bg-blue-900 transition-colors"
          type="button"
          :disabled="!inputText.trim() || streaming"
          @click="sendFromHero(inputText)"
        >
          <span v-if="streaming" class="c-spinner c-spinner--small" />
          <span v-else class="u-ic-send text-lg" />
        </button>
      </div>

      <!-- Instant search suggestions -->
      <div
        v-if="suggestionsOpen && suggestions.length > 0"
        class="absolute left-0 right-0 top-full mt-1 bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden z-50 text-left"
      >
        <a
          v-for="(item, i) in suggestions"
          :key="item.post_id"
          :href="suggestionPermalink(item)"
          class="flex items-center gap-3 px-3 py-2 no-underline text-gray-800 transition-colors"
          :class="i === suggestionIndex ? 'bg-blue-50' : 'hover:bg-gray-50'"
          @mousedown.prevent
        >
          <img
            v-if="item.thumbnail"
            :src="item.thumbnail"
            width="80"
            height="45"
            alt=""
            class="shrink-0 w-[80px] h-auto rounded-sm"
          >
          <div class="flex-1 min-w-0">
            <div class="text-sm font-medium leading-tight truncate">{{ item.title }}</div>
            <div v-if="item.speaker" class="text-xs text-gray-500 mt-0.5">{{ item.speaker }}<span v-if="item.series"> · {{ item.series }}</span></div>
          </div>
        </a>
      </div>
    </div>

    <!-- Chat conversation (teleported below hero, on white background) -->
    <Teleport to="#hero-chat">
      <div v-if="chatVisible" class="max-w-screen-xl mx-auto px-4 py-8">

        <!-- Headline -->
        <div class="max-w-[700px] mx-auto mb-6">
          <h2 class="text-xl font-bold mb-1">Zeteo <span class="font-normal">&ndash; Suche und du wirst finden...</span></h2>
          <p class="text-sm text-gray-500 m-0">
            KI Antworten basierend auf unserem Archiv von {{ recordingCount }} Videos
          </p>
        </div>

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
              class="flex-1 resize-none border border-gray-300 rounded-xl rounded-br-none px-4 py-2 text-base leading-6 bg-white text-gray-800 placeholder:text-gray-400 overflow-y-auto focus:outline-none focus:border-blue-500 disabled:opacity-60"
              placeholder="Weitere Frage stellen..."
              rows="1"
              :disabled="streaming"
              @keydown.enter.exact.prevent="sendMessage(inputText)"
              @input="autoGrow"
            />
            <button
              class="shrink-0 block h-10 px-4 rounded text-white bg-blue-700 disabled:bg-blue-500 enabled:hover:bg-blue-900 transition-colors"
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

        <!-- Separator -->
        <hr class="mt-10 mb-0 border-gray-200">
      </div>
    </Teleport>
  </div>
</template>

<script>
import JoStudyCenterMessage from '../study-center/JoStudyCenterMessage.vue'
import { renumberCitations } from '../../utils/citations.js'

const STORAGE_KEY = 'jm:chat-messages:home'
const MEMORY_KEY = 'jm:chat-memory'
const MAX_EXCHANGES = 4

const PLACEHOLDERS = [
  'Durchsuche das Archiv...',
  'Was sagt die Bibel über Vergebung?...',
  'Predigten über Offenbarung...',
  'Erkläre das Heiligtum...',
  'Andachten über Daniel...',
  'In welchem Jahr ist Jesus geboren?...',
  '2. Korinther 5, Vers 21...',
  'Was steht in den Schriftrollen vom Toten Meer?...',
  'Was geschah 1844?...',
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
      conversationId: crypto.randomUUID(),
      currentPlaceholder: PLACEHOLDERS[0],
      placeholderIndex: 0,
      suggestions: [],
      suggestionsOpen: false,
      suggestionIndex: -1,
      searchAbort: null,
    }
  },
  computed: {
    chatVisible () {
      return this.messages.some(m => m.text || m.sources)
    },
    recordingCount () {
      const count = this.options.recording_count || 0
      return new Intl.NumberFormat('de-DE').format(count)
    },
  },
  mounted () {
    this.loadHistory()
    this.startPlaceholderRotation()
  },
  beforeUnmount () {
    clearInterval(this._placeholderTimer)
    clearTimeout(this._searchDebounce)
  },
  methods: {
    startPlaceholderRotation () {
      this._placeholderTimer = setInterval(() => {
        this.placeholderIndex = (this.placeholderIndex + 1) % PLACEHOLDERS.length
        this.currentPlaceholder = PLACEHOLDERS[this.placeholderIndex]
      }, 4000)
    },

    onSearchInput () {
      clearTimeout(this._searchDebounce)
      const query = this.inputText.trim()
      if (query.length < 2) {
        this.closeSuggestions() // clear results when input is too short
        return
      }
      this._searchDebounce = setTimeout(() => this.fetchSuggestions(query), 300)
    },

    async fetchSuggestions (query) {
      if (this.searchAbort) this.searchAbort.abort()
      const controller = new AbortController()
      this.searchAbort = controller

      try {
        const apiUrl = this.options.api_url || ''
        const res = await fetch(`${apiUrl}/api/search?q=${encodeURIComponent(query)}`, {
          signal: controller.signal,
        })
        if (!res.ok) return
        this.suggestions = await res.json()
        this.suggestionsOpen = true
        this.suggestionIndex = -1
      } catch (e) {
        if (e.name !== 'AbortError') console.error('Search suggestion error:', e)
      }
    },

    closeSuggestions () {
      this.suggestions = []
      this.suggestionsOpen = false
      this.suggestionIndex = -1
      if (this.searchAbort) {
        this.searchAbort.abort()
        this.searchAbort = null
      }
    },

    navigateSuggestion (direction) {
      if (!this.suggestions.length) return
      this.suggestionIndex = Math.max(-1, Math.min(
        this.suggestions.length - 1,
        this.suggestionIndex + direction,
      ))
    },

    suggestionPermalink (item) {
      return item.permalink || '#'
    },

    sendFromHero (text) {
      if (this.chatVisible) {
        this.messages = []
        this.conversationId = crypto.randomUUID()
        localStorage.removeItem(STORAGE_KEY)
      }
      this.sendMessage(text)
    },

    async sendMessage (text) {
      // If keyboard navigated to a suggestion, go there instead
      if (this.suggestionIndex >= 0 && this.suggestions[this.suggestionIndex]) {
        window.location.href = this.suggestionPermalink(this.suggestions[this.suggestionIndex])
        return
      }
      this.closeSuggestions()

      text = text.trim()
      if (!text || this.streaming) return

      this.inputText = ''
      this.$nextTick(() => this.resetTextarea())

      this.messages.push({ role: 'user', text })
      this.messages.push({ role: 'assistant', text: '', sources: null, loading: true })

      this.streaming = true

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
      this.scrollLastMessageIntoView()
    },

    async streamResponse () {
      const apiUrl = this.options.api_url || ''
      const historyMessages = this.buildHistory()

      const memory = this.loadMemory()
      const body = { messages: historyMessages, conversation_id: this.conversationId }
      if (memory.length) body.memory = memory
      const response = await fetch(apiUrl + '/api/chat', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(body),
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
              // Scroll into view on first chunk (chat just appeared)
              if (assistant.text === data.text) {
                this.$nextTick(() => this.scrollLastMessageIntoView())
              }
            } else if (eventType === 'done') {
              assistant.loading = false
              const sources = (data.sources || []).sort((a, b) => a.ref - b.ref)
              const refRemap = {}
              sources.forEach((s, i) => {
                refRemap[s.ref] = i + 1
                s.ref = i + 1
              })
              assistant.text = renumberCitations(assistant.text, refRemap)
              assistant.sources = sources
              if (data.memory) this.handleMemoryEvent(data.memory)
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
      this.conversationId = crypto.randomUUID()
      localStorage.removeItem(STORAGE_KEY)
      this.$nextTick(() => this.$refs.input?.focus())
    },

    loadMemory () {
      try {
        const stored = localStorage.getItem(MEMORY_KEY)
        return stored ? JSON.parse(stored).map(m => m.text) : []
      } catch (e) { return [] }
    },

    handleMemoryEvent (data) {
      try {
        let memories = []
        try { memories = JSON.parse(localStorage.getItem(MEMORY_KEY)) || [] } catch (e) { /* */ }

        const now = new Date().toISOString().slice(0, 10)
        for (const text of (data.save || [])) {
          memories.push({ text, ts: now })
        }

        // Evict oldest when over limit
        while (memories.length > 10) memories.shift()

        localStorage.setItem(MEMORY_KEY, JSON.stringify(memories))
      } catch (e) { /* quota exceeded */ }
    },
  },
}
</script>
