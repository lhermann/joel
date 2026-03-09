<template>
  <ul class="ais-hierarchical-menu--body">
    <li v-for="item in items" :key="item.value" class="ais-hierarchical-menu--item">
      <a
        class="ais-hierarchical-menu--link u-truncate"
        :class="{ 'is-active': item.isRefined }"
        href="#"
        @click.prevent="refine(item.value)"
      >
        {{ item.label }}
        <span class="ais-hierarchical-menu--count">{{ item.count }}</span>
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
