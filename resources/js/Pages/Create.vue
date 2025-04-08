<script setup>
import axios from 'axios';
import { ref, onMounted } from 'vue';
import { router, Link, usePage } from '@inertiajs/vue3';
import { Modal } from 'bootstrap';
import { trans } from 'laravel-vue-i18n';
import NavigationBar from '@/Components/NavigationBar.vue';
import PlayerView from '@/Components/PlayerView.vue';

const targetPlayer = ref(null);

const searchQuery = ref('');
const suggestions = ref([]);
const showSuggestions = ref(false);

const message = ref('');

const shareResultText = ref('Share');

let placeholderPlayer = {
	name: 'Pepito',
	photo: 'user.jpg'
};

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

const selectPlayer = (selectedPlayer) => {

	console.log(selectedPlayer);

	targetPlayer.value = selectedPlayer;

    showSuggestions.value = false;  

};

const shareFootble = () => {
	const encodedMessage = btoa(encodeURIComponent(message.value));
	const encodedPlayerId = btoa(targetPlayer.value.id);

	const baseUrl = window.origin;
   
    const url = `${baseUrl}/custom/${encodedPlayerId}/${encodedMessage}`;

    const iMade = trans('I made this Footble for you');
    const guessThe = trans('Guess the footballer in 10 tries');

    let texto = `${iMade}. ${guessThe}.

${url}
`;

	navigator.clipboard.writeText(texto);
	shareResultText.value = 'Copied result';

}

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
	<NavigationBar />

  	<main class="container text-bg-dark mt-5 p-4">
  		<div class="row pt-3">
  			<div class="col-md-3 "></div>
  			<div class="col-md-6">
  				<div class="text-center mb-5">
  					<h1><i class="bi bi-plus-circle"></i> Create</h1>
  				</div>

  				<div class="data-row instruction-data mb-2">
  					<p class="mr-3">
  						1.
  					</p>
  					<h2> {{ $t('Pick a footballer for your friend to guess') }}</h2>  				
  				</div>

  				
  				<div class="input-group mb-3 input-dropdown-container pl-5 pr-5 mb-4">
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
	                  <li v-for="(suggestedPlayer, index) in suggestions" :key="index + 1">
	                    <div class="dropdown-item dropdown-player-item" @click="selectPlayer(suggestedPlayer)">
	                      <img :src="`/img/players/${suggestedPlayer.photo}`" :alt="suggestedPlayer.name" class="tinythumb" style="float: right">
	                      {{ suggestedPlayer.name }}
	                    </div>
	                  </li>
	                </ul>
	              </div>
		        </div>

		        <div class="data-row data-player-id d-flex align-items-center mb-5" v-if="targetPlayer">
			      <PlayerView :player="targetPlayer" />

			    </div>

			    <div v-if="targetPlayer">
				    <div class="data-row instruction-data mb-1" >
						<p class="mr-3">
							2.
						</p>
						<h2> {{ $t('Leave a note for your friend') }}</h2>  						
									
					</div>

					<textarea class="form-control" v-model="message" :placeholder="$t('Write a hint or message here')" id="floatingTextarea"></textarea>	

					<div class="mt-4 text-center">
						<button type="button" class="btn btn-success" @click="shareFootble">{{ $t(shareResultText) }}</button>		  
					</div>
					  	
			    </div>  				
  			</div>
  		</div>
  	</main>
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

.instruction-data h2 {
	font-size: 1.2rem;
	width: 90%;
}

.instruction-data {
	justify-content: space-evenly;
}

.instruction-data p {
	font-size: 1.1rem;
	font-weight: bold;
	color: #888;
	width: 10%;
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

</style>