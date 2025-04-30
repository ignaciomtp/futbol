<script setup>
import axios from 'axios';
import { ref, onMounted, nextTick } from 'vue';
import { debounce } from 'lodash';

let props = defineProps({ 
  footble: Number,
  player: Object,
  showSuggestions: Boolean,
  isMobile: Boolean,
  disabled: {
    type: Boolean,
    default: false
  }
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

// forzar ocultar el teclado en móviles
const handleSearch = async () => {
  console.log('enter');

  document.getElementById('mainSearchBox').blur();

  await searchPlayers(); // esperar a que termine la búsqueda

  // Esperar a que Vue actualice el DOM
  await nextTick();

  // Ahora seleccionar el primer <li> y hacer focus
  const playersList = document.querySelector('.desktop-suggestions');
  const firstLi = playersList?.querySelector('li');

  firstLi?.setAttribute('tabindex', '-1'); // Hace el <li> focusable sin estar en el tab natural
  firstLi?.focus();
};

// buscar jugadores por nombre
const searchPlayers = debounce(async () => {

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
    

  } catch (error) {
    console.error(error);
  }
}, 300); // Espera 300ms antes de ejecutar

onMounted(() => {


    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;


});


</script>

<template>
  <input type="text" class="searchbox" id="mainSearchBox"
    :placeholder="$t('Type a footballer name here') + '...'" 
    v-model="searchQuery" 
    @input="searchPlayers"
    @keyup.enter="handleSearch"
    :disabled="props.disabled"
    autocomplete="off">
  <span class="searchbox-button">
    <i class="bi bi-search text-bg-light" :class="{textBgLightDisabled: props.disabled}"></i>
  </span>
  <div class="dropdown w-100">
    <!-- Desktop dropdown -->
    <ul class="dropdown-menu desktop-suggestions" v-show="props.showSuggestions && suggestions.length" ref="playerslist">
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


.textBgLightDisabled {
  color: light-dark(rgba(239, 239, 239, 0.3), rgba(59, 59, 59, 0.3)) !important;
    background-color: #5f6265 !important;
}


#suggestions {
  display: block !important;
  position: absolute;
  z-index: 1000;
}

.desktop-suggestions li:focus {
  outline: none; /* quitar el borde negro */
  background-color: #eee; /* color de fondo gris clarito */
}

</style>
