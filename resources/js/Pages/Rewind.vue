<script setup>
import axios from 'axios';
import { ref, onMounted, nextTick } from 'vue';
import { router, Link, usePage } from '@inertiajs/vue3';
import { Modal } from 'bootstrap';
import { debounce } from 'lodash';
import NavigationBar from '@/Components/NavigationBar.vue';
import LastWeekComponent from '@/Components/LastWeekComponent.vue';
import PlayerContainer from '@/Components/PlayerContainer.vue';

let props = defineProps({ 
  footble: Number
});

let tempSearchVal = '';

const page = usePage();
const modalResult = ref(null);
const modalResultBackground = ref('wrong-guess');

const targetPlayer = ref({
	name: 'Pepito',
	photo: 'user.jpg',
	debut_season: null,
	last_season: null

});

const returnTargetName = () => {
	return targetPlayer.value.name;
}

const returnTargetPhoto = () => {
	return targetPlayer.value.photo;
}

const playGame = ref(true); 
const gameFinished = ref(false);

const playedAll = ref(false);

const searchQuery = ref('');
const suggestions = ref([]);
const showSuggestions = ref(false);
const guesses = ref([]);
const weekFootbles = ref([]);

const selectedPastFootble = ref(null);

// Variable para controlar el evento actualizar estadísticas
const updateStatsTrigger = ref(false);

const triggerStatsUpdate = () => {
  updateStatsTrigger.value = !updateStatsTrigger.value; // Cambia el valor para disparar el watcher
};

const checkCanPlay = () => {
	let result = true;

	if(guesses.value.length == 10) result = false;

	let winner = guesses.value.find((elem) => elem.idPlayer == selectedPastFootble.value);

	if(winner) result = false;

	return result;
}

// establecer el footble del día
const setSelectedPastFootble = async (id) => {

	selectedPastFootble.value = id;

	const footble = await axios.get(`/getplayer/${id}/`);

	targetPlayer.value.name = footble.data.name;
	targetPlayer.value.photo = footble.data.photo;
	targetPlayer.value.debut_season = footble.data.debut_season;
	targetPlayer.value.last_season = footble.data.last_season;

	const idx = weekFootbles.value.findIndex((elem) => elem.idPlayer == id);

	guesses.value = weekFootbles.value[idx].guesses;

	let canPlay = checkCanPlay();

	if(canPlay){
		gameFinished.value = false;
	} else {
		gameFinished.value = true;
	}
}

const updateLoadedHistoric = (finished, won) => {
	const idx = weekFootbles.value.findIndex((elem) => elem.idPlayer == selectedPastFootble.value);

	weekFootbles.value[idx].guesses = guesses.value;
	weekFootbles.value[idx].done = finished;
	weekFootbles.value[idx].won = won;
	if(finished) weekFootbles.value[idx].photo = targetPlayer.value.photo;

};

// crear fechas de días pasados
const getPastDates = (n) => {
    const fecha = new Date();
    fecha.setDate(fecha.getDate() - n);
    const dia = String(fecha.getDate()).padStart(2, '0');
    const mes = String(fecha.getMonth() + 1).padStart(2, '0');
    const anio = fecha.getFullYear();
    return `${dia}/${mes}/${anio}`;
}

const updateHistoric = (gameFinished, gameResult) => {
	let historic = localStorage.getItem('FootbleHistoric');

	if(historic) {
		historic = JSON.parse(historic);

		let currentFootble = historic.find((elem) => elem.idPlayer == selectedPastFootble.value);

		if(currentFootble) {
			currentFootble.guesses = guesses.value;
			currentFootble.done = gameFinished;
			currentFootble.won = gameResult;

			let index = historic.findIndex((elem) => elem.idPlayer === currentFootble.idPlayer);
			historic[index] = currentFootble;
		} else {
			let newFootble = {
				date: getPastDates(props.footble - selectedPastFootble.value), 
				done: gameFinished,
				won: gameResult,
				photo: targetPlayer.value.photo,
				name: targetPlayer.value.name,
				idPlayer: selectedPastFootble.value,
				guesses: guesses.value			
			}

			historic.unshift(newFootble);
		}

	} else {
	    historic = [];
	    let newFootble = {
			date: getPastDates(props.footble - selectedPastFootble.value), 
			done: gameFinished,
			won: gameResult,
			photo: targetPlayer.value.photo,
			name: targetPlayer.value.name,
			idPlayer: selectedPastFootble.value,
			guesses: guesses.value			
		}

		historic.unshift(newFootble);
		
	}


	localStorage.setItem('FootbleHistoric', JSON.stringify(historic));
}

