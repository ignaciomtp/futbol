<script setup>
defineProps({
  country_name: String,
  country_flag: String,
  guessClass: String,
  isFlipping: Boolean
});
</script>

<template>
  <div :class="['player-data-item', 'text-center', guessClass, { 'flip': isFlipping }]">
    <h3>{{ $t(country_name) }}</h3>
    <img class="borde2" :src="country_flag" :alt="country_name">
  </div>
</template>