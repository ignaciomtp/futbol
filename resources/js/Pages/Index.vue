<script setup>

import axios from 'axios';
import { ref, onMounted } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import PlayerContainer from '@/Components/PlayerContainer.vue';

const searchQuery = ref('');
const suggestions = ref([]);
const showSuggestions = ref(false);
const guesses = ref([]);

const searchPlayers = async () => {
  if (searchQuery.value.length < 1) {
    suggestions.value = [];
    showSuggestions.value = false;
    return;
  }

  try {
    const response = await axios.post('/player/search/', { 
      name: searchQuery.value 
    });
    suggestions.value = response.data;
    showSuggestions.value = true;
  } catch (error) {
    console.error(error);
  }
};

const selectPlayer = async (player) => {
  // Ejecutar checkGuess y almacenar el resultado en el objeto player
  const result = await checkGuess(player.id);
  player.checkResult = result; // Agregar el resultado al jugador
  player.isFlipping = true;    // Activar la animación

  guesses.value.unshift(player);
  searchQuery.value = '';
  suggestions.value = [];
  showSuggestions.value = false;

  // Eliminar la clase flip después de 250ms
  setTimeout(() => {
    player.isFlipping = false;
  }, 250);
};

const checkGuess = async (playerId) => {
  try {
    const response = await axios.get(`/checkresult/${playerId}`);
    return response.data; // Devolver el resultado para almacenarlo
  } catch (error) {
    console.error(error);
    return null;
  }
};

onMounted(() => {
  axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;

  document.addEventListener('click', (event) => {
    if (!event.target.closest('.input-dropdown-container')) {
      showSuggestions.value = false;
    }
  });
});
</script>

<template>
  <nav class="navbar navbar-expand navbar-dark bg-dark" aria-label="Second navbar example">
    <!-- Tu navbar existente -->
  </nav>

  <main class="container text-bg-dark p-4">
    <div class="row pt-4">
      <div class="col-md-3 text-center">
        <p>left column</p>
      </div>
      <div class="col-md-6">
        <div class="input-group mb-3 input-dropdown-container pl-5 pr-5">
          <input type="text" class="searchbox" 
            placeholder="Type a footballer name here..." 
            v-model="searchQuery" 
            @input="searchPlayers"
            autocomplete="off">
          <span class="searchbox-button">
            <i class="bi bi-search text-bg-light"></i>
          </span>
          <div class="dropdown w-100">
            <ul class="dropdown-menu" id="suggestions" v-if="showSuggestions">
              <li v-for="player in suggestions" :key="player.id">
                <div class="dropdown-item dropdown-player-item" @click="selectPlayer(player)">
                  <img :src="`/img/players/${player.photo}`" :alt="player.name" class="tinythumb" style="float: right">
                  {{ player.name }}
                </div>
              </li>
            </ul>
          </div>
        </div>

        <div class="mt-5" id="guesses">
          <PlayerContainer v-for="player in guesses" :key="player.id" :player="player" />
        </div>
      </div>
      <div class="col-md-3 text-center">
        <p>right column</p>
      </div>
    </div>
  </main>
</template>
<style scoped>
#suggestions {
  display: block !important;
  position: absolute;
  z-index: 1000;
}
</style>
