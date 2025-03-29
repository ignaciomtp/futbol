<!DOCTYPE html>
  <html>
  <head>
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

      <!-- CSRF Token -->
      <meta name="csrf-token" content="{{ csrf_token() }}">

      <title>{{ config('app.name', 'Laravel') }}</title>
      <link rel="shortcut icon" href="{{{ asset('img/ball.png') }}}">

      <!-- Fonts -->
      <link rel="preconnect" href="https://fonts.bunny.net">
      <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />



      <link rel="stylesheet" href="{{ URL::to('css/styles-admin.css') }}" />

      <!-- Scripts -->
      @vite(['resources/css/app.css', 'resources/js/app.js'])
      @inertiaHead
  </head>
  <body class="bg-dark">
      @inertia
  </body>
  </html>