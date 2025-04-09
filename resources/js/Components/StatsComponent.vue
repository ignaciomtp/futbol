<script setup>
import { ref, onMounted } from 'vue';
import DistributionComponent from '@/Components/DistributionComponent.vue';

let historic = localStorage.getItem('FootbleHistoric');
let won = [];

if(historic){
	historic = JSON.parse(historic);

	won = historic.filter((elem) => elem.won);
} else {
	historic = [];
}

let distribution = ref(null);

distribution.value = [
	{
		'1-2': 0,
		'percent': 0
	},
	{
		'3-4': 0,
		'percent': 0
	},
	{
		'5-6': 0,
		'percent': 0
	},
	{
		'7-8': 0,
		'percent': 0
	},
	{
		'9-10': 0,
		'percent': 0
	},
];

const fillDistribution = () => {
	console.log('Won: ', won);

	won.forEach((elem) =>{
		let intentos = (elem.guesses || []).length;
		let label = '';

		if (intentos < 3) {
		    label = '1-2';
		} else if (intentos < 5) {
		    label = '3-4';
		} else if (intentos < 7) {
		    label = '5-6';
		} else if (intentos < 9) {
		    label = '7-8';
		} else {
		    label = '9-10';
		}

		let idx = distribution.value.findIndex((item) => Object.keys(item)[0] === label);

		if (idx !== -1) {
		  distribution.value[idx][label] += 1; // Incrementa el valor en 1
		}

	});

	distribution.value.forEach((item) => {
		item['percent'] = getPercentage(won.length, item[Object.keys(item)[0]]);
	});


	console.log('Distribution: ', distribution.value);
}

const getPercentage = (total, part) => {
	let result = 0;

	result = (part * 100) / total;

	return Math.floor(result);
}

let width1 = 20;
let width2 = '80%';

onMounted(() => {
	fillDistribution();
});

</script>
<template>

	<div class="row g-4 container">
		<div class="col stat-value">
			{{ historic.length }}
			<div class="stat-label">Played</div>
		</div>
		<div class="col stat-value">
			{{ getPercentage(historic.length, won.length) }} %
			<div class="stat-label">Win %</div>
		</div>
		<div class="col stat-value">
			0
			<div class="stat-label">Current Streak</div>
		</div>
		<div class="col stat-value">
			0
			<div class="stat-label">Max Streak</div>
		</div>

	</div>

	<div class="container mt-3">
		<h6>GUESS DISTRIBUTION</h6>

		<div class="container">

			<DistributionComponent v-for="(rango, index) in distribution" :key="index + 1" :rango="rango" />


		</div>

	</div>

</template>
<style scoped>

.fullwidth {
	width: 100%;
}

.stat-value {
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 12px;
    color: #b5b5b5;
    text-align: center;
}




</style>