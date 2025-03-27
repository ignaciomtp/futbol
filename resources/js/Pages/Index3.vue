<template>
  <div class="antialiased bg-dark">
    <nav class="navbar navbar-expand navbar-dark bg-dark" aria-label="Second navbar example">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">Always expand</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample02" 
          aria-controls="navbarsExample02" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExample02">
          <ul class="navbar-nav me-auto">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="#">Home</a>
            </li>
          </ul>

          <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="languageDropdown" role="button" 
                data-bs-toggle="dropdown" aria-expanded="false">
                {{ $page.props.locale.toUpperCase() }}
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
                <li>
                  <Link class="dropdown-item text-bg-light" :href="route('change.locale', 'en')">English</Link>
                </li>
                <li>
                  <Link class="dropdown-item" :href="route('change.locale', 'es')">Español</Link>
                </li>
              </ul>
            </li>
          </ul>

          <ul class="navbar-nav" v-if="$page.props.auth.user">
            <li class="nav-item">
              <Link :href="route('home')" class="nav-link">Home</Link>
            </li>
          </ul>
          <ul class="navbar-nav" v-else>
            <li class="nav-item">
              <Link :href="route('login')" class="nav-link">Log in</Link>
            </li>
            <li class="nav-item" v-if="route().has('register')">
              <Link :href="route('register')" class="nav-link">Register</Link>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <main class="container text-bg-dark p-4">
      <div class="row pt-4">
        <div class="col-md-3 text-center">
          <p>{{ __('left column') }}</p>
          <div>{{ $page.props.locale }}</div>
        </div>
        
        <div class="col-md-6">
          <div class="input-group mb-3 input-dropdown-container pl-5 pr-5">
            <input type="text" class="searchbox" 
              :placeholder="__('Type a footballer name here') + '...'" 
              v-model="searchQuery" 
              @input="searchPlayers"
              autocomplete="off">
            <span class="searchbox-button">
              <i class="bi bi-search text-bg-light"></i>
            </span>
            <div class="dropdown w-100">
              <ul class="dropdown-menu" id="suggestions" v-show="showSuggestions">
                <li v-for="player in suggestions" :key="player.id">
                  <div class="dropdown-item dropdown-player-item" @click="selectPlayer(player)">
                    <img :src="`/img/players/${player.photo}`" :alt="player.name" class="tinythumb" style="float: right">
                    {{ player.name }}
                  </div>
                </li>
              </ul>
            </div>
          </div>

          <div class="mt-5" id="guesses">
            <div v-for="guess in guesses" :key="guess.id" class="player-div-box p-3 mt-4 mb-4">
              <div class="data-row data-player-id">
                <img :src="`/img/players/${guess.photo}`" :alt="guess.name" class="round-thumb">
                <h2 class="text-bg-dark">{{ guess.name }}</h2>
              </div>
              
              <div class="player-data data-row mt-2">
                <div :id="`player${guess.id}Country`" :class="`player-data-item text-center ${getGuessClass(guess.id, 'country')}`">
                  <h3>{{ guess.country }}</h3>
                  <img :src="guess.country_flag" :alt="guess.country">
                </div>
                
                <div :id="`player${guess.id}Active`" :class="`player-data-item text-center ${getGuessClass(guess.id, 'active')}`">
                  <h3>Active</h3>
                  <h4>{{ guess.debut_season }} - {{ guess.last_season || 'Today' }}</h4>
                </div>
                
                <div :id="`player${guess.id}Position`" :class="`player-data-item text-center ${getGuessClass(guess.id, 'position')}`">
                  <h3>Position</h3>
                  <h4>{{ guess.position }}</h4>
                </div>
                
                <div :id="`player${guess.id}Clubs`" :class="`player-data-item-wide text-left ${getGuessClass(guess.id, 'clubs')}`">
                  <h3 class="text-center">Played for</h3>
                  <ul class="text-left">
                    <li v-for="club in guess.clubs" :key="club.id" class="mt-1 mb-1">
                      <div class="badge-box">
                        <img :src="`/img/clubs/${club.badge}`" class="club-badge">
                      </div>
                      <div class="club-name-box">{{ club.name }}</div>
                    </li>
                  </ul>
                </div>
                
                <div :id="`player${guess.id}Titles`" :class="`player-data-item-wide text-left ${getGuessClass(guess.id, 'titles')}`">
                  <h3 class="text-center">Won</h3>
                  <ul class="text-left">
                    <li v-for="title in guess.titles" :key="title.id">
                      <div class="num-titles-box">{{ title.pivot.number }}</div>
                      <div class="name-titles-box">{{ title.name }}</div>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-md-3 text-center">
          <p>{{ __('right column') }}</p>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { router, Link } from '@inertiajs/vue3';

