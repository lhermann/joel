<template>
  <ul class="o-list-bare o-flex o-flex--large o-flex-wrap u-mb0">
    <li v-for="event in events" :key="event.id" class="o-flex__item u-1/3">
      <event :event="event" />
    </li>
    <li v-if="!events.length" class="o-flex__item u-1/1">
      <div class="u-p u-center">
        <div class="c-spinner"></div>
      </div>
    </li>
  </ul>
</template>

<script>
import axios from "axios";
import Event from "./event.vue";

export default {
  components: { Event },
  props: {
    params: Object
  },
  data() {
    return {
      events: []
    };
  },
  created() {
    this.params.thumbnail_size = "square80";
    axios
      .get("joel/v1/events", { params: this.params })
      .then(response => (this.events = response.data))
      .catch(error => console.log({ error }));
  }
};
</script>
