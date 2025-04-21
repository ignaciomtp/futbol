<script setup>
import axios from 'axios';
import { ref, onMounted  } from 'vue';

let props = defineProps({ 
  footble: Number,
  player: Object,
  showSuggestions: Boolean,
  isMobile: Boolean
});


const emit = defineEmits(['selected', 'toggleSugestions']);

const searchQuery = ref('');
const suggestions = ref([]);


const selected = (player) => {
  emit('selected', player);
};

const toggleSugestions = (val) => {
  emit('toggleSugestions', val);
}

// buscar jugadores por nombre
const searchPlayers = async () => {

  if (searchQuery.value.length < 1) {
    suggestions.value = [];
    //showSuggestions.value = false;
    toggleSugestions(false);
    return;
  }

  try {
    const response = await axios.post('/player/search', { 
      name: searchQuery.value 
    });

    suggestions.value = response.data;
    toggleSugestions(true);
    //showSuggestions.value = true;
    
    // On mobile, we want to keep the keyboard open
    if (props.isMobile.value) {
      document.activeElement.blur();
      setTimeout(() => {
        document.getElementById('mainSearchBox').focus();
      }, 100);
    }
  } catch (error) {
    console.error(error);
  }
};

onMounted(() => {


    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;


});


</script>

<template>
  <input type="text" class="searchbox" id="mainSearchBox"
    :placeholder="$t('Type a footballer name here') + '...'" 
    v-model="searchQuery" 
    @input="searchPlayers"
    autocomplete="off">
  <span class="searchbox-button">
    <i class="bi bi-search text-bg-light"></i>
  </span>
  <div class="dropdown w-100">
    <!-- Desktop dropdown -->
    <ul class="dropdown-menu desktop-suggestions" v-show="props.showSuggestions && suggestions.length">
      <li v-for="suggestedPlayer in suggestions" :key="suggestedPlayer.id">
        <div class="dropdown-item dropdown-player-item" @click="selected(suggestedPlayer)">
          <img :src="`/img/players/${suggestedPlayer.photo}`" :alt="suggestedPlayer.name" class="tinythumb" style="float: right">
          {{ suggestedPlayer.name }}
        </div>
      </li>
    </ul>


  </div>
</template>
<style scoped>

.desktop-suggestions {
  display: block;
  position: absolute;
  z-index: 1000;
  width: 100%;
  overflow-y: auto;
}


#suggestions {
  display: block !important;
  position: absolute;
  z-index: 1000;
}

/* Hide desktop suggestions on mobile 
@media (max-width: 768px) {
  .desktop-suggestions {
    display: none !important;
  }
}
*/

</style>
