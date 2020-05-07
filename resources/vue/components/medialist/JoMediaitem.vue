<template>
  <div
    class="o-media c-mediaitem"
    :class="['c-mediaitem--' + item.type, { 'c-mediaitem--dummy': isDummy }]"
  >
    <a class="c-mediaitem__link" :href="item.link"></a>

    <div class="o-media__img c-mediaitem__img">
      <a
        v-if="item.type === 'topics'"
        class="c-mediaitem__imglink"
        :href="item.link"
      >
        <span class="u-ic-folder"></span>
      </a>
      <a v-else class="c-mediaitem__imglink" :href="item.link">
        <img :src="item.thumbnail" />
        <div v-if="length" class="c-mediaitem__length">
          <div v-html="length"></div>
        </div>
      </a>
      <div
        v-if="isNew"
        class="c-mediaitem__new"
        :style="{
          backgroundImage: `url(${$joel.assetPath}images/neu-badge.png)`
        }"
      ></div>
    </div>

    <div class="o-media__body c-mediaitem__body">
      <a class="c-mediaitem__title" :href="item.link" v-html="title"></a>
      <ul class="c-mediaitem__meta u-truncate">
        <li v-if="subtopics">
          {{ subtopics }}
        </li>
        <li v-if="!isRecording">
          {{ item.count }} {{ item.count === 1 ? "Aufnahme" : "Aufnahmen" }}
        </li>
        <li v-if="item.speakers">
          <a
            v-for="(speaker, i) in item.speakers"
            :key="i"
            :href="speaker.link"
          >
            {{ speaker.name }}{{ i !== item.speakers.length - 1 ? "," : "" }}
          </a>
        </li>
        <li v-if="item.views">{{ item.views }} Klicks</li>
        <li v-if="item.series_count">
          {{ item.series_count }}
          {{ item.series_count === 1 ? "Serie" : "Serien" }}
        </li>
        <li v-if="isRecording">
          {{ item.date_human }}
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
import differenceInDays from 'date-fns/differenceInDays'
import parseISO from 'date-fns/parseISO'
import get from 'lodash/get'

export default {
  props: {
    item: Object,
  },
  computed: {
    isRecording () {
      return this.item.type === 'recordings'
    },
    isNew () {
      const date = parseISO(this.item.date_gmt + 'Z')
      return differenceInDays(Date.now(), date) <= 7
    },
    title () {
      const title = this.isRecording
        ? get(this.item, 'title.rendered', '')
        : this.item.name
      if (!title || title.length < 90) {
        return title
      } else {
        return title.replace(/^(.{0,90}[\s]).*$/, '$1...')
      }
    },
    length () {
      if (this.isRecording) return this.item.length
      return this.item.count
    },
    subtopics () {
      if (typeof this.item.subtopics_count === 'undefined') return null
      switch (this.item.subtopics_count) {
        case 0:
          return 'Keine Unterthemen'
        case 1:
          return 'Ein Unterthema'
        default:
          return `${this.item.subtopics_count} Unterthemen`
      }
    },
    isDummy () {
      return typeof this.item.dummy !== 'undefined'
    },
  },
}
</script>
