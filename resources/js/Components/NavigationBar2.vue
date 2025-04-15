<script setup>

import axios from 'axios';
import { usePage } from '@inertiajs/vue3';
import { Modal } from 'bootstrap';
import { loadLanguageAsync } from 'laravel-vue-i18n';


// Acceder a los datos compartidos por Inertia
const page = usePage();
let locale = page.props.locale; // El locale compartido desde Laravel


// Definimos el prop con un valor por defecto
const props = defineProps({
  updateTrigger: {
    type: Boolean, // Tipo del prop
    default: false, // Valor por defecto si no se pasa
  },
});


const showSidebarMenu = () => {
	let navigationModal = new Modal(document.getElementById('navigation'));
	const buttonSidebar = document.getElementById('buttonSidebar');
	const navigationModal2 = document.getElementById('navigation');

	navigationModal2.addEventListener('hidden.bs.modal', () => {
    // Mueve el foco al botón que abrió la modal
    buttonSidebar.focus();
  });  

  navigationModal.show();
}



const changeLocale = async (newLocale) => {
    try {
        const response = await axios.post('/change-locale/', { 
            locale: newLocale
        });

        if (response.data.message === 'locale changed') {
            // Cambiar el idioma en el frontend
            loadLanguageAsync(newLocale);
            locale = newLocale;
        }
    } catch (error) {
        console.error('Error changing locale:', error);
    }
}

</script>

<template>

	<nav class="navbar navbar-expand fixed-top navbar-dark navbar-background" aria-label="Second navbar example">
    	<div class="container container-fluid">
    		<div class="collapse navbar-collapse" id="navbarsExample02">
	            <ul class="navbar-nav me-auto">
	              <li class="nav-item">
	              	<button class="bar-button" @click="showSidebarMenu" id="buttonSidebar">
	              		<i class="bi bi-list bar-button"></i>
	              	</button>
	                
	              </li>
	            </ul>
	            <ul class="navbar-nav mr-3 text-end">
	                <li class="nav-item dropdown">
	                    <a class="nav-link dropdown-toggle" href="#" id="languageDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
	                       {{ locale.toUpperCase() }} <!-- Muestra el locale actual en mayúsculas -->
	                    </a>
	                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
	                        <li>
	                            <button class="dropdown-item text-bg-light" @click="changeLocale('en')">English</button>
	                        </li>
	                        <li>
	                            <button class="dropdown-item" @click="changeLocale('es')">Español</button>
	                        </li>
	                        <li>
	                            <button class="dropdown-item" @click="changeLocale('fr')">Français</button>
	                        </li>
	                        <li>
	                            <button class="dropdown-item" @click="changeLocale('de')">Deutsch</button>
	                        </li>
	                        <li>
	                            <button class="dropdown-item" @click="changeLocale('it')">Italiano</button>
	                        </li>
	                    </ul>
	                </li>
	            </ul>
        	</div>
    	</div>
  	</nav>



<!-- Modal Navegación -->
<div class="modal" tabindex="-1" id="navigation">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header wrong-guess">
        
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body bg-dark p-2">
        <div class="sidebar">
            <ul>
              <li>
                <h4>
                	<a :href="route('homeapp')">{{ $t('Home') }}</a>
                </h4>
              </li>
              <li>
                <h4>{{ $t('Follow Us') }}</h4>
              </li>
              <li>
                <h4>
                	<a :href="route('privacy')">{{ $t('Privacy') }}</a>
                </h4>
              </li>
            </ul>

            <h3>{{ $t('Need more Footble?') }}</h3>

            <ul>
              <li>
                <h5><a :href="route('rewind')">{{ $t('Rewind') }}</a></h5>
                <p>{{ $t('Play the last week of Footble') }}</p>
              </li>
              <li>
                <h5><a :href="route('create')">{{ $t('Create') }}</a></h5>
                <p>{{ $t('Make a Footble for your friends!') }}</p>
              </li>
            </ul>
        </div>
      </div>
      <div class="my-modal-bottom bg-dark" >
      	<div style="min-height: 10px;"></div>
      </div>

    </div>
  </div>
</div>

</template>
<style scoped>
	.fullwidth {
		width: 100%;
	}

	.stat-value {
	    font-size: 24px;
	    font-weight: 700;
	    margin-bottom: 5px;
	}

	.stat-label {
	    font-size: 12px;
	    color: #b5b5b5;
	    text-align: center;
	}

	.navbar-background {
		background-color: #000 !important;
	}

	.bar-button {
		color: #FFF;
		font-size: 1.5rem;
		background-color: transparent;
		border: none;
	}

	.nav-item {
		margin-left: 10px;
		margin-right: 10px;
	}

	.modal-content {
	  color: #FFF;
	}

	.btn-close {
	    filter: invert(1) grayscale(100%) brightness(200%);
	}

	.sidebar ul {
	  margin: 30px 0;
	  list-style: none;
	}

	.sidebar ul li {
	  margin: 20px 0;
	}

	.sidebar p{
	  color: #b5b5b5;
	}

	.sidebar h3 {
	  padding-left: 20px;
	}
</style>