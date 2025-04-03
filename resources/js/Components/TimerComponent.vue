<script setup>
import { ref, watch, onUnmounted } from 'vue';

const props = defineProps({ 
  start: Boolean
});

const timeRemaining = ref({
  hours: 0,
  minutes: 0,
  seconds: 0
});

const intervalId = ref(null); // Hacer intervalId reactivo

const startCountdown = () => {
  updateCountdown();
  intervalId.value = setInterval(() => {
    updateCountdown();
  }, 1000);
};

const updateCountdown = () => {
  const now = new Date();
  const nextChange = new Date(now);
  nextChange.setHours(24, 0, 0, 0);

  const diffMs = nextChange - now;
  
  timeRemaining.value.hours = Math.floor(diffMs / (1000 * 60 * 60));
  timeRemaining.value.minutes = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));
  timeRemaining.value.seconds = Math.floor((diffMs % (1000 * 60)) / 1000);	
};

const stopCountdown = () => {
  if (intervalId.value) {
    clearInterval(intervalId.value);
    intervalId.value = null;
    console.log('Countdown stopped!');
  }
};

// Observa cambios en 'start' y ejecuta la función correspondiente
watch(() => props.start, (newValue) => {
  
  if (newValue) {
    startCountdown();
  } else {
    stopCountdown();
  }
}, { immediate: true }); // Ejecutar inmediatamente al montar si start es true

// Limpia el intervalo si el componente se desmonta
onUnmounted(() => {
  stopCountdown();
});
</script>

<template>
  <div class="countdown">
    <div>Next Footble </div>
    <div>
      <span>{{ timeRemaining.hours }}:</span>
      <span>{{ timeRemaining.minutes }}:</span>
    <span>{{ timeRemaining.seconds }}</span>
    </div>
    
  </div>
</template>
<style scoped>
  .countdown {
    font-size: 20px;
  }
</style>