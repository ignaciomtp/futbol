<script setup>

	const props = defineProps({
	    debut_year: Number,
	    last_year: Number,
	    guessClass: String,
  		isFlipping: Boolean,
  		era: String
	}); 


</script>
<template>
	<div :class="['player-data-item', 'text-center', guessClass, { 'flip': isFlipping }]">
		<h3><i class="bi bi-arrow-down" :class="{hideicon : (era == 'anterior' || guessClass == 'right-guess')}"></i> {{ $t('Active') }} <i class="bi bi-arrow-up" :class="{hideicon : (era == 'posterior' || guessClass == 'right-guess')}"></i></h3>
		<h4>{{ debut_year }} - {{ last_year ? last_year : $t('Today') }}</h4>
	</div>
</template>

<style scoped>

.hideicon {
	visibility: hidden;
}

@media screen and (max-width: 1080px) {

	.player-data h3 {
		font-size: 14;
	}

	.player-data h4 {
		font-size: 12px;
		font-weight: 600;
	}

	.player-data-item {
		padding: 10px 5px;
	}
}


@media screen and (max-width: 400px) {
	.player-data-item {
		padding: 10px 5px;
	}
}

</style>