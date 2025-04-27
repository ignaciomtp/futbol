<script setup>
import { ref, watch, onUnmounted } from 'vue';

const props = defineProps({ 
  start: Boolean
});

const timeRemaining = ref({
  hours: 0,
  minutes: 0,
  seconds: 0,
  hoursString: '',
  minutesString: '',
  secondsString: ''
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

  timeRemaining.value.hoursString = timeRemaining.value.hours < 10 ? '0' + timeRemaining.value.hours : timeRemaining.value.hours.toString();

  timeRemaining.value.minutesString = timeRemaining.value.minutes < 10 ? '0' + timeRemaining.value.minutes : timeRemaining.value.minutes.toString();

  timeRemaining.value.secondsString = timeRemaining.value.seconds < 10 ? '0' + timeRemaining.value.seconds : timeRemaining.value.seconds.toString();
};

const stopCountdown = () => {
  if (intervalId.value) {
    clearInterval(intervalId.value);
    intervalId.value = null;

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
    <div style="line-height: 1;" class="mb-1">{{ $t('Next Footble') }}</div>
    <div style="line-height: 1;">
      <span>{{ timeRemaining.hoursString }} : </span>
      <span>{{ timeRemaining.minutesString }} : </span>
    <span>{{ timeRemaining.secondsString }}</span>
    </div>
    
  </div>
</template>
<style scoped>
  .countdown {
    font-size: 1.5rem;
  }
</style>