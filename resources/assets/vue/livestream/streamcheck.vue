<template>
  <span>
    <slot :live="live" :loading="loading"></slot>
  </span>
</template>

<script>
import axios from "axios";

export default {
  name: "Streamcheck",
  data() {
    return {
      loading: true,
      live: false,
    };
  },
  async mounted() {
    axios
      .get("//streamcheck.joelmedia.de/api/v1/status")
      .then(({ data }) => (this.live = data.live))
      .catch(error => console.log(error))
      .then(() => (this.loading = false));
  }
};
</script>
