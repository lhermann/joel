<template>
  <li
    :class="{ 'is-active': currentSlide === index }"
    @click="$emit('update:currentSlide', index)"
  >
    <button class="c-btn c-btn--dark c-slider__btn">
      <div class="c-slider__btn__fill"></div>
    </button>
  </li>
</template>

<script>
export default {
  name: "SliderNavComponent",
  props: {
    index: { type: Number },
    currentSlide: { type: Number }
  }
};
</script>
