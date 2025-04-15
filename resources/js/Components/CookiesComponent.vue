<script setup>
import { ref, onMounted  } from 'vue';
import { Modal } from 'bootstrap';

const modalResult = ref(null);

const setCookie = (cname, cvalue, exdays) => {
	console.log('This is setCookie');
  const d = new Date();
  d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
  let expires = "expires="+d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

const getCookie = (cname) => {
  let name = cname + "=";
  let ca = document.cookie.split(';');
  for(let i = 0; i < ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

const checkCookie = () => {
  let user = getCookie("footble");

  console.log('User (cookie): ', user);

  if (user != "consent") {
  	showModal();
  }

}


const consent = () => {
	setCookie("footble", "consent", 365);
	hideModal();
}

const dontConsent = () => {
	window.location.replace("https://www.google.com/");
}


// Método para mostrar el modal
const showModal = () => {
  modalResult.value.show();
};

// Método para ocultar el modal
const hideModal = () => {
  modalResult.value.hide();
};

onMounted(() => {
	modalResult.value = new Modal(document.getElementById('consentModal'), {
      focus: false, // Desactiva el enfoque automático
      keyboard: false // Opciones adicionales si las necesitas
    });

	checkCookie();

});
</script>
<template>

<!-- Modal -->
<div class="modal fade" id="consentModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
        <button type="button" class="btn-close" @click="hideModal"></button>
      </div>
      <div class="modal-body">
        <div class="text-center" id="welcome">
        	<h2>{{ $t('Welcome to Footble') }}</h2>
        	<h3>{{ $t('The football Wordle game') }}</h3>
        	<p>{{ $t('We use cookies and similar technologies to enhance your experience, analyze site performance, and deliver personalized ads') }}. {{ $t('Here’s what you need to know') }}:</p>
        </div>

        <div class="text-left pl-4 pr-4 smallfont">
        	<p class="m-2 text-justify"><strong>{{ $t('Local Storage') }}:</strong> {{ $t('Footble stores game progress (e.g., scores or settings) directly in your browser for functionality') }}. {{ $t('No personal data is collected or sent to us') }}.</p>
        	<p class="m-2 text-justify"><strong>Analytics:</strong> {{ $t('We use Google Analytics to understand how our site is used') }}. {{ $t('This may involve cookies to track anonymized usage patterns') }}.</p>
        	<p class="m-2 text-justify">
        	<strong>{{ $t('Advertising') }}:</strong> {{ $t('We partner with ad providers that may use cookies to serve relevant ads based on your interests and browsing behavior') }}.
        	</p>
        </div>

        <div class="text-left">
        	<p class="text-justify smallfont">{{ $t('By continuing to use Footble, you consent to our use of cookies as described') }}. {{ $t('You can manage your cookie preferences through your browser settings at any time') }}.
			{{ $t('For more details, see our') }} <a :href="route('privacy')">{{ $t('Privacy Policy') }}</a>.</p>
        </div>
      </div>
      <div class="modal-footer ">
        <button type="button" class="btn btn-secondary" @click="dontConsent">{{ $t('No Consent') }}</button>
        <button type="button" class="btn btn-success" @click="consent">{{ $t('Consent') }}</button>
      </div>
    </div>
  </div>
</div>

</template>

<style scoped>
.modal-footer {
	justify-content: space-around;
}

.smallfont {
	font-size: 0.9rem;
}

#welcome p{
	font-weight: bold;
}

#welcome h2 {
	font-size: 1.65rem;
}

#welcome h3 {
	font-size: 1rem;
	text-transform: uppercase;
	color: #888;
}

.btn {
  border-radius: 100px;
  min-width: 160px;
  font-weight: 600;
}

</style>
