<script setup>

import axios from 'axios';
import { ref, onMounted } from 'vue';
import { router, Link, usePage } from '@inertiajs/vue3';
import PlayerContainer from '@/Components/PlayerContainer.vue';
import NavigationBar from '@/Components/NavigationBar.vue';
import HomeCover from '@/Components/HomeCover.vue';
import { loadLanguageAsync } from 'laravel-vue-i18n';
import { Modal } from 'bootstrap';

let props = defineProps({ 
  footble: Number,
  player: Object
});

const modalResult = ref(null);
const modalResultBackground = ref('wrong-guess');

const page = usePage();

const playGame = ref(false); 
const gameFinished = ref(false);


const searchQuery = ref('');
const suggestions = ref([]);
const showSuggestions = ref(false);
const guesses = ref([]);

const playTheGame = () => {

    playGame.value = true;

    gameFinished.value = checkGameFinished();

    if(gameFinished.value) showModal();

}

const checkGameFinished = () => {
    let result = false;
    const wonGame = guesses.value.find((elem) => elem.id === props.player.id);
    if(wonGame) {
      modalResultBackground.value = 'right-guess';
      result = true;
    } 

    if(guesses.value.length > 9) result = true;

    return result;
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

  if(!gameFinished.value) {
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

      // Si ha acertado, mostrar el resultado
      if(result.match) {
        modalResultBackground.value = 'right-guess';
        showModal();
      }

      // Si ha hecho 10 intentos, no puede jugar más
      if(guesses.value.length == 10) {
        gameFinished.value = true;

        showModal();
      }

  } else {
    showSuggestions.value = false;
  }


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

    // Método para mostrar el modal
    const showModal = () => {
      modalResult.value.show();
    };

    // Método para ocultar el modal
    const hideModal = () => {
      modalResult.value.hide();
    };

onMounted(() => {
    getDayGuesses();

    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;

    document.addEventListener('click', (event) => {
        if (!event.target.closest('.input-dropdown-container')) {
          showSuggestions.value = false;
        }
    });

    modalResult.value = new Modal(document.getElementById('staticBackdrop'), {
      keyboard: false // Opciones adicionales si las necesitas
    });

    console.log('modalResult: ', modalResult);
});
</script>

<template>
  <NavigationBar @locale-changed="changeLocale" />

  <main class="container text-bg-dark mt-5 p-4">
    <div class="row pt-4">
      <div class="col-md-3 text-center">
        <p> {{ $t('left column') }}</p>
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
          Launch static backdrop modal
        </button>
      </div>
      <div class="col-md-6">

        <HomeCover v-if="!playGame" @play-game="playTheGame" />

        <div id="game-container" v-if="playGame">
            <div class="guesses-remaining" v-if="guesses.length < 10">{{ $t('Guess') + ' ' + (guesses.length + 1) + ' ' + $t('of') }} 10</div>
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
        <button type="button" class="btn btn-warning" @click="showModal">
          Launch static backdrop modal
        </button>
      </div>
    </div>
  </main>

<!-- Modal -->
<div class="modal text-center fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content ">
      <div class="modal-header" :class="modalResultBackground">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">{{ $t("Today's footballer is") }}...</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" :class="modalResultBackground">
        <img :src="'/img/players/' + props.player.photo" :alt="props.player.name" class="result-thumb">
        <h2 class="mt-2">{{ props.player.name }}</h2>
      </div>
      <div class="modal-footer" :class="modalResultBackground">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Understood</button>
      </div>
    </div>
  </div>
</div>


</template>
<style scoped>
#suggestions {
  display: block !important;
  position: absolute;
  z-index: 1000;
}

.modal-content {
  color: #FFF;
}

.modal-body img {
  border: 1px solid #FFF;
}

.result-thumb {
  max-height: 250px;
}
</style>
