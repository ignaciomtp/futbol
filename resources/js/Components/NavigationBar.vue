<script setup>
import { usePage } from '@inertiajs/vue3';


// Acceder a los datos compartidos por Inertia
const page = usePage();
const locale = page.props.locale; // El locale compartido desde Laravel

const emit = defineEmits(['locale-changed']);

const emitChangeLocale = (locale) => {
	emit('locale-changed', locale);
}


</script>

<template>
	<nav class="navbar navbar-expand fixed-top navbar-dark bg-dark" aria-label="Second navbar example">
    	<div class="container-fluid">
    		<div class="collapse navbar-collapse" id="navbarsExample02">
	            <ul class="navbar-nav me-auto">
	              <li class="nav-item">
	                <a class="nav-link active" aria-current="page" href="#">Home</a>
	              </li>
	            </ul>
	            <ul class="navbar-nav mr-3 text-end">
	                <!-- Otros elementos de la navbar -->
	                <li class="nav-item dropdown">
	                    <a class="nav-link dropdown-toggle" href="#" id="languageDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
	                       {{ locale.toUpperCase() }} <!-- Muestra el locale actual en mayúsculas -->
	                    </a>
	                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
	                        <li>
	                            <button class="dropdown-item text-bg-light" @click="emitChangeLocale('en')">English</button>
	                        </li>
	                        <li>
	                            <button class="dropdown-item" @click="emitChangeLocale('es')">Español</button>
	                        </li>
	                    </ul>
	                </li>
	            </ul>
        	</div>
    	</div>
  	</nav>
</template>
<style scoped>
	.bg-dark{
		background-color: #000 !important;
	}
</style>