<script setup>

import axios from 'axios';
import { ref, onMounted  } from 'vue';
import { router, Link, usePage } from '@inertiajs/vue3';
import { Modal } from 'bootstrap';
import PlayerContainer from '@/Components/PlayerContainer.vue';
import NavigationBar from '@/Components/NavigationBar.vue';
import TimerComponent from '@/Components/TimerComponent.vue';
import HomeCover from '@/Components/HomeCover.vue';


let props = defineProps({ 
  footble: Number,
  player: Object
});

const modalResult = ref(null);
//const modalResult2 = ref(null);
const modalResultBackground = ref('wrong-guess');

const page = usePage();

const playGame = ref(false); 
const gameFinished = ref(false);
const userWon = ref(false);


// contador para mostrar cuanto falta hasta el próximo footble
const startCounter = ref(false);

const shareResultText = ref('Share result');

const searchQuery = ref('');
const suggestions = ref([]);
const showSuggestions = ref(false);
const guesses = ref([]);

// Variable para controlar el evento actualizar estadísticas
const updateStatsTrigger = ref(false);

const triggerStatsUpdate = () => {
  updateStatsTrigger.value = !updateStatsTrigger.value; // Cambia el valor para disparar el watcher
};

// copiar resultado del juego para compartir
const shareResult = async () => {
  let res = [];

  if(userWon.value) {
    res.push('✔');
  } else {
    res.push('❌');
  }

  for(let i = 0; i < guesses.value.length - 1; i++) {
    res.unshift('⬜');
  }

  let texto = `Footble #${props.footble}⚽

${res.join('')}

footble.io`;

  try {
      await navigator.clipboard.writeText(texto);
      shareResultText.value = 'Copied result';
  } catch (err) {
      console.error('Error al copiar al portapapeles: ', err);
  }
}

// empezar a jugar
const playTheGame = () => {

    playGame.value = true;

    gameFinished.value = checkGameFinished();

    if(gameFinished.value) showModal();

}

// comprobar si el usuario ha ganado
const checkUserWon = () => {
  const wonGame = guesses.value.find((elem) => elem.id === props.player.id);
  if(wonGame) {
    userWon.value = true;
    return true;
  } 

  return false;
  
}

// actualizar racha
const updateStreak = (result) => {
  let currentStreak = localStorage.getItem('footbleCurrentStreak');
  if(!currentStreak) {
    currentStreak = 0;
  } else {
    currentStreak = parseInt(currentStreak);
  }

  if(result) {
    currentStreak++;
  } else {
    currentStreak = 0;
  }

  localStorage.setItem('footbleCurrentStreak', currentStreak);

  let maxStreak = localStorage.getItem('footbleMaxStreak');

  if(!maxStreak) {
    maxStreak = 0;
  } else {
    maxStreak = parseInt(maxStreak);
  }

  if(currentStreak > maxStreak) {
    maxStreak = currentStreak;
  } 

  localStorage.setItem('footbleMaxStreak', maxStreak);

}

// comprobar si la partida del día ha terminado
const checkGameFinished = () => {
    let result = false;
    if(checkUserWon()) {
      modalResultBackground.value = 'right-guess';
      result = true;
    } 

    if(guesses.value.length > 9) result = true;

    return result;
}

// buscar jugadores por nombre
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

