<template>
  <span>
    <slot :live="live" :loading="loading"></slot>
  </span>
</template>

<script>
import axios from "axios";

export default {
  name: "Streamcheck",
  props: {
    stream: { type: String, default: "" }
  },
  data() {
    return {
      loading: true,
      live: false,
    };
  },
  mounted() {
    this.loading = false;
    // axios
    //   .get(this.stream, {
    //     baseURL: "//streamcheck.joelmedia.de/"
    //   })
    //   .then(response => (this.live = response.data.live))
    //   .catch(error => console.log({ error }))
    //   .then(() => (this.loading = false));
  }
};
</script>
