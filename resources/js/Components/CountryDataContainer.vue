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

<style scoped>

@media screen and (max-width: 450px) {
  h3 {
    margin-bottom: .1rem;

  }

  img {
    max-width: 30px;
  }

}  

</style>