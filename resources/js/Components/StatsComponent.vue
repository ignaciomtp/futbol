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

const topThree = ref([]);
const topThreeVisible = ref(false);

const distribution = ref(null);

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

	let tempWon = won.map((item) => {
		let clone = {...item};

		if(!clone.hasOwnProperty('attempts')) {
			clone['attempts'] = clone.guesses.length;
		}

		return clone;
	});

	tempWon.sort((a, b) => a.attempts - b.attempts);

	tempWon.forEach((elem) =>{
		let intentos = elem.attempts;		
		let label = '';

		if (intentos < 3) {
		    label = '1-2';
		    if(topThree.value.length < 3) topThree.value.push(elem);
		} else if (intentos < 5) {
		    label = '3-4';
		    if(topThree.value.length < 3) topThree.value.push(elem);
		} else if (intentos < 7) {
		    label = '5-6';
		    if(topThree.value.length < 3) topThree.value.push(elem);
		} else if (intentos < 9) {
		    label = '7-8';
		    if(topThree.value.length < 3) topThree.value.push(elem);
		} else {
		    label = '9-10';
		    if(topThree.value.length < 3) topThree.value.push(elem);
		}

		let idx = distribution.value.findIndex((item) => Object.keys(item)[0] === label);

		if (idx !== -1) {
		  distribution.value[idx][label] += 1; // Incrementa el valor en 1
		}

	});

	if(topThree.value.length) topThreeVisible.value = true;

	distribution.value.forEach((item) => {
		item['percent'] = getPercentage(won.length, item[Object.keys(item)[0]]);
	});

}

const getPercentage = (total, part) => {
	let result = 0;

	result = (part * 100) / total;

	result = Math.floor(result);	

	return isNaN(result) ? 0 : result;
}

onMounted(() => {
	fillDistribution();
});

</script>
<template>

	<div class="row g-4 container mb-4">
		<div class="col stat-value">
			{{ historic.length }}
			<div class="stat-label">{{ $t('Played') }}</div>
		</div>
		<div class="col stat-value">
			{{ getPercentage(historic.length, won.length) }} %
			<div class="stat-label">{{ $t('Win') }} %</div>
		</div>
		<div class="col stat-value">
			0
			<div class="stat-label">{{ $t('Current Streak') }}</div>
		</div>
		<div class="col stat-value">
			0
			<div class="stat-label">{{ $t('Max Streak') }}</div>
		</div>

	</div>

	<div class="container">
		
			<h6 v-if="topThreeVisible" style="text-transform: uppercase;">{{ $t('Top performances') }}</h6>
			<div class="top-footballers mb-4" >
				<div class="top-footballer " v-for="player in topThree" :key="player.idPlayer">
					<img :src="'/img/players/' + player.photo" :alt="player.name" class="round-thumb">
					<div class="guess-number">{{ Array.isArray(player.guesses) ? player.guesses.length : player.attempts }} {{ $t('guesses') }}</div>
				</div>
			</div>

			<h6 style="text-transform: uppercase;">{{ $t('Guess distribution') }}</h6>
			<DistributionComponent v-for="(rango, index) in distribution" :key="index + 1" :rango="rango" />

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

.top-footballers {
    display: flex;
    justify-content: space-around;
    width: 100%;
}

.top-footballer {
    display: flex;
    flex-direction: column;
    align-items: center;

}

.guess-number {
    font-size: 14px;
    color: #b5b5b5;
    margin-top: 7px;
    background-color: #cbff70;
    padding: 3px 8px;
    border-radius: 12px;
    color: #000;
}


</style>