<script setup>
import PlayerContainer from '@/Components/PlayerContainer.vue';

const props = defineProps({
    player: Object
}); 

</script>
<template>
    <nav class="navbar navbar-expand navbar-dark bg-dark" aria-label="Second navbar example">
        <div class="container-fluid">

          <a class="navbar-brand" href="#">Always expand</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample02" aria-controls="navbarsExample02" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse" id="navbarsExample02">
            <ul class="navbar-nav me-auto">
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#">Home</a>
              </li>

            </ul>


          </div>
        </div>
    </nav>


    <main class="container text-bg-dark p-4">
        <div class="row pt-4">
            <div class="col-md-3 text-center">
                <p>left column</p>

            </div>
            <div class="col-md-6">
                <div class="input-group mb-3 input-dropdown-container pl-5 pr-5">

                    <input type="text" class="searchbox" placeholder="Type a footballer name here..." id="searchbox" autocomplete="off">
                    <span class="searchbox-button">
                        <i class="bi bi-search text-bg-light"></i>
                    </span>
                    <div class="dropdown w-100">
                        <ul class="dropdown-menu" id="suggestions">
                            
                        </ul>
                    </div>
                </div>

                <div class="mt-5" id="guesses">
                    <PlayerContainer :player="player" />
                </div>
            </div>
            <div class="col-md-3 text-center">
                 <p>right column</p>
            </div>
        </div>
    </main>
</template>
