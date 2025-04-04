<script setup>
import { ref } from 'vue';

let props = defineProps({ 
  lastWeek: Array
  
});

const selectedFootble = ref(null);

const selectFootble = (id) => {
	
	selectedFootble.value = id;

	console.log(selectedFootble.value);
}

const footbleBackground = (id) => {
	if(id == selectedFootble.value){
		return 'right-guess';
	} else {
		return 'wrong-guess';
	}
	
}



</script>

<template>
	<div class="data-row text-center ">
		<div class="casilla" v-for="(day, index) in lastWeek" :key="index + 1">
			<div class="date-circle borde2" :class="footbleBackground(day.idPlayer)">
				<div v-if="day.done">
					<img :src="'/img/players/' + day.photo" v-if="day.photo.length" />
					<span v-if="!day.photo.length">?</span>
				</div>

				<div v-else>
					<div class="button" @click="selectFootble(day.idPlayer)">
						<span>?</span>
					</div>
					
				</div>
				
			</div>
			<div class="date mt-1">{{ day.date.substring(0, 5) }}</div>
		</div>
	</div>
</template>

<style scoped>

.data-row {
	justify-content: center;
}

.casilla {
	width: 50px;
	margin: 0 10px;
}

.date {
	font-size: 0.9rem;
}

.date-circle {
	width: 50px;
    height: 50px;
    border-radius: 50%;
    justify-content: center;
    align-items: center;
    padding-top: 6px;
    font-size: 1.2rem;
    font-weight: bold;
    transition: all .2s ease;
    overflow: hidden;
}

.date-circle img {
	height: 50px;
}

.date-circle span {
	vertical-align: middle;
}

.button {
	cursor: pointer;
}

@media (max-width: 480px) {
	.casilla {
		width: 45px;
		margin: 0 7px;
	}

	.date-circle {
		width: 45px;
	    height: 45px;
	    padding-top: 3px;
	}

	.date-circle img {
		height: 45px;
	}
}

</style>