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

<style scoped>
@media screen and (max-width: 450px) {

	.player-data-item {
		padding-left: 2px;
		padding-right: 2px;
	}

    .player-data h4 {
        font-size: 12px;
    }
}
</style>