const selectPlayer = async (selectedPlayer) => {

  if(!gameFinished.value) {
      // comprobar que no esté ya seleccionado
      if(guesses.value.length) {
        let idx = guesses.value.findIndex((elem) => elem.id === selectedPlayer.id);
        if(idx != -1){
          showSuggestions.value = false;
          return;
        }
      }

      // Ejecutar checkGuess y almacenar el resultado en el objeto player
      const result = await checkGuess(selectedPlayer.id);
      selectedPlayer.checkResult = result; // Agregar el resultado al jugador
      selectedPlayer.isFlipping = true;    // Activar la animación

      if(result.match == false && result.active != 'right') {
        if(selectedPlayer.debut_season < props.player.debut_season) selectedPlayer['era'] = 'anterior';

        if(selectedPlayer.last_season == null || selectedPlayer.last_season > props.player.last_season) selectedPlayer['era'] = 'posterior';
      }

      guesses.value.unshift(selectedPlayer);
      searchQuery.value = '';
      suggestions.value = [];
      showSuggestions.value = false;

      // Eliminar la clase flip después de 250ms
      setTimeout(() => {
        selectedPlayer.isFlipping = false;
      }, 250);

      // Guardar intentos en localStorage
      saveDayGuesses();    

      // Si ha acertado, mostrar el resultado y guardar estadísticas
      if(result.match) {
        modalResultBackground.value = 'right-guess';
        gameFinished.value = true;
        userWon.value = true;
        updateStreak(true);
        saveDayResult();
        triggerStatsUpdate();
        showModal();
      }

      // Si ha hecho 10 intentos, no puede jugar más
      if(guesses.value.length == 10) {
        gameFinished.value = true;
        updateStreak(false);
        saveDayResult();
        triggerStatsUpdate();
        showModal();
      }

  } else {
    showSuggestions.value = false;
    showModal();
  }

};



// comprobar intento
const checkGuess = async (playerId) => {
  try {
    const response = await axios.get(`/checkresult/${playerId}`);
    return response.data; // Devolver el resultado para almacenarlo
  } catch (error) {
    console.error(error);
    return null;
  }
};


// cargar los intentos del día si existen
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

// guardar los intentos del día
const saveDayGuesses = () => {
    let newDayGuesses = {
        day: props.footble,
        guesses: guesses.value
    };
    localStorage.setItem('footbleDay', JSON.stringify(newDayGuesses));

    checkGameFinished();
}

// guadar en el histórico al terminar el juego
const saveDayResult = (date = getDateOfDay()) => {
  let dayData = {
    date: date,
    done: true,
    won: userWon.value,
    idPlayer: props.footble,
    photo: props.player.photo,
    name: props.player.name,
    attempts: guesses.value.length
  }

  let historic = localStorage.getItem('FootbleHistoric');

  if(historic){
    historic = JSON.parse(historic);
  } else {
    historic = [];
  }

  historic.unshift(dayData);

  localStorage.setItem('FootbleHistoric', JSON.stringify(historic));

}

// Método para mostrar el modal
const showModal = () => {
  startCounter.value = true;
  modalResult.value.show();
};

// Método para ocultar el modal
const hideModal = () => {
  startCounter.value = false;
  document.getElementById("mainSearchBox").focus();
  modalResult.value.hide();
};

// mostrar modal instrucciones
const showInstructions = () => {
    let instructionsModal = new Modal(document.getElementById('instructions'));
    instructionsModal.show();
}

// devolver la fecha del día
const getDateOfDay = () => {
    const hoy = new Date();
    const dia = String(hoy.getDate()).padStart(2, '0');
    const mes = String(hoy.getMonth() + 1).padStart(2, '0');
    const anio = hoy.getFullYear();
    return `${dia}/${mes}/${anio}`;
}

