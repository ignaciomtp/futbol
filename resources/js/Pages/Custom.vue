<script setup>
import axios from 'axios';
import { ref, onMounted, computed  } from 'vue';
import { router, Link, usePage } from '@inertiajs/vue3';
import { Modal } from 'bootstrap';
import { trans } from 'laravel-vue-i18n';
import PlayerContainer from '@/Components/PlayerContainer.vue';
import NavigationBar from '@/Components/NavigationBar.vue';
import SearchComponent from '@/Components/SearchComponent.vue';
import FooterComponent from '@/Components/FooterComponent.vue';

let props = defineProps({ 
	footble: Number,
	message: String,
  player: Object
});

const isMobile = computed(() => {
  return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
});

const modalResult = ref(null);
const modalResultBackground = ref('wrong-guess');

const page = usePage();

const playGame = ref(false); 
const gameFinished = ref(false);

const shareResultText = ref('Share result');

/*
const searchQuery = ref('');
const suggestions = ref([]);*/
const showSuggestions = ref(false);
const guesses = ref([]);

// copiar resultado del juego para compartir
const shareResult = async () => {
  let res = [];

  if(checkUserWon()) {
    res.push('✔');
  } else {
    res.push('❌');
  }

  for(let i = 0; i < guesses.value.length - 1; i++) {
    res.unshift('⬜');
  }

  let message = trans('Footble challenge');

  let texto = `${message} ⚽: ${props.player.name}

${res.join('')}

footble.io`;

  try {
      await navigator.clipboard.writeText(texto);
      shareResultText.value = 'Copied result';
      localStorage.removeItem('footbleCustom' + props.footble);
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
  if(wonGame) return true;
  return false;
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



const selectPlayer = async (selectedPlayer) => {

  if(!gameFinished.value) {
      // Ejecutar checkGuess y almacenar el resultado en el objeto player
      const result = await checkGuess(selectedPlayer.id);
      selectedPlayer.checkResult = result; // Agregar el resultado al jugador
      selectedPlayer.isFlipping = true;    // Activar la animación

      if(result.match == false && result.active != 'right') {
        if(selectedPlayer.debut_season < props.player.debut_season) selectedPlayer['era'] = 'anterior';

        if(selectedPlayer.last_season == null || selectedPlayer.last_season > props.player.last_season) selectedPlayer['era'] = 'posterior';
      }

      guesses.value.unshift(selectedPlayer);
      showSuggestions.value = false;

      // Eliminar la clase flip después de 250ms
      setTimeout(() => {
        selectedPlayer.isFlipping = false;
      }, 250);

      // Guardar intentos en localStorage
      saveCustomGuesses();    

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
    const response = await axios.get(`/checkresult/${playerId}/${props.footble}`);
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


// guardar los intentos del custom
const saveCustomGuesses = () => {
    let newDayGuesses = {
        day: props.footble,
        guesses: guesses.value
    };

    let customItem = 'footbleCustom' + props.footble;

    localStorage.setItem(customItem, JSON.stringify(newDayGuesses));

    checkGameFinished();
}

// cargar los intentos del custom-made si existen
const getCustomGuesses = () => {
	let customItem = 'footbleCustom' + props.footble;
    let customGuesses = localStorage.getItem(customItem);
    if (customGuesses) customGuesses = JSON.parse(customGuesses);

    if (customGuesses) {
        // Asegurar que cada jugador tenga las propiedades necesarias
        guesses.value = customGuesses.guesses.map(player => ({
            ...player,
            isFlipping: false // Valor por defecto al cargar
        }));
    }
    
};

const suggestionsVisble = (value) => {
  showSuggestions.value = value;
}


onMounted(() => {
    getCustomGuesses();

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

    
});

</script>
<template>
	<NavigationBar />

	<div class="page-wrapper">
    <main class="main-content text-bg-dark mt-5 pt-2">
      <div class="container">
  	    <div class="row padding-top-5">
  	      <div class="col-lg-3 text-center"></div>
  	      <div class="col-lg-6">
  	        <div id="game-container">
  	            <div class="guesses-remaining" v-if="guesses.length < 10">{{ $t('Guess') + ' ' + (guesses.length + 1) + ' ' + $t('of') }} 10</div>
  	            <div class="input-group mb-3 input-dropdown-container px-1"style="position: relative;">
                  <SearchComponent 
                    :player="props.player"
                    :footble="props.footble"
                    :showSuggestions="showSuggestions"
                    :isMobile="isMobile"
                    @selected="selectPlayer"
                    @toggleSugestions="suggestionsVisble"
                  />
  	            </div>

  	            <div class="mt-3 text-center hints" v-if="!guesses.length">
  	                <p class="m-4">{{ $t('A hint from your friend') }}: <span class="message">{{ props.message }}</span></p>
  	                
  	            </div>

  	            <div class="mt-3" id="guesses">
  	              <PlayerContainer v-for="player in guesses" :key="player.id" :player="player"  />
  	            </div>
  	        </div>

  	      </div>
  	      <div class="col-lg-3 text-center">

  	      </div>
  	    </div>
      </div>
	  </main>

    <FooterComponent />
  </div>



<!-- Modal Resultado -->
<div class="modal text-center fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content ">
      <div class="modal-header " :class="modalResultBackground">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">{{ $t("The footballer is") }}...</h1>
        <button type="button" class="btn-close" @click="hideModal"></button>
      </div>
      <div class="modal-body " :class="modalResultBackground">
        <img :src="'/img/players/' + props.player.photo" :alt="props.player.name" class="result-thumb">
        <h2 class="mt-2">{{ props.player.name }}</h2>
      </div>
      <div class="my-modal-bottom " :class="modalResultBackground">
        <div class="mb-4">
         	<button type="button" class="btn btn-dark" @click="shareResult">{{ $t(shareResultText) }}</button>
        </div>
        <div class="mb-4">
         	<a class="btn btn-dark" :href="route('homeapp')">{{ $t("Play Today's") }}</a>
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

.modal-content {
  color: #FFF;
}

.instructions img {
  max-width: 400px;
}

.message {
	color: #888;
	font-style: italic;
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
