<script setup>
defineProps({
  message: {
    type: String,
    default: 'Vue 3 está listo para usar en esta vista.',
  },
});
</script>

<template>
  <div>{{ message }}</div>
</template>
