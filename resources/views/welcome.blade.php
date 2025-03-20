<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ URL::to('css/styles-admin.css') }}" />

    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    
</head>
<body class="antialiased">
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
                @if (Route::has('login'))
                    <ul class="navbar-nav ">
                        @auth
                        <li class="nav-item">
                            <a href="{{ url('/home') }}" class="nav-link">Home</a>
                        </li>
                        @else
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="nav-link">Log in</a>
                        </li>
                            @if (Route::has('register'))
                            <li class="nav-item">
                                <a href="{{ route('register') }}" class="nav-link">Register</a>
                            </li>
                            @endif
                        @endauth
                    </ul>
                @endif
          </div>
        </div>
    </nav>


    <main class="container text-center p-4">
        <div class="row pt-4">
            <div class="col-md-4">
                <p>col left</p>
            </div>
            <div class="col-md-4">
                <div class="input-group mb-3 input-dropdown-container">
                    <input type="text" class="form-control" placeholder="Type a guess here..." id="searchbox" autocomplete="off">
                    <div class="dropdown w-100">
                        <ul class="dropdown-menu" id="suggestions">
                            
                        </ul>
                    </div>
                </div>

                <div class="mt-5" id="guesses">
                    
                </div>
            </div>
            <div class="col-md-4">
                 <p>col right</p>
            </div>
        </div>
    </main>


    <script>
    $(document).ready(function() {
        // Mostrar el dropdown al escribir
        $('#searchbox').on('keyup', function() {

            $('#suggestions').empty();

            let playerName = $('#searchbox').val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            let url = window.location.origin + '/player/search/';

            $.ajax({
              url: url,
              type : 'POST',
              data: {name: playerName},
              success: function(result){
                console.log(result);

                
                result.forEach(elem => {
                    showPlayer(elem);
                });

                
              },
              error: function(error) {
                  // Manejar el error
                  console.error(error);
              }
            });   

            $('#suggestions').addClass('show');    


        });

        // Ajustar posición del dropdown
        $('#suggestions').on('show.bs.dropdown shown.bs.dropdown', function() {
            const $input = $('#searchbox');
            const inputHeight = $input.outerHeight();
            const inputOffset = $input.offset();

            $('#suggestions').css({
                'top': inputHeight, // Justo debajo del input
                'left': 0,
                'position': 'absolute'
            });
        });

        // Ocultar el dropdown al hacer clic fuera
        $(document).on('click', function(event) {
            if (!$(event.target).closest('.input-dropdown-container').length) {
                $('#suggestions').removeClass('show');
            }
        });
    });


    function showPlayer(player) {


      var playerLi = $('<li>');
      
      var playerDiv = $('<div>').addClass('dropdown-item');
      playerDiv.addClass('dropdown-player-item');

      
      // Crea la imagen con la clase round-thumb y los atributos src y alt
      var playerImg = $('<img>')
        .attr('src', '../../img/players/' + player.photo)
        .attr('alt', player.name)
        .addClass('tinythumb');

      playerImg.css('float', 'right');

      playerDiv.append(document.createTextNode(player.name));
      playerDiv.append(playerImg);

      playerDiv.on('click', function(){
          // Crea el div principal con la clase player-div-box
          var playerDiv = $('<div>').addClass('player-div-box p-2 mt-2 mb-2');

          // Crea la imagen con la clase round-thumb y los atributos src y alt
          var playerImg = $('<img>')
            .attr('src', '../../img/players/' + player.photo)
            .attr('alt', player.name)
            .addClass('round-thumb');

          // Crea el encabezado h2 con el nombre del jugador
          var playerName = $('<h2>').text(player.name);

          // Crea la primera fila de datos
          var firstRow = $('<div>').addClass('data-row data-player-id');

          // Crea el div contenedor de los datos
          var playerDataDiv = $('<div>').addClass('player-data data-row mt-2');

          // Crear los divs de los datos
          var playerCountry = $('<div>').addClass('player-data-item wrong-guess p-2');
          var playerActive = $('<div>').addClass('player-data-item wrong-guess p-2');
          var playerPosition = $('<div>').addClass('player-data-item wrong-guess p-2');
          var playerClubs = $('<div>').addClass('player-data-item wrong-guess p-2');
          var playerTitles = $('<div>').addClass('player-data-item wrong-guess p-2');

          var countryTag = $('<h3>').text(player.country);
          var activeTag = $('<h3>').text('Active');
          var positionTag = $('<h3>').text('Position');
          var clubsTag = $('<h3>').text('Played for');
          var titlesTag = $('<h3>').text('Won');


          var countryData = $('<img>')
            .attr('src', player.country_flag)
            .attr('alt', player.country);

          var activeData = $('<h4>').text(player.debut_season + '-' + (player.last_season ? player.last_season : 'Today'));
          var positionData = $('<h4>').text(player.position);

          //var clubData 

          playerCountry.append(countryTag);
          playerCountry.append(countryData);

          playerActive.append(activeTag);
          playerActive.append(activeData);

          playerPosition.append(positionTag);
          playerPosition.append(positionData);

          // Agregar divs de datos al contenedor de datos
          playerDataDiv.append(playerCountry);
          playerDataDiv.append(playerActive);
          playerDataDiv.append(playerPosition);


          // Agrega la imagen, el encabezado y los datos al div principal
          firstRow.append(playerImg);
          firstRow.append(playerName);
          playerDiv.append(firstRow);
          playerDiv.append(playerDataDiv);
                
          $('#guesses').prepend(playerDiv);         
      });

            
      $('#suggestions').append(playerDiv);

    }




    </script>
</body>
</html>