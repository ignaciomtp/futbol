<template>
  <div v-if="visible" class="mobile-suggestions-overlay">
    <div class="mobile-suggestions-container">
      <div v-for="player in players" :key="player.id" 
           class="mobile-suggestion-item"
           @click="select(player)">
        <img :src="`/img/players/${player.photo}`" class="tinythumb">
        <span>{{ player.name }}</span>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  visible: Boolean,
  players: Array
});

const emit = defineEmits(['select']);

const select = (player) => {
  emit('select', player);
};
</script>

<style scoped>
.mobile-suggestions-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0,0,0,0.5);
  z-index: 1050;
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  padding-top: 20vh;
}

.mobile-suggestions-container {
  background: #2c3e50;
  max-height: 60vh;
  overflow-y: auto;
  margin: 0 20px;
  border-radius: 8px;
}

.mobile-suggestion-item {
  padding: 12px 16px;
  border-bottom: 1px solid #34495e;
  display: flex;
  align-items: center;
  gap: 10px;
}

.mobile-suggestion-item:active {
  background-color: #34495e;
}

.tinythumb {
  width: 30px;
  height: 30px;
  object-fit: cover;
  border-radius: 4px;
}
</style>