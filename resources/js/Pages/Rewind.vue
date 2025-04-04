<script setup>
import axios from 'axios';
import { ref, onMounted } from 'vue';
import { router, Link, usePage } from '@inertiajs/vue3';
import { Modal } from 'bootstrap';
import NavigationBar from '@/Components/NavigationBar.vue';
import LastWeekComponent from '@/Components/LastWeekComponent.vue';

let props = defineProps({ 
  footble: Number,
  player: Object
});

const page = usePage();

const playGame = ref(false); 
const gameFinished = ref(false);

const searchQuery = ref('');
const suggestions = ref([]);
const showSuggestions = ref(false);
const guesses = ref([]);
const weekFootbles = ref([]);

// crear fechas de días pasados
const getPastDates = (n) => {
    const fecha = new Date();
    fecha.setDate(fecha.getDate() - n);
    const dia = String(fecha.getDate()).padStart(2, '0');
    const mes = String(fecha.getMonth() + 1).padStart(2, '0');
    const anio = fecha.getFullYear();
    return `${dia}/${mes}/${anio}`;
}

const fillWeekFootbles = () => {
	for(let i = 0; i < 6; i++) {
		let dayFootble = {
			date: getPastDates(i),
			done: false,
			won: false,
			photo: '',
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
				if(footble.won || footble.attempts == 10) elem.done = true;
			}		
		});

	}

	console.log('last week: ', weekFootbles.value);

}

onMounted(() => {

	fillWeekFootbles();

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
  		<div class="row text-center pt-3">
  			<div class="col-md-3 "></div>
  			<div class="col-md-6">
  				<h1><i class="bi bi-rewind-fill"></i> Rewind</h1>
  				<LastWeekComponent :lastWeek="weekFootbles" />
  			</div>
  			<div class="col-md-3 "></div>
  		</div>
  	</main>
</template>
<style scoped>

h1 {
	margin-bottom: 15px;
}

</style>