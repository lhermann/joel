<template>
  <component
    :is="event.today ? 'a' : 'div'"
    class="o-box o-box--natural"
    :class="{ 'c-link c-link--block c-link--primary': event.today }"
    :href="url"
  >
    <div class="o-flag">
      <div
        v-if="event.thumbnail"
        class="o-flag__img u-mr"
        v-html="event.thumbnail"
      ></div>
      <div class="o-flag__body">
        <div>
          <span
            v-if="event.today"
            class="c-badge"
            :class="{
              'c-badge--success': event.now,
              'u-textmuted': !event.now
            }"
          >
            live
          </span>
          {{ event.post_title }}
        </div>
        <div class="u-text">
          <template v-if="event.today">
            <strong class="u-green">Heute</strong> &middot;
          </template>
          <template v-if="event.tomorrow">
            <strong class="u-yellow">Morgen</strong> &middot;
          </template>
          <span :class="{ 'u-bold': !(event.today || event.tomorrow) }">
            {{ weekday }}
          </span>
          , {{ date }} um {{ time }}
        </div>
      </div>
    </div>
  </component>
</template>

<script>
import format from "date-fns/format";
import locale from "date-fns/locale/de";
import parseISO from "date-fns/parseISO";

export default {
  props: {
    event: Object,
    url: String
  },
  computed: {
    weekday() {
      return format(parseISO(this.event.StartDate), "EEEE", { locale });
    },
    date() {
      return format(parseISO(this.event.StartDate), "d. MMM", { locale });
    },
    time() {
      const datetime = this.event.StartDate + " " + this.event.StartTime;
      return format(parseISO(datetime), "k:mm", { locale });
    }
  }
};
</script>
