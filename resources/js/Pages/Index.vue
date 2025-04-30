<script setup>

import axios from 'axios';
import { ref, onMounted, computed  } from 'vue';
import { router, Link, usePage } from '@inertiajs/vue3';
import { Modal } from 'bootstrap';
import PlayerContainer from '@/Components/PlayerContainer.vue';
import NavigationBar from '@/Components/NavigationBar.vue';
import TimerComponent from '@/Components/TimerComponent.vue';
import HomeCover from '@/Components/HomeCover.vue';
import SearchComponent from '@/Components/SearchComponent.vue';

let props = defineProps({ 
  footble: Number,
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
const userWon = ref(false);


// contador para mostrar cuanto falta hasta el próximo footble
const startCounter = ref(false);

const shareResultText = ref('Share result');

/*
const searchQuery = ref('');
const suggestions = ref([]);*/
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


// compartir en Twitter
const shareOnTwitter = () => {
  const text = encodeURIComponent(shareResultText.value);
  const url = `https://twitter.com/intent/tweet?text=${text}`;
  window.open(url, '_blank');
}

// compartir en Whatsapp
const shareOnWhatsApp = () => {
  const text = encodeURIComponent(shareResultText.value);
  const url = `https://api.whatsapp.com/send?text=${text}`;
  window.open(url, '_blank');
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

const suggestionsVisble = (value) => {
  showSuggestions.value = value;
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

    playTheGame();

});
</script>

<template>
  <NavigationBar :update-trigger="updateStatsTrigger" />

  <main class="container text-bg-dark mt-5 pt-2">
    <div class="row padding-top-5">
      <div class="col-lg-3 text-center">
        
      </div>
      <div class="col-lg-6">


        <div id="game-container" v-if="playGame">
            <div class="guesses-remaining" v-if="guesses.length < 10">{{ $t('Guess') + ' ' + (guesses.length + 1) + ' ' + $t('of') }} 10</div>
            <div class="input-group mb-3 input-dropdown-container px-1" style="position: relative;">
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
                <p class="m-3">{{ $t('Guess the footballer of the day') }}.</p>
                <p class="m-3">{{ $t('Search for an footballer to make your first guess') }}.</p>
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

    <div class="footer__bottom">
        <div class="footer__copir">Footble.io © 2025</div>
        <ul class="footer__links">
            <li><a href="mailto:admin@footble.io?subject=Footble">Contact</a></li>
            <li><a :href="route('privacy')">{{ $t('Privacy') }}</a></li>
        </ul>
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
        <button type="button" class="btn bigger btn-dark" @click="shareResult">{{ $t(shareResultText) }}</button>
      </div>

<div class="share-buttons" v-if="shareResultText == 'Copied result'">
    <button @click="shareOnTwitter" class="btn share-btn twitter-btn text-center">
      <div class="share-btn-content" >
        <i class="bi bi-twitter-x"></i>
        <span>{{ $t('Share on X') }}</span>
        
      </div>
    </button>
    <button @click="shareOnWhatsApp" class="btn share-btn whatsapp-btn text-center">
      <div class="share-btn-content" >
        <i class="bi bi-whatsapp"></i>
        <span>{{ $t('Share on WhatsApp') }}</span>
      </div>
    </button>

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


/* Hide desktop suggestions on mobile 
@media (max-width: 768px) {
  .desktop-suggestions {
    display: none !important;
  }
}
*/

.btn {
  min-width: 200px;
  font-weight: 500;
}

.share-buttons {
    display: flex;
    gap: 10px;
    justify-content: center;
    margin-top: 20px;
    margin-bottom: 20px;
}

.share-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 5px 12px;
    border: none;

    cursor: pointer;


    transition: background-color 0.3s;
    width: 48%;
    justify-content: center; /* Centra el contenido del botón */
}

.share-btn i {
  font-size: 22px;
  float: left;
  margin-right: 8px;
}

.share-btn-content {
  display: inline-block; 


}

.share-btn-content span {
  vertical-align: middle; 

  line-height: 32px;
  display: inline-block;
  font-weight: 500;
}

.twitter-btn {
    background-color: #000;
    color: #fff;
}

.twitter-btn:hover {
    background-color: #333;
}

.whatsapp-btn {
    background-color: #25D366;
    color: #fff;
}

.whatsapp-btn:hover {
    background-color: #20b358;
}

.copy-btn {
    background-color: #f0f0f0;
    color: #000;
}

.copy-btn:hover {
    background-color: #e0e0e0;
}

/* Media query para dispositivos móviles */
@media (max-width: 768px) {
    .share-buttons {
        flex-direction: column; /* Cambia a disposición vertical */
        align-items: center; /* Centra los botones horizontalmente */
        gap: 15px; /* Aumenta el espacio entre botones */
    }

    .btn {
        width: 100%; /* Ocupa todo el ancho disponible (hasta un máximo) */
        max-width: 300px; /* Límite máximo para evitar botones demasiado anchos */
        min-height: 43px;
    }
}

</style>
