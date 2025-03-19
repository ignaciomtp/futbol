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

    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        .input-dropdown-container {
            position: relative;
            width: 100%; /* Asegura que ocupe todo el ancho del input-group */
        }
        .dropdown-menu {
            width: 100%; /* Igual al ancho del input */
            margin-top: 0 !important; /* Elimina márgenes extra */
        }

        .tinythumb {
            max-height: 50px;
        }
    </style>
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

    <div id="app"> 
        <main class="container p-4">
            <div class="m-5 text-center">
                <div class="input-group mb-3 input-dropdown-container">
                    <input type="text" class="form-control" placeholder="Type a guess here..." id="searchbox" autocomplete="off">
                    <div class="dropdown w-100">
                        <ul class="dropdown-menu" id="suggestions">
                            
                        </ul>
                    </div>
                </div>
            </div>
        </main>
    </div>

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

                let playersList = '';
                result.forEach(elem => {
                    playersList += '<li><a class="dropdown-item" href="#">' + elem.name + '<img class="tinythumb" src="../../img/players/'+ elem.photo +'" style="float:right;" /></a></li>';
                });

                $('#suggestions').append(playersList);
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
    </script>
</body>
</html>