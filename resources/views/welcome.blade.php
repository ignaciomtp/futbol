<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

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

        <div id="app"> 

            <main class="container p-4">

                <h1> How to Install Bootstrap 5 in Laravel 10 - ItSolutionstuiff.com</h1>

                <div class="card">

                  <div class="card-header">

                    Icons

                  </div>

                  <div class="card-body text-center">

                        <i class="bi bi-bag-heart-fill"></i>

                        <i class="bi bi-app"></i>

                        <i class="bi bi-arrow-right-square-fill"></i>

                        <i class="bi bi-bag-check-fill"></i>

                        <i class="bi bi-calendar-plus-fill"></i>

                  </div>

                </div>

            </main>

        </div>
        
    </body>
</html>
