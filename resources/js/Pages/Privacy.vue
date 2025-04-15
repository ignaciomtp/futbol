<script setup>
import NavigationBar2 from '@/Components/NavigationBar2.vue';
</script>

<template>
	<NavigationBar2 />

  	<main class="container text-bg-dark mt-5 p-4">
  		<div class="row pt-3">
  			<div class="col-md-3 "></div>
  			<div class="col-md-6">
  				<div class="mb-4">
  					<h1 class="text-center "><i class="bi bi-incognito"></i> {{ $t('Privacy Policy') }}</h1>

  					<p class="mt-5">{{ $t('Last updated') }}: {{ $t('April 14, 2025') }}</p>

  					<div class="text-justify">
	  					<p>{{ $t('Welcome to Footble, a football-themed puzzle game') }}. {{ $t('This Privacy Policy explains how we handle information when you use our website, footble.io (#)') }} ("{{ $t('Site') }}"). {{ $t('We are committed to protecting your privacy and complying with applicable data protection laws') }}, {{ $t('including the EU General Data Protection Regulation (GDPR), UK GDPR, and US privacy laws (e.g., California Consumer Privacy Act, CCPA)') }}.
	  					</p>

	  					<h2>1. {{ $t('Information We Collect') }}</h2>
	  					<p class="boldfont">{{ $t('No Personal Data Collection by Footble') }}</p>
						<p>{{ $t('Footble does not collect, store, or process personal data (e.g., names, email addresses) directly from users') }}. {{ $t('Our game operates without requiring user accounts or personal information') }}.
						</p>

						<p class="boldfont">{{ $t('Local Storage') }}</p>
						<p>{{ $t('To provide a seamless experience, Footble uses browser-based technologies (e.g., local storage) to save game progress') }}, {{ $t('such as scores or preferences, directly on your device') }}. {{ $t('This data is not transmitted to us or shared with third parties') }}.
	  					</p>

	  					<p class="boldfont">Analytics</p>
	  					<p>{{ $t('We use Google Analytics to understand how users interact with our Site') }}. {{ $t('This may collect anonymized data, such as') }}:</p>
	  					<ul>
	  						<li>{{ $t('Device and browser information (e.g., type, operating system)') }}.</li>
	  						<li>{{ $t('Usage data (e.g., pages visited, time spent on the Site)') }}.</li>
	  						<li>{{ $t('Approximate location (e.g., country-level, based on anonymized IP addresses)') }}.</li>
	  					</ul>

	  					<p>{{ $t('Google Analytics uses cookies or similar technologies') }}. {{ $t('We configure it to anonymize IP addresses to minimize personal data processing') }}.</p>

	  					<p class="boldfont">{{ $t('Advertising') }}</p>
	  					<p>{{ $t('We may display ads via third-party providers, such as Google AdSense') }}. {{ $t('These providers may use cookies or similar technologies to serve personalized ads based on your browsing behavior or interests') }}. {{ $t('This may involve') }}:</p>
	  					<ul>
	  						<li>{{ $t('Tracking your interactions with ads') }}.</li>
	  						<li>{{ $t('Collecting device identifiers or anonymized data to tailor ads') }}.</li>
	  					</ul>


	  					<h2>2. {{ $t('How We Use Information') }}</h2>
	  					<p>{{ $t('We use the limited data described above to') }}:</p>
	  					<ul>
	  						<li>{{ $t('Enable core game functionality (via local storage)') }}.</li>
	  						<li>{{ $t('Improve our Site’s performance and user experience (via analytics)') }}.</li>
	  						<li>{{ $t('Deliver relevant advertisements (via ad providers)') }}.</li>
	  					</ul>

	  					<h2>3. {{ $t('Legal Basis for Processing (EU/UK Users)') }}</h2>
	  					<p>{{ $t('For users in the EU and UK, our legal bases under GDPR/UK GDPR are') }}:</p>
	  					<ul>
	  						<li><strong>{{ $t('Legitimate Interests') }}:</strong> {{ $t('For analytics and local storage to improve and operate the Site, where this does not override your rights') }}.</li>
	  						<li><strong>{{ $t('Consent:') }}</strong> {{ $t('For non-essential cookies (e.g., personalized ads), which you provide by continuing to use the Site or via your browser settings') }}.</li>
	  					</ul>

	  					<h2>4. {{ $t('Sharing Your Information') }}</h2>
	  					<p>{{ $t('We do not sell or share personal data') }}. {{ $t('However, anonymized or aggregated data may be shared with') }}:</p>
	  					<ul>
	  						<li><strong>Google Analytics:</strong> {{ $t('To analyze Site usage') }} (<a href="https://policies.google.com/privacy" target="_blank">{{ $t('see Google’s Privacy Policy') }}</a>).</li>
	  						<li><strong>{{ $t('Ad Providers') }}:</strong> {{ $t('To serve ads') }} (e.g., Google AdSense).</li>
	  					</ul>


	  					<h2>5. {{ $t('Your Rights and Choices') }}</h2>
	  					<p>{{ $t('You have control over your data') }}:</p>
	  					<ul>
	  						<li><strong>{{ $t('Browser Settings') }}:</strong> {{ $t('Disable cookies or local storage via your browser to opt out of analytics or personalized ads') }} ({{ $t('note: this may affect game functionality') }}).</li>
	  						<li><strong>{{ $t('Do Not Track') }}:</strong> {{ $t("We respect 'Do Not Track' signals where supported by third-party providers") }}.</li>
	  						<li><strong>{{ $t('EU/UK Rights') }} (GDPR/UK GDPR):</strong> {{ $t('If any personal data is processed, you may have rights to access, rectify, erase, restrict, or port it, or object to processing') }}. {{ $t('Contact us at') }} [admin@footble.io].</li>
	  						<li><strong>{{ $t('US Rights') }} (e.g., CCPA):</strong> {{ $t('You can opt out of targeted ads via browser settings. We do not sell personal data') }}.</li>
	  					</ul>

	  					<p>{{ $t('To exercise these rights, contact us at') }} [admin@footble.io].</p>

	  					<h2>6. {{ $t('Third-Party Links') }}</h2>
	  					<p>{{ $t('Our Site may contain links to third-party websites (e.g., ad networks)') }}. {{ $t('We are not responsible for their privacy practices') }}. {{ $t('Please review their policies') }}.</p>

	  					<h2>7. {{ $t('Data Security') }}</h2>
	  					<p>{{ $t('We take reasonable measures to protect our Site, but no method is 100% secure') }}. {{ $t('Local storage remains on your device and is not transmitted to us') }}.</p>

	  					<h2>8. {{ $t('International Data Transfers') }}</h2>
	  					<p>{{ $t('As Footble operates globally, third-party services (e.g., Google) may process data outside the EU/UK/US') }}. {{ $t('We rely on their compliance mechanisms (e.g., Standard Contractual Clauses) to ensure adequate protection') }}.</p>

	  					<h2>9. {{ $t('Children’s Privacy') }}</h2>
	  					<p>{{ $t('Footble is not intended for users under 13 (or 16 in the EU/UK)') }}. {{ $t('We do not knowingly collect data from children') }}. {{ $t('If you believe we have such data, contact us at') }} [admin@footble.io].</p>

	  					<h2>10. {{ $t('Changes to This Policy') }}</h2>
	  					<p>{{ $t('We may update this Privacy Policy to reflect changes in our practices or legal requirements') }}. {{ $t('We will notify you of significant changes via the Site') }}.</p>

	  					<h2>11. {{ $t('Contact Us') }}</h2>
	  					<p>{{ $t('For questions or concerns about this Privacy Policy, contact us at') }}:
						[admin@footble.io]</p>


  					</div>
  				</div>
  			</div>
  			<div class="col-md-3 "></div>
  		</div>
  	</main>
</template>

<style scoped>

h2 {
	font-size: 1.5rem;
	border-bottom: 1px dotted #ccc;
	margin: 20px 0;
}

.boldfont {
	font-weight: bold;
	margin-bottom: 0;
}

</style>