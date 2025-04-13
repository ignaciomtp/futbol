<script setup>
defineProps({
  country_name: String,
  country_flag: String,
  birth_country_name: String,
  birth_country_flag: String,
  guessClass: String,
  isFlipping: Boolean
});
</script>

<template>
  <div :class="['player-data-item', 'text-center', guessClass, { 'flip': isFlipping }]">

    <div v-if="country_name === birth_country_name">
      <h3>{{ $t(country_name) }}</h3>
      <img class="borde2" :src="country_flag" :alt="country_name">
    </div>

    <div v-else>
      <h3>{{ $t(birth_country_name) }} / {{ $t(country_name) }}</h3>
      <img class="borde2 smaller" :src="birth_country_flag" :alt="birth_country_name">
      <img class="borde2 smaller" :src="country_flag" :alt="country_name">
    </div>

    
  </div>
</template>

<style scoped>

.smaller {
  width: 28px;
  height: 19px;
  margin: 0 3px;
}

@media screen and (max-width: 450px) {
  h3 {
    margin-bottom: .1rem;

  }

  img {
    max-width: 30px;
  }

}  

</style>