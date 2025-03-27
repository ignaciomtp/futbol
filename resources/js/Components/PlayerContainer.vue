<script setup>
import CountryDataContainer from '@/Components/CountryDataContainer.vue';
import ClubsDataContainer from '@/Components/ClubsDataContainer.vue';
import ActiveDataContainer from '@/Components/ActiveDataContainer.vue';
import PositionDataContainer from '@/Components/PositionDataContainer.vue';
import TitlesDataContainer from '@/Components/TitlesDataContainer.vue';


const props = defineProps({
    player: Object
}); 

</script>
<template>
	<div class="player-div-box p-3 mt-4 mb-4">
		<div class="data-row data-player-id">
			<img :src="'/img/players/' + player.photo" :alt="player.name" class="round-thumb">
			<h2 class="text-bg-dark">{{ player.name }}</h2>
		</div>
		<div class="player-data data-row mt-2">
			<CountryDataContainer  
				:country_name="player.country"
				:country_flag="player.country_flag"
			/>

			<ActiveDataContainer 
				:debut_year="player.debut_season"
				:last_year="player.last_season"
			/>

			<PositionDataContainer 
				:position="player.position"
			/>

			<ClubsDataContainer 
				:clubs="player.clubs"
			/>

			<TitlesDataContainer 
				:titles="player.titles"
			/>
		</div>
	</div>	
</template>