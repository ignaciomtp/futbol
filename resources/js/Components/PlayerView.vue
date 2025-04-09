<script setup>
const props = defineProps({
  player: Object,
});
</script>
<template>
	<div class="d-flex align-items-center ancho ">
	    <img :src="'/img/players/' + player.photo" :alt="player.name" class="round-thumb mr16">
	    <h2 class="text-bg-dark mb-0">{{ player.name }}</h2>
	</div>
</template>
<style scoped>
.ancho {
	width: 100%;
	justify-content: center;
}


</style>