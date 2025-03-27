<script setup>

	const props = defineProps({
	    country_name: String,
	    country_flag: String,
	}); 

</script>
<template>
	<div class="player-data-item text-center wrong-guess" >
		<h3>{{ country_name }}</h3>
		<img src="{{ country_flag }}" alt="{{ country_name }}">
	</div>
</template>