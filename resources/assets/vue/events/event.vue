<template>
  <a class="c-card c-card--clickable" :href="event.url">
    <div class="c-card__content u-ph-">
      <div class="o-flag">
        <div class="o-flag__img" v-html="event.thumbnail"></div>
        <div class="o-flag__body">
          <div class="u-text- u-medium u-brand">{{ weekday }}, {{ date }}</div>
          <div class="u-text u-medium u-mv---">{{ event.post_title }}</div>
          <div v-if="event.venue" class="u-text-- u-muted">
            <span class="u-ic-room"></span> {{ event.venue }}
          </div>
        </div>
      </div>
    </div>
  </a>
</template>

<script>
import format from "date-fns/format";
import locale from "date-fns/locale/de";
import parseISO from "date-fns/parseISO";

export default {
  props: {
    event: Object
  },
  computed: {
    thumbnail() {
      return this.event.thumbnail.replace("%%class%%", "u-rounded");
    },
    weekday() {
      return format(parseISO(this.event.StartDate), "EEEE", { locale });
    },
    date() {
      return format(parseISO(this.event.StartDate), "d. MMMM Y", { locale });
    }
  }
};
</script>
