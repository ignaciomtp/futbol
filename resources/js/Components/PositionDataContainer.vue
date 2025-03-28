<script setup>

	const props = defineProps({
	    position: String,
	    guessClass: String,
  		isFlipping: Boolean
	}); 


</script>
<template>
	<div :class="['player-data-item', 'text-center', guessClass, { 'flip': isFlipping }]">
		<h3>{{ $t('Position') }}</h3>
		<h4>{{ $t(position) }}</h4>
	</div>
</template>