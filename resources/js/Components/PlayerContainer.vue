<script setup>
import CountryDataContainer from '@/Components/CountryDataContainer.vue';
import ClubsDataContainer from '@/Components/ClubsDataContainer.vue';
import ActiveDataContainer from '@/Components/ActiveDataContainer.vue';
import PositionDataContainer from '@/Components/PositionDataContainer.vue';
import TitlesDataContainer from '@/Components/TitlesDataContainer.vue';

const props = defineProps({
  player: Object,
});

// Función para determinar las clases según el resultado
const getGuessClass = (result) => {
  if (!props.player.checkResult) return 'wrong-guess'; // Valor por defecto si no hay resultado
  if (props.player.checkResult?.match) {
    return 'right-guess';
  }
  if (result === 'right') return 'right-guess';
  if (result === 'partial') return 'partial-guess';
  return 'wrong-guess';
};

// Función para determinar si debe aplicarse la animación flip
const shouldFlip = (result) => {
  const classResult = getGuessClass(result);
  // Solo aplicamos flip si el resultado es 'right-guess' o 'partial-guess'
  return props.player.isFlipping && (classResult === 'right-guess' || classResult === 'partial-guess');
};



</script>

<template>
  <div class="player-div-box p-3 mt-4 mb-4">
    <div class="data-row data-player-id">
      <img :src="'/img/players/' + player.photo" :alt="player.name" class="round-thumb">
      <h2 class="text-bg-dark">{{ player.name }}</h2>
    </div>
    <div class="player-data data-row mt-2">
      <CountryDataContainer  
        :country_name="player.country"
        :country_flag="player.country_flag"
        :guess-class="getGuessClass(player.checkResult?.country)"
        :is-flipping="shouldFlip(player.checkResult?.country)"
      />

      <ActiveDataContainer 
        :debut_year="player.debut_season"
        :last_year="player.last_season"
        :guess-class="getGuessClass(player.checkResult?.active)"
        :is-flipping="shouldFlip(player.checkResult?.active)"
        :era="player.era"
      />

      <PositionDataContainer 
        :position="player.position"
        :guess-class="getGuessClass(player.checkResult?.position)"
        :is-flipping="shouldFlip(player.checkResult?.position)"
      />

      <ClubsDataContainer 
        :clubs="player.clubs"
        :guess-class="getGuessClass(player.checkResult?.clubs)"
        :is-flipping="shouldFlip(player.checkResult?.clubs)"
      />

      <TitlesDataContainer 
        :titles="player.titles"
        :guess-class="getGuessClass(player.checkResult?.titles)"
        :is-flipping="shouldFlip(player.checkResult?.titles)"
      />
    </div>
  </div>  
</template>
<style scoped>
  .text-bg-dark {
    background-color: #141C23 !important;
  }
</style>