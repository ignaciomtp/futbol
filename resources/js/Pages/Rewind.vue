<script setup>
import NavigationBar from '@/Components/NavigationBar.vue';

const changeLocale = async (locale) => {
    try {
        const response = await axios.post('/change-locale/', { 
            locale: locale
        });

        if (response.data.message === 'locale changed') {
            // Cambiar el idioma en el frontend
            loadLanguageAsync(locale);

        }
    } catch (error) {
        console.error('Error changing locale:', error);
    }
}
</script>

<template>
	<NavigationBar />

  	<main class="container text-bg-dark mt-5 p-4">
  		<div class="row pt-4">
  			<div class="col-md-3 text-center"></div>
  			<div class="col-md-6">
  				<h1>Rewind</h1>
  			</div>
  			<div class="col-md-3 text-center"></div>
  		</div>
  	</main>
</template>