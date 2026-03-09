<template>
  <ul>
    <li v-for="item in items" :key="item.value">
      <a
        class="u-truncate"
        :class="{ 'is-active': item.isRefined }"
        href="#"
        @click.prevent="refine(item.value)"
      >
        {{ item.label }} ({{ item.count }})
      </a>
      <JoSearchTopicTree
        v-if="item.data && item.data.length"
        :items="item.data"
        :refine="refine"
      />
    </li>
  </ul>
</template>

<script setup>
defineProps({
  items: { type: Array, required: true },
  refine: { type: Function, required: true },
})
</script>
