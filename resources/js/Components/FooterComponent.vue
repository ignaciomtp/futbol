<template>
	<div class="footer-container">
		<div class="sharethis-inline-share-buttons"></div>
		<div class="footer__bottom">
		  <div class="footer__copir">Footble.io © 2025</div>
		  <ul class="footer__links">
		      <li><a href="mailto:admin@footble.io?subject=Footble">Contact</a></li>
		      <li><a :href="route('privacy')">{{ $t('Privacy Policy') }}</a></li>
		  </ul>
		</div>		
	</div>
</template>