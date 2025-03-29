<script setup>

const emit = defineEmits(['play-game']);

const emitPlayGame = () => {
	emit('play-game');
}

</script>

<template>

	<div class="mt-3 text-center p-3">
		<img :src="`/img/soccer-field.png`" alt="Footble" >

		<div class="mt-3 mb-3" data-svelte-h="svelte-1f4f1d1">
			<p class="p-1">10 {{ $t('guesses') }}, 1000 {{ $t('footballers') }}</p> 
			<p class="p-1">{{ $t('Will you win today?') }}</p>
		</div>

		<div class="mt-5">
			<button class="btn btn-success" @click="emitPlayGame">{{ $t('Play') }}</button>
		</div>
		
	</div>


</template>