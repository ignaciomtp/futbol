<script setup>

const emit = defineEmits(['play-game']);

const emitPlayGame = () => {
	emit('play-game');
}

</script>

<template>

	<div class="mt-3 text-center p-3">
		<div id="logo">
			<h1>Footble</h1><span>⚽</span>
		</div>

		<div class="mt-3 mb-3" >
			<p class="p-1 hometext">10 {{ $t('guesses') }}, 1000 {{ $t('footballers') }}</p> 
			<p class="p-1 hometext">{{ $t('Will you win today?') }}</p>
		</div>

		<div class="mt-5">
			<button class="btn bigger btn-success" @click="emitPlayGame">{{ $t('Play') }}</button>
		</div>
		
	</div>


</template>
<style scoped>

#logo {
  background-image: url('/img/soccer-field-2.png');
  background-size: contain;
  background-position: center;
  background-repeat: no-repeat;
  min-height: 200px; /* Ajusta esta altura según tus necesidades */
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
}

#logo h1 {
  color: #002600; /* O el color que prefieras para que contraste con la imagen */
  margin: 0;
  text-align: center;
  font-family: "Oswald", sans-serif;
  font-optical-sizing: auto;
  font-size: 3.5rem;
  padding: 0 !important;
}

#logo span {
  font-size: 1.8rem;
  vertical-align: baseline;
  padding-top: 15px;
  
}



/* Media queries para responsividad */
@media (max-width: 768px) {
  #logo {
    min-height: 150px; /* Altura menor para pantallas más pequeñas */
  }
}

@media (max-width: 480px) {
  #logo {
    min-height: 150px;
  }
  
  #logo h1 {
    font-size: 2.8rem; /* Tamaño de fuente menor para móviles */
  }

  #logo span {
    font-size: 1.5rem;
    vertical-align: text-top;

    padding-top: 8px;
  }
}
</style>