<script setup>

	const props = defineProps({
	    titles: Array
	}); 

</script>
<template>
	<div class="player-data-item-wide text-left wrong-guess" >
		<h3 class="text-center">Won</h3>
		<ul class="text-left">
			<li  v-for="(title, index) in titles" :key="index + 1">
				<div class="num-titles-box">{{ title.pivot.number }}</div>
				<div class="name-titles-box">{{ title.name }}</div>
			</li>
		</ul>
	</div>
</template>