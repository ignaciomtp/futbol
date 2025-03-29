<script setup>

import axios from 'axios';
import { ref, onMounted } from 'vue';
import { router, Link, usePage } from '@inertiajs/vue3';
import PlayerContainer from '@/Components/PlayerContainer.vue';
import NavigationBar from '@/Components/NavigationBar.vue';
import HomeCover from '@/Components/HomeCover.vue';
import { loadLanguageAsync } from 'laravel-vue-i18n';

let props = defineProps({ footble: Number });

const page = usePage();

const playGame = ref(false); 

const searchQuery = ref('');
const suggestions = ref([]);
const showSuggestions = ref(false);
const guesses = ref([]);

const playTheGame = () => {
    playGame.value = true;
}

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

  // Guardar intentos en localStorage
  saveDayGuesses();
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

const changeLocale = async (locale) => {
    try {
        const response = await axios.post('/change-locale/', { 
            locale: locale
        });

        if (response.data.message === 'locale changed') {
            // Cambiar el idioma en el frontend
            loadLanguageAsync(locale);

        }
    } catch (error) {
        console.error('Error changing locale:', error);
    }
}

const getDayGuesses = () => {
    let dayGuesses = localStorage.getItem('footbleDay');
    if (dayGuesses) dayGuesses = JSON.parse(dayGuesses);
    console.log('Day guesses: ', dayGuesses);
    if (dayGuesses && dayGuesses.day == props.footble) {
        // Asegurar que cada jugador tenga las propiedades necesarias
        guesses.value = dayGuesses.guesses.map(player => ({
            ...player,
            isFlipping: false // Valor por defecto al cargar
        }));
    }
};

const saveDayGuesses = () => {
    let newDayGuesses = {
        day: props.footble,
        guesses: guesses.value
    };
    localStorage.setItem('footbleDay', JSON.stringify(newDayGuesses));
}

//const saveDayResult = () => {}

onMounted(() => {
    getDayGuesses();

    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;

    document.addEventListener('click', (event) => {
        if (!event.target.closest('.input-dropdown-container')) {
          showSuggestions.value = false;
        }
    });
});
</script>

<template>
  <NavigationBar @locale-changed="changeLocale" />

  <main class="container text-bg-dark mt-5 p-4">
    <div class="row pt-4">
      <div class="col-md-3 text-center">
        <p> {{ $t('left column') }}</p>
      </div>
      <div class="col-md-6">

        <HomeCover v-if="!playGame" @play-game="playTheGame" />

        <div id="game-container" v-if="playGame">
            <div class="guesses-remaining">{{ $t('Guess') + ' ' + (guesses.length + 1) + ' ' + $t('of') }} 10</div>
            <div class="input-group mb-3 input-dropdown-container pl-5 pr-5">
              <input type="text" class="searchbox" 
                :placeholder="$t('Type a footballer name here') + '...'" 
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

            <div class="mt-3 text-center" v-if="!guesses.length">
                <p class="m-4">{{ $t('Guess the footballer of the day') }}.</p>
                <p class="m-4">{{ $t('Search for an footballer to make your first guess') }}.</p>
            </div>

            <div class="mt-5" id="guesses">
              <PlayerContainer v-for="player in guesses" :key="player.id" :player="player" />
            </div>
        </div>


      </div>
      <div class="col-md-3 text-center">
        <p> {{ $t('right column') }}</p>
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
