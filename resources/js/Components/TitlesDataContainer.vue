<script setup>

	const props = defineProps({
	    titles: Array,
	    guessClass: String,
  		isFlipping: Boolean
	}); 


</script>
<template>
	<div :class="['player-data-item-wide', 'text-left', guessClass, { 'flip': isFlipping }]">
		<h3 class="text-center">{{ $t('Won') }}</h3>
		<ul class="text-left">
			<li  v-for="(title, index) in titles" :key="index + 1">
				<div class="num-titles-box">{{ title.pivot.number }}</div>
				<div class="name-titles-box">{{ title.name }}</div>
			</li>
		</ul>
	</div>
</template>