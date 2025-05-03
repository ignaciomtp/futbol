<script setup>
import { ref } from 'vue';

let props = defineProps({ 
  lastWeek: Array
  
});

const emit = defineEmits(['selected-footble']);

const selectedFootble = ref(null);

const selectFootble = (id) => {
	
	selectedFootble.value = id;

	emit('selected-footble', id);
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
	<div class="data-row text-center p-1">
		<div class="casilla" v-for="(day, index) in lastWeek" :key="index + 1">
			<div class="date-circle borde2" :class="footbleBackground(day.idPlayer)">
				<div v-if="day.done">
					<img :src="'/img/players/' + day.photo" v-if="day.photo.length" class="tinythumb2" />
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

.tinythumb2 {
    max-height: 53px;
    border-radius: 50%;
}

.date-circle {
	width: 50px;
    height: 50px;
    border-radius: 50%;
    justify-content: center;
    align-items: top;
    padding: 0;
    font-size: 1.2rem;
    font-weight: bold;
    transition: all .2s ease;
    overflow: hidden;
    display: grid;
    place-items: center; /* Centra tanto horizontal como verticalmente */
}

.date-circle span {
	display: inline-block;
	min-width: 50px;
	line-height: 2.6rem;
}


.button {
	cursor: pointer;
}

.wrong-guess:hover {
	background-color: #030202 !important;
}

@media (max-width: 480px) {
	.casilla {
		width: 45px;
		margin: 0 7px;
	}

	.date {
		font-size: 0.8rem;
		font-weight: 400;
	}

	.date-circle {
		width: 45px;
	    height: 45px;
	    padding-top: 1px;
	}

	.date-circle img {
		height: 45px;
	}
}

</style>