const setCookie = (cname, cvalue, exdays) => {
  console.log('This is setCookie');
  const d = new Date();
  d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
  let expires = "expires="+d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

const borrarCookie = () => {
  setCookie("footble", "noconsent", 365);
}


onMounted(() => {
    getDayGuesses();

    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;

    document.addEventListener('click', (event) => {
        if (!event.target.closest('.input-dropdown-container')) {
          showSuggestions.value = false;
        }
    });

    modalResult.value = new Modal(document.getElementById('staticBackdrop'), {
      focus: false, // Desactiva el enfoque automático
      keyboard: false // Opciones adicionales si las necesitas
    });

/*
    modalResult2.value = document.getElementById('staticBackdrop');

    modalResult2.addEventListener('hidden.bs.modal', () => {
      
      openModalBtn.focus();
    }); */

});
</script>

<template>
  <NavigationBar :update-trigger="updateStatsTrigger" />

  <main class="container text-bg-dark mt-5 p-4">
    <div class="row pt-4">
      <div class="col-lg-3 text-center">
        
      </div>
      <div class="col-lg-6">

        <HomeCover v-if="!playGame" @play-game="playTheGame" />

        <div id="game-container" v-if="playGame">
            <div class="guesses-remaining" v-if="guesses.length < 10">{{ $t('Guess') + ' ' + (guesses.length + 1) + ' ' + $t('of') }} 10</div>
            <div class="input-group mb-3 input-dropdown-container pl-5 pr-5">
              <input type="text" class="searchbox" id="mainSearchBox"
                :placeholder="$t('Type a footballer name here') + '...'" 
                v-model="searchQuery" 
                @input="searchPlayers"
                autocomplete="off">
              <span class="searchbox-button">
                <i class="bi bi-search text-bg-light"></i>
              </span>
              <div class="dropdown w-100">
                <ul class="dropdown-menu" id="suggestions" v-if="showSuggestions">
                  <li v-for="suggestedPlayer in suggestions" :key="player.id">
                    <div class="dropdown-item dropdown-player-item" @click="selectPlayer(suggestedPlayer)">
                      <img :src="`/img/players/${suggestedPlayer.photo}`" :alt="suggestedPlayer.name" class="tinythumb" style="float: right">
                      {{ suggestedPlayer.name }}
                    </div>
                  </li>
                </ul>
              </div>
            </div>

            <div class="mt-3 text-center hints" v-if="!guesses.length">
                <p class="m-4">{{ $t('Guess the footballer of the day') }}.</p>
                <p class="m-4">{{ $t('Search for an footballer to make your first guess') }}.</p>
            </div>

            <div class="mt-3" id="guesses">
              <PlayerContainer v-for="player in guesses" :key="player.id" :player="player"  />
            </div>

            <div class="mt-3 " v-if="guesses.length == 1">
              <div class="data-row2">
                <div class="player-data-item text-center wrong-guess">{{ $t('No match') }}</div>
                <div class="player-data-item text-center partial-guess">{{ $t('Close') }}</div>
                <div class="player-data-item text-center right-guess">{{ $t('Match') }}</div>      
              </div>

              <p class="mt-2 text-center hometext">{{ $t('Use the matching attributes to make more guesses') }}. {{ $t('Good luck!') }}</p>
                      
            </div>
        </div>

      </div>
      <div class="col-lg-3 text-center">
        <!--<p> {{ $t('right column') }}</p>
        <button type="button" class="btn btn-warning" @click="showModal">
          Launch static backdrop modal
        </button> -->
      </div>
    </div>
  </main>

<!-- Modal Resultado -->
<div class="modal text-center fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content ">
      <div class="modal-header " :class="modalResultBackground">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">{{ $t("Today's footballer is") }}...</h1>
        <button type="button" class="btn-close" @click="hideModal"></button>
      </div>
      <div class="modal-body " :class="modalResultBackground">
        <img :src="'/img/players/' + props.player.photo" :alt="props.player.name" class="result-thumb">
        <h2 class="mt-2">{{ props.player.name }}</h2>
      </div>
      <div class="my-modal-bottom " :class="modalResultBackground">
        <div class="mt-2 mb-2">
          <button type="button" class="btn btn-dark" @click="shareResult">{{ $t(shareResultText) }}</button>
        </div>
        <div class="mb-4">
          <TimerComponent :start="startCounter" />
        </div>
        
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

.data-row2 {
  display: flex;
  justify-content: space-evenly;
}

.modal-content {
  color: #FFF;
}

.modal-body {
  padding-bottom: 0;
}

.instructions img {
  max-width: 400px;
}

.result-thumb {
  max-height: 250px;
  border: 1px solid #FFF;
}

.btn-close {
    filter: invert(1) grayscale(100%) brightness(200%);
}

.sidebar ul {
  margin: 30px 0;
  list-style: none;
}

.sidebar ul li {
  margin: 20px 0;
}

.sidebar p{
  color: #b5b5b5;
}

.sidebar h3 {
  padding-left: 20px;
}

.btn {
  border-radius: 100px;
  min-width: 150px;
  font-weight: 700;
}




</style>
