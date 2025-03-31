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
		<h3><i class="bi bi-arrow-down" :class="{hideicon : era == 'anterior'}"></i> {{ $t('Active') }} <i class="bi bi-arrow-up" :class="{hideicon : era == 'posterior'}"></i></h3>
		<h4>{{ debut_year }} - {{ last_year ? last_year : $t('Today') }}</h4>
	</div>
</template>

<style scoped>

	.hideicon {
		visibility: hidden;
	}
</style>