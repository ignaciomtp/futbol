<script setup>

	const props = defineProps({
	    debut_year: Number,
	    last_year: Number,
	}); 

</script>
<template>
	<div class="player-data-item text-center partial-guess" id="player17Active">
		<h3>Active</h3>
		<h4>{{ debut_year }} - {{ last_year ? last_year : 'Today' }}</h4>
	</div>
</template>