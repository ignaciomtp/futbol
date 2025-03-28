<script setup>

	const props = defineProps({
	    debut_year: Number,
	    last_year: Number,
	    guessClass: String,
  		isFlipping: Boolean
	}); 


</script>
<template>
	<div :class="['player-data-item', 'text-center', guessClass, { 'flip': isFlipping }]">
		<h3>{{ $t('Active') }}</h3>
		<h4>{{ debut_year }} - {{ last_year ? last_year : $t('Today') }}</h4>
	</div>
</template>