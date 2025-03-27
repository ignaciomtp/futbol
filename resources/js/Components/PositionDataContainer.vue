<script setup>

	const props = defineProps({
	    position: String,
	    
	}); 

</script>
<template>
	<div class="player-data-item text-center wrong-guess" >
		<h3>Position</h3>
		<h4>{{ position }}</h4>
	</div>
</template>