// recuperar resultados de la semana pasada
const fillWeekFootbles = () => {
	for(let i = 1; i < 7; i++) {
		let dayFootble = {
			date: getPastDates(i), 
			done: false,
			won: false,
			photo: '',
			name: '',
			idPlayer: props.footble - i,
			guesses: []
		}

		weekFootbles.value.unshift(dayFootble);
	}

	let historic = localStorage.getItem('FootbleHistoric');

	if(historic) {
		historic = JSON.parse(historic);

		weekFootbles.value.forEach(elem => {
		
			let footble = historic.find((item) => item.date === elem.date);

			if(footble) {
				elem.won = footble.won;
				elem.photo = footble.photo;
				elem.name = footble.name;
				elem.done = footble.done;
			}		
		});

	}


}

// comprobar si ha jugado todos los de la semana pasada
const checkPlayedAll = () => {
	let result = false;

	let idx = weekFootbles.value.findIndex((elem) => elem.done === false);

	if(idx === -1) result = true;

	playedAll.value = result;
}

// buscar jugadores por nombre
const searchPlayers = debounce(async () => {
  if (searchQuery.value.length < 1) {
    suggestions.value = [];
    showSuggestions.value = false;
    return;
  }

  if(searchQuery.value != tempSearchVal) {
    try {
      const response = await axios.post('/player/search', { 
        name: searchQuery.value 
      });
      suggestions.value = response.data;
      showSuggestions.value = true;
      tempSearchVal = searchQuery.value;
      await nextTick(); // Esperar a que el DOM se actualice
    } catch (error) {
      console.error('Error en searchPlayers:', error.message, error.response?.data);
    }    
  }

}, 300); // 300ms de espera

const selectPlayer = async (selectedPlayer) => {

  if(checkCanPlay()) {
      // Ejecutar checkGuess y almacenar el resultado en el objeto player
      const result = await checkGuess(selectedPlayer.id);
      selectedPlayer.checkResult = result; // Agregar el resultado al jugador
      selectedPlayer.isFlipping = true;    // Activar la animación

      if(result.match == false && result.active != 'right') {

        if(selectedPlayer.debut_season < targetPlayer.value.debut_season) selectedPlayer['era'] = 'anterior';

        if(selectedPlayer.last_season == null || selectedPlayer.last_season > targetPlayer.value.last_season) selectedPlayer['era'] = 'posterior';
      }


      guesses.value.unshift(selectedPlayer);
      searchQuery.value = '';
      suggestions.value = [];
      showSuggestions.value = false;  

      // Eliminar la clase flip después de 250ms
      setTimeout(() => {
        selectedPlayer.isFlipping = false;
      }, 250);


      // Si ha acertado, mostrar el resultado
      if(result.match) {
        modalResultBackground.value = 'right-guess';
        gameFinished.value = true;
        updateHistoric(gameFinished.value, result.match);
      	updateLoadedHistoric(gameFinished.value, result.match);
      	updateStreak(true);
      	triggerStatsUpdate();
      	selectedPastFootble.value = null;
      	checkPlayedAll();
        showModal();
      }

      // Si ha hecho 10 intentos, no puede jugar más
      if(guesses.value.length == 10) {
      	if(!result.match) modalResultBackground.value = 'wrong-guess';
        gameFinished.value = true;
        updateHistoric(gameFinished.value, result.match);
      	updateLoadedHistoric(gameFinished.value, result.match);
      	updateStreak(false);
      	triggerStatsUpdate();
      	selectedPastFootble.value = null;
      	checkPlayedAll();
        showModal();
      }



  } else {
    showSuggestions.value = false;
    showModal();
  }


};

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

// comprobar intento
const checkGuess = async (playerId) => {
  try {
    const response = await axios.get(`/checkresult/${playerId}/` + selectedPastFootble.value);
    return response.data; // Devolver el resultado para almacenarlo
  } catch (error) {
    console.error(error);
    return null;
  }
};

// Método para mostrar el modal
const showModal = () => {
  modalResult.value.show();
};

// Método para ocultar el modal
const hideModal = () => {
	document.getElementById("mainSearchBox").focus();
  	modalResult.value.hide();
};

