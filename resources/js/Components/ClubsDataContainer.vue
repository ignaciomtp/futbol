<script setup>

	const props = defineProps({
	    clubs: Array
	}); 

</script>
<template>
	<div class="player-data-item-wide wrong-guess text-left" >
		<h3 class="text-center">Played for</h3>
		<ul class="text-left">
			<li class="mt-1 mb-1" v-for="(club, index) in clubs" :key="index + 1">
				<div class="badge-box"><img :src="'/img/clubs/' + club.badge" class="club-badge"></div>
				<div class="club-name-box">{{ club.name }}</div>
			</li>
		</ul>
	</div>	
</template>