const searchQuery = ref('');
const suggestions = ref([]);
const showSuggestions = ref(false);
const guesses = ref([]);
const guessResults = ref({});

const searchPlayers = async () => {
  if (searchQuery.value.length < 2) {
    suggestions.value = [];
    showSuggestions.value = false;
    return;
  }

  try {
    const response = await axios.post('/player/search/', { 
      name: searchQuery.value 
    });
    suggestions.value = response.data;
    showSuggestions.value = true;
  } catch (error) {
    console.error(error);
  }
};

const selectPlayer = async (player) => {
  guesses.value.unshift(player);
  searchQuery.value = '';
  suggestions.value = [];
  showSuggestions.value = false;
  
  await checkGuess(player.id);
};

const checkGuess = async (playerId) => {
  try {
    const response = await axios.get(`/checkresult/${playerId}`);
    guessResults.value[playerId] = response.data;
    
    // Remove flip class after animation
    setTimeout(() => {
      document.querySelectorAll('.player-data-item, .player-data-item-wide').forEach(el => {
        el.classList.remove('flip');
      });
    }, 250);
  } catch (error) {
    console.error(error);
  }
};

const getGuessClass = (playerId, field) => {
  const result = guessResults.value[playerId];
  if (!result) return 'wrong-guess';
  
  if (result.match) return 'right-guess flip';
  
  const fieldResult = result[field];
  if (fieldResult === 'right') return 'right-guess flip';
  if (fieldResult === 'partial') return 'partial-guess flip';
  
  return 'wrong-guess';
};

// Close suggestions when clicking outside
onMounted(() => {
  document.addEventListener('click', (event) => {
    if (!event.target.closest('.input-dropdown-container')) {
      showSuggestions.value = false;
    }
  });
});
</script>

<style scoped>
/* Add your existing CSS classes here */
.tinythumb {
  width: 30px;
  height: 30px;
  object-fit: cover;
}

.round-thumb {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  object-fit: cover;
}

.player-div-box {
  background-color: #2c3e50;
  border-radius: 8px;
}

.dropdown-player-item {
  cursor: pointer;
  padding: 8px 16px;
}

.dropdown-player-item:hover {
  background-color: #f8f9fa;
}

.player-data-item {
  flex: 1;
  padding: 10px;
  margin: 5px;
  border-radius: 5px;
}

.player-data-item-wide {
  flex: 2;
  padding: 10px;
  margin: 5px;
  border-radius: 5px;
}

.wrong-guess {
  background-color: #dc3545;
}

.right-guess {
  background-color: #28a745;
}

.partial-guess {
  background-color: #ffc107;
}

.flip {
  animation: flip 0.5s ease;
}

@keyframes flip {
  0% { transform: rotateX(0deg); }
  50% { transform: rotateX(90deg); }
  100% { transform: rotateX(0deg); }
}

.club-badge {
  width: 30px;
  height: 30px;
  object-fit: contain;
}

.badge-box {
  display: inline-block;
  margin-right: 10px;
}

.club-name-box {
  display: inline-block;
}

.num-titles-box {
  display: inline-block;
  margin-right: 10px;
  font-weight: bold;
}

.name-titles-box {
  display: inline-block;
}
</style>