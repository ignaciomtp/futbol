<template>
<ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">{{ $t('How to play') }}</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">{{ $t('Attributes') }}</button>
  </li>

</ul>

<div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade show active p-2" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
    <div class="text-center">
      <h4>{{ $t('Guess the footballer in 10 tries') }}.</h4>
      <p>{{ $t('Each guess reveals the attributes of footballer') }}.

        {{ $t('The color of the tiles will change to show how close your guess was') }}.
      </p>          
    </div>

    <div class="text-left instructions">
      <h5>{{ $t('Step') }} 1</h5>
      <p>{{ $t('Guess a footballer using the search bar') }}.</p>

      <div class="text-center">
        <img :src="'/img/guess1.png'" class="img-fluid">
      </div>          

      <h5>{{ $t('Step') }} 2</h5>
      <p>{{ $t('Make another guess based off the new information') }}.</p>

      <div class="text-center">
        <img :src="'/img/guess3.png'" class="img-fluid">
      </div>          

      <h5>{{ $t('Step') }} 3</h5>
      <p>{{ $t('Keep going until you guess the footballer') }}. {{ $t('A new Footble will appear every day!') }}</p>

      <div class="text-center">
        <img :src="'/img/guess4.png'" class="img-fluid">
      </div>        

    </div>

  </div>
  <div class="tab-pane fade p-2" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
  	<h5>{{ $t('Country') }}</h5>
  	<p>{{ $t('The country the footballer played for') }}. {{ $t('Might not coincide with his country of birth') }}.</p>
  	<div class="player-data data-row mb-2">
  		<div class="player-data-item text-center wrong-guess"><p>{{ $t('Incorrect') }}</p></div>
  		<div class="player-data-item text-center partial-guess"><p>{{ $t('In the same continent') }}</p></div>
  		<div class="player-data-item text-center right-guess"><p>{{ $t('Correct') }}</p></div>
  	</div>

  	<h5>{{ $t('Active') }}</h5>
  	<p>{{ $t('The years he was profesionally active') }}. {{ $t('A down arrow means the target player is from an older era, an up arrow a newer one') }}.</p>
  	<div class="player-data data-row mb-2">
  		<div class="player-data-item text-center wrong-guess"><p>{{ $t("Careers don't overlap") }}</p></div>
  		<div class="player-data-item text-center partial-guess"><p>{{ $t('Careers overlap less than 10 years') }}</p></div>
  		<div class="player-data-item text-center right-guess"><p>{{ $t('Careers overlap 10 years or more') }}</p></div>
  	</div>

  	<h5>{{ $t('Position') }}</h5>
  	<p>{{ $t('The general position he mostly played') }}. {{ $t('Options are goalkeeper, defender, midfielder and attacker') }}.</p>
  	<div class="player-data data-row mb-2">
  		<div class="player-data-item text-center wrong-guess"><p>{{ $t('Incorrect') }}</p></div>
  		<div class="player-data-item text-center right-guess"><p>{{ $t('Correct') }}</p></div>
  	</div>

  	<h5>{{ $t('Clubs') }}</h5>
  	<p>{{ $t('The clubs the footballer played for') }}. {{ $t('Not necessarily in that order') }}. </p>
  	<div class="player-data data-row mb-2">
  		<div class="player-data-item text-center wrong-guess"><p>{{ $t("Didn't play for any of those clubs") }}</p></div>
  		<div class="player-data-item text-center right-guess"><p>{{ $t('Played for at least one of those clubs') }}</p></div>
  	</div>

  	<h5>{{ $t('Won') }}</h5>
  	<p>{{ $t('The most important titles and awards the player won') }}.</p>
  	<div class="player-data data-row mb-2">
  		<div class="player-data-item text-center wrong-guess"><p>{{ $t("Didn't win any of those") }}</p></div>
  		<div class="player-data-item text-center partial-guess"><p>{{ $t('Won exactly one of those') }}</p></div>
  		<div class="player-data-item text-center right-guess"><p>{{ $t('Won more than one of those') }}</p></div>
  	</div>
  </div>
 
  
</div>
</template>

<style scoped>
	.nav-item {
		margin: 0 20px;
	}

  .player-data-item {
    display: grid;
    place-items: center; /* Centra tanto horizontal como verticalmente */
  }

  .player-data-item p{
    line-height: 1.1rem;
    margin-bottom: 0;
  }
</style>