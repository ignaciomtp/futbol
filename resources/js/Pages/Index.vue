<script setup>
import axios from 'axios';
import { ref, onMounted } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import PlayerContainer from '@/Components/PlayerContainer.vue';

const searchQuery = ref('');
const suggestions = ref([]);
const showSuggestions = ref(false);
const guesses = ref([]);
const guessResults = ref({});


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
    console.log('Sugerencias recibidas:', response.data);
    suggestions.value = response.data;
    showSuggestions.value = true;
  } catch (error) {
    console.error(error);
  }
};

const selectPlayer = async (player) => {

    console.log('Selected player photo: ', player.photo);

  guesses.value.unshift(player);
  searchQuery.value = '';
  suggestions.value = [];
  showSuggestions.value = false;
  
  await checkGuess(player.id);
};

const checkGuess = async (playerId) => {
  try {
    const response = await axios.get(`/checkresult/${playerId}`);

    console.log('Resultado checkeo: ', response.data);

    guessResults.value[playerId] = response.data;
    
    // Remove flip class after animation
    setTimeout(() => {
      document.querySelectorAll('.player-data-item, .player-data-item-wide').forEach(el => {
        el.classList.remove('flip');
      });
    }, 250);
  } catch (error) {
    console.error(error);
  }
};

const getGuessClass = (playerId, field) => {
  const result = guessResults.value[playerId];
  if (!result) return 'wrong-guess';
  
  if (result.match) return 'right-guess flip';
  
  const fieldResult = result[field];
  if (fieldResult === 'right') return 'right-guess flip';
  if (fieldResult === 'partial') return 'partial-guess flip';
  
  return 'wrong-guess';
};

// Close suggestions when clicking outside
onMounted(() => {
  axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;

    document.addEventListener('click', (event) => {
        if (!event.target.closest('.input-dropdown-container')) {
            console.log('Cerrando sugerencias');
            showSuggestions.value = false;
        }
    });
});

</script>
<template>
    <nav class="navbar navbar-expand navbar-dark bg-dark" aria-label="Second navbar example">
        <div class="container-fluid">

          <a class="navbar-brand" href="#">Always expand</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample02" aria-controls="navbarsExample02" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse" id="navbarsExample02">
            <ul class="navbar-nav me-auto">
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#">Home</a>
              </li>

            </ul>


          </div>
        </div>
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
                    <div class="dropdown w-100 ">
                        <ul class="dropdown-menu " id="suggestions" v-if="showSuggestions">
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
                    <PlayerContainer v-for="(player, index) in guesses" :key="index + 1" :player="player" />
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