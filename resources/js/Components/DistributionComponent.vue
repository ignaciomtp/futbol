<script setup>

const props = defineProps({
  rango: Object,
});
	
</script>
<template>

<div class="distribution-row" >
	<div class="guess-label ">
		{{ rango.label }}
	</div>
	<div class="guess-bar-container">
		<div class="guess-bar " :style="{ width: rango['percent'] + '%' }">
			<span class="bar-value">
				{{ rango.value }}
			</span>
		</div>
	</div>
</div>
	
</template>
<style scoped>



.distribution-row {
	display: flex;
    align-items: center;
    font-weight: bold;
    margin-bottom: 8px;
    width: 100%;
}

.guess-label {
    width: 30px;
    text-align: center;
    margin-right: 10px;
    font-size: 14px;
    color: #b5b5b5;
}

.guess-bar-container {
    flex: 1;
    height: 24px;
    background-color: #454545;
    border-radius: 4px;
}

.guess-bar {
    height: 100%;
    background-color: #198754;
    border-radius: 4px;
    display: flex;
    align-items: center;
    padding-left: 10px;
    min-width: 24px;
    transition: width .5s ease;
}

.bar-value {
    color: #fff;
    font-weight: 700;
    font-size: 14px;
}

</style>