onMounted(() => {

	fillWeekFootbles();
	checkPlayedAll();

	axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;

	document.addEventListener('click', (event) => {
      const inputDropdown = document.querySelector('.input-dropdown-container');
      const searchBox = document.querySelector('#mainSearchBox');
      if (!inputDropdown?.contains(event.target) && event.target !== searchBox) {
	        showSuggestions.value = false;
	    }
	});

    modalResult.value = new Modal(document.getElementById('staticBackdrop'), {
    	focus: false, // Desactiva el enfoque automático
      keyboard: false // Opciones adicionales si las necesitas
    });
});

</script>

<template>
	<NavigationBar :update-trigger="updateStatsTrigger" />

  	<main class="container text-bg-dark mt-5 p-4 ">
  		<div class="row">
  			<div class="col-md-3 "></div>
  			<div class="col-md-6">
  				<div class="text-center mb-3">
  					<h1><i class="bi bi-rewind-fill"></i> Rewind</h1>

  					<div v-if="playedAll" class="instruction-message">{{ $t('You already played them all') }}</div>
  					<div v-else class="instruction-message">{{ $t('Choose a Footble to play') }}</div>
  				</div>
  				
  				<LastWeekComponent :lastWeek="weekFootbles" @selected-footble="setSelectedPastFootble" />

  				<div id="game-container" v-if="playGame">
		            <div class="guesses-remaining" >{{ $t('Guess') + ' ' + (guesses.length + 1) + ' ' + $t('of') }} 10</div>
		            <div class="input-group mb-3 input-dropdown-container pl-5 pr-5">
		              <input type="text" class="searchbox" id="mainSearchBox"
		                :placeholder="$t('Type a footballer name here') + '...'" 
		                v-model="searchQuery" 
		                @input="searchPlayers"
		                @keyup="searchPlayers"
                		@change="searchPlayers"
		                :disabled="!selectedPastFootble"
		                autocomplete="off">
		              <span class="searchbox-button">
		                <i class="bi bi-search text-bg-light" :class="{textBgLightDisabled: !selectedPastFootble}"></i>
		              </span>
		              <div class="dropdown w-100">
		                <ul class="dropdown-menu" id="suggestions" v-if="showSuggestions">
		                  <li v-for="(suggestedPlayer, index) in suggestions" :key="index + 1">
		                    <div class="dropdown-item dropdown-player-item" @click="selectPlayer(suggestedPlayer)">
		                      <img :src="`/img/players/${suggestedPlayer.photo}`" :alt="suggestedPlayer.name" class="tinythumb" style="float: right">
		                      {{ suggestedPlayer.name }}
		                    </div>
		                  </li>
		                </ul>
		              </div>
		            </div>

		            <div class="mt-5" id="guesses">
		              <PlayerContainer v-for="(player, index) in guesses" :key="index" :player="player"  />
		            </div>
		        </div>

  			</div>
  			<div class="col-md-3 ">
  				<!-- <p> {{ $t('right column') }}</p>
		        <button type="button" class="btn btn-warning" @click="showModal">
		          Launch static backdrop modal
		        </button> -->
  			</div>
  		</div>
  	</main>

<!-- Modal Resultado -->
<div class="modal text-center fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel">
  <div class="modal-dialog">
    <div class="modal-content ">
      <div class="modal-header " :class="modalResultBackground">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">{{ $t("The footballer of this day is") }}...</h1>
        <button type="button" class="btn-close" @click="hideModal"></button>
      </div>
      <div class="modal-body " :class="modalResultBackground">
        <img :src="'/img/players/' + returnTargetPhoto()" 
	     :alt="returnTargetName" 
	     class="result-thumb">
        <h2 class="mt-2">{{ returnTargetName() }}</h2>
      </div>
      <div class="my-modal-bottom " :class="modalResultBackground">
      	<div style="min-height: 10px;"></div>        
      </div>
    </div>
  </div>
</div>

</template>
<style scoped>

h1 {
	margin-bottom: 15px;
}

</style>
<style scoped>
#suggestions {
  display: block !important;
  position: absolute;
  z-index: 1000;
}

.modal-content {
  color: #FFF;
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

.my-modal-bottom {

    border-bottom-right-radius: var(--bs-modal-inner-border-radius);
    border-bottom-left-radius: var(--bs-modal-inner-border-radius);
}


.textBgLightDisabled {
	color: light-dark(rgba(239, 239, 239, 0.3), rgba(59, 59, 59, 0.3)) !important;
    background-color: #5f6265 !important;
}

@media screen and (max-width: 480px) {
	.mb-3 {
		margin-bottom: 0.25rem !important;
	}

	h1 {
		margin-bottom: 0.18rem !important;
	}

	.instruction-message, .guesses-remaining {
		font-size: 0.8rem;
	}

}

</style>