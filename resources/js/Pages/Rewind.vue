<script setup>
import axios from 'axios';
import { ref, onMounted } from 'vue';
import { router, Link, usePage } from '@inertiajs/vue3';
import { Modal } from 'bootstrap';
import NavigationBar from '@/Components/NavigationBar.vue';
import LastWeekComponent from '@/Components/LastWeekComponent.vue';
import PlayerContainer from '@/Components/PlayerContainer.vue';

let props = defineProps({ 
  footble: Number
});

const page = usePage();
const modalResult = ref(null);
const modalResultBackground = ref('wrong-guess');

const targetPlayer = ref({
	name: 'Pepito',
	photo: 'user.jpg'
});

const playGame = ref(true); 
const gameFinished = ref(false);

const searchQuery = ref('');
const suggestions = ref([]);
const showSuggestions = ref(false);
const guesses = ref([]);
const weekFootbles = ref([]);

const selectedPastFootble = ref(null);

const checkCanPlay = () => {
	let result = true;

	if(guesses.value.length == 10) result = false;

	let winner = guesses.value.find((elem) => elem.idPlayer == selectedPastFootble.value);

	if(winner) result = false;

	return result;
}

// establecer el footble del día
const setSelectedPastFootble = (id) => {
	selectedPastFootble.value = id;

	let footble = weekFootbles.value.find((elem) => elem.idPlayer === id);

	if(footble) {
		guesses.value = footble.guesses;
	}

	targetPlayer.value.name = footble.name;
	targetPlayer.value.photo = footble.photo;
}

// crear fechas de días pasados
const getPastDates = (n) => {
    const fecha = new Date();
    fecha.setDate(fecha.getDate() - n);
    const dia = String(fecha.getDate()).padStart(2, '0');
    const mes = String(fecha.getMonth() + 1).padStart(2, '0');
    const anio = fecha.getFullYear();
    return `${dia}/${mes}/${anio}`;
}

// recuperar resultados de la semana pasada
const fillWeekFootbles = () => {
	for(let i = 0; i < 6; i++) {
		let dayFootble = {
			date: getPastDates(i), // TO DO -> Cambiar a i + 1
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
				if(footble.won || footble.attempts == 10) elem.done = true;
			}		
		});

	}

	console.log('last week: ', weekFootbles.value);

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

      // Guardar intentos en localStorage
      //saveDayGuesses();    

      // Si ha acertado, mostrar el resultado
      if(result.match) {
        modalResultBackground.value = 'right-guess';
        gameFinished.value = true;
        
        showModal();
      }

      // Si ha hecho 10 intentos, no puede jugar más
      if(guesses.value.length == 10) {
        gameFinished.value = true;
        
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
  modalResult.value.hide();
};

onMounted(() => {

	fillWeekFootbles();

	axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;

	document.addEventListener('click', (event) => {
        if (!event.target.closest('.input-dropdown-container')) {
          showSuggestions.value = false;
        }
    });

    modalResult.value = new Modal(document.getElementById('staticBackdrop'), {
      keyboard: false // Opciones adicionales si las necesitas
    });
});

</script>

<template>
	<NavigationBar />

  	<main class="container text-bg-dark mt-5 p-4">
  		<div class="row pt-3">
  			<div class="col-md-3 "></div>
  			<div class="col-md-6">
  				<div class="text-center">
  					<h1><i class="bi bi-rewind-fill"></i> Rewind</h1>

  					<div>Choose a Footble to play</div>
  				</div>
  				
  				<LastWeekComponent :lastWeek="weekFootbles" @selected-footble="setSelectedPastFootble" />

  				<div id="game-container" v-if="playGame">
		            <div class="guesses-remaining" v-if="guesses.length < 10">{{ $t('Guess') + ' ' + (guesses.length + 1) + ' ' + $t('of') }} 10</div>
		            <div class="input-group mb-3 input-dropdown-container pl-5 pr-5">
		              <input type="text" class="searchbox" 
		                :placeholder="$t('Type a footballer name here') + '...'" 
		                v-model="searchQuery" 
		                @input="searchPlayers"
		                :disabled="!selectedPastFootble"
		                autocomplete="off">
		              <span class="searchbox-button">
		                <i class="bi bi-search text-bg-light"></i>
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
		              <PlayerContainer v-for="player in guesses" :key="player.id" :player="player"  />
		            </div>
		        </div>

  			</div>
  			<div class="col-md-3 ">
  				<p> {{ $t('right column') }}</p>
		        <button type="button" class="btn btn-warning" @click="showModal">
		          Launch static backdrop modal
		        </button>
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
        <img :src="'/img/players/' + (targetPlayer.value?.photo || 'user.jpg')" 
	     :alt="targetPlayer.value?.name || 'Pepito'" 
	     class="result-thumb">
        <h2 class="mt-2">{{ targetPlayer.value?.name || 'Pepito' }}</h2>
      </div>
      <div class="my-modal-bottom " :class="modalResultBackground">

        
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


</style>