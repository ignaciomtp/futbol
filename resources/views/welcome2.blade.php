<!DOCTYPE html>
<html lang="{{ $lang }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Footble.io - {{ $homeTexts['title'] }}</title>
    <meta name="description" content="{{ $homeTexts['description'] }}">


    <link rel="shortcut icon" href="{{{ asset('img/ball.png') }}}">

    <link rel="alternate" hreflang="en" href="https://footble.io">
    <link rel="alternate" hreflang="es" href="https://footble.io/es">
    <link rel="alternate" hreflang="fr" href="https://footble.io/fr">
    <link rel="alternate" hreflang="de" href="https://footble.io/de">
    <link rel="alternate" hreflang="it" href="https://footble.io/it">
    <link rel="alternate" hreflang="x-default" href="https://footble.io">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ URL::to('css/styles-admin.css') }}" />

    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    
</head>
<body class="antialiased bg-dark ">
<div id="back-to-top" class="back-to-top-btn">
  ↑
</div>

    <main class="text-center text-bg-dark pt-4">
        <div class="my-4 p-3">

            <div class="container">
                <div id="logo">
                    <span class="logotext">Footble </span><span>⚽</span>
                </div>

                <div class="mt-3 mb-3" >
                    <p class="hometext">{{ $homeTexts['p1'] }}</p> 
                    <p class="hometext">{{ $homeTexts['p2'] }}</p>
                </div>

                <div class="my-4">
                    <a class="btn bigger btn-success btn-cover" href="{{ route('playgame') }}">{{ $homeTexts['Play'] }}</a>
                </div>
            </div>            
            
        </div>

        <div class="section-darker1 my-4">
            <div class="p-5">
                <h1>{{ $homeTexts['h1'] }}</h1>
                <p class="headline__text">{{ $homeTexts['p3'] }}</p>
            </div>
        </div>


        <div class="my-4 footble-text py-4 px-3">
            <h2>{{ $homeTexts['h2-1'] }}</h2>
            <ol class="ol">
                <li>
                    <div class="width50">
                        <h3>{{ $homeTexts['li1h3'] }}</h3>
                        <span>{{ $homeTexts['li1span'] }}</span>
                        <img src="/img/guess0.png" class="img-fluid img-instructions" alt="{{ $homeTexts['li1h3'] }}">
                    </div>                    
                </li>
                <li>
                    <div class="width50">
                        <h3>{{ $homeTexts['li2h3'] }}</h3>
                        <span>{{ $homeTexts['li2span'] }}</span>
                        <img src="/img/guess1.png" class="img-fluid img-instructions" alt="{{ $homeTexts['li2h3'] }}">
                    </div>
                    
                </li>
                <li>
                    <div class="width50">
                        <h3>{{ $homeTexts['li3h3'] }}</h3>
                        <span>{{ $homeTexts['li3span'] }}</span>
                        <img src="/img/guess3.png" class="img-fluid img-instructions mb-3" alt="{{ $homeTexts['li3h3'] }}">
                        <h4>{{ $homeTexts['li3h4'] }}</h4>
                        
                        <h5>{{ $homeTexts['li3li1h5'] }}</h5>
                        <p>{{ $homeTexts['li3li1p'] }}</p>
                        <div class=" player-data data-row mb-2">
                            <div class="player-data-item attribute-item text-center wrong-guess"><p>{{ $homeTexts['incorrect'] }}</p></div>
                            <div class="player-data-item attribute-item text-center partial-guess"><p>{{ $homeTexts['li3li1t2'] }}</p></div>
                            <div class="player-data-item attribute-item text-center right-guess"><p>{{ $homeTexts['correct'] }}</p></div>
                        </div>
         
                        <h5>{{ $homeTexts['li3li2h5'] }}</h5>
                        <p>{{ $homeTexts['li3li2p'] }}</p>
                        <div class=" player-data data-row mb-2">
                            <div class="player-data-item attribute-item text-center wrong-guess"><p>{{ $homeTexts['li3li2grey'] }}</p></div>
                            <div class="player-data-item attribute-item text-center partial-guess"><p>{{ $homeTexts['li3li2yellow'] }}</p></div>
                            <div class="player-data-item attribute-item text-center right-guess"><p>{{ $homeTexts['li3li2green'] }}</p></div>
                        </div>
           
                        <h5>{{ $homeTexts['li3li3h5'] }}</h5>
                        <p>{{ $homeTexts['li3li3p'] }}</p>
                        <div class=" player-data data-row mb-2">
                            <div class="player-data-item attribute-item text-center wrong-guess"><p>{{ $homeTexts['incorrect'] }}</p></div>
                            <div class="player-data-item attribute-item text-center right-guess"><p>{{ $homeTexts['correct'] }}</p></div>
                           
                        </div>
              
                        <h5>{{ $homeTexts['li3li4h5'] }}</h5>
                        <p>{{ $homeTexts['li3li4p'] }}</p>
                        <div class=" player-data data-row mb-2">
                            <div class="player-data-item attribute-item text-center wrong-guess"><p>{{ $homeTexts['li3li4grey'] }}</p></div>
                            
                            <div class="player-data-item attribute-item text-center right-guess"><p>{{ $homeTexts['li3li4green'] }}</p></div>
                        </div>
            
                        <h5>{{ $homeTexts['li3li5h5'] }}</h5>
                        <p>{{ $homeTexts['li3li5p'] }}</p>
                        <div class=" player-data data-row mb-2">
                            <div class="player-data-item attribute-item text-center wrong-guess"><p>{{ $homeTexts['li3li5grey'] }}</p></div>
                            <div class="player-data-item attribute-item text-center partial-guess"><p>{{ $homeTexts['li3li5yellow'] }}</p></div>
                            <div class="player-data-item attribute-item text-center right-guess"><p>{{ $homeTexts['li3li5green'] }}</p></div>
                        </div> 
                    </div>         
                </li>

                <li>
                    <div class="width50">
                        <h3>{{ $homeTexts['li4h3'] }}</h3>
                        <span>{{ $homeTexts['li4span'] }}</span>
                        <img src="/img/guess4.png" class="img-fluid img-instructions" alt="{{ $homeTexts['li4h3'] }}">
                    </div>
                </li>
            </ol>
        </div>

        <div class="section-clearer1 my-4">
            <div class="p-5">
                <h2>{{ $homeTexts['h2-2'] }}</h2>
                <p class="headline__text">{{ $homeTexts['p4'] }}</p>
                <div class="mt-4">
                    <a class="btn bigger btn-success btn-cover" href="{{ route('playgame') }}">{{ $homeTexts['playnow'] }}</a>
                </div>
            </div>
        </div>

        <div class="my-4">
            <div class="p-5">
                <h2>{{ $homeTexts['h2-3'] }}</h2>
                <p class="headline__text">{{ $homeTexts['p5'] }}</p>
                <div class="width50 headline__text home-textleft">
                    <ol>
                        <li>
                            <div class="why-list-title">
                                {{ $homeTexts['ol2li1t'] }}
                            </div>
                            <p>
                                {{ $homeTexts['ol2li1p'] }}
                            </p>
                        </li>
                        <li>
                            <div class="why-list-title">
                                {{ $homeTexts['ol2li2t'] }}
                            </div>
                            <p>
                                {{ $homeTexts['ol2li2p'] }}
                            </p>
                        </li>
                        <li>
                            <div class="why-list-title">
                                {{ $homeTexts['ol2li3t'] }}
                            </div>
                            <p>
                                {{ $homeTexts['ol2li3p'] }}
                            </p>
                        </li>
                        <li>
                            <div class="why-list-title">
                                {{ $homeTexts['ol2li4t'] }}
                            </div>
                            <p>
                                {{ $homeTexts['ol2li4p'] }}
                            </p>
                        </li>
                    </ol>
                </div>
                
            </div>
        </div>

        <div class="section-clearer1 my-4">
            <div class="p-5">
                <h2>{{ $homeTexts['h2-4'] }}</h2>
                <div class="width50 headline__text ">
                    <p>{{ $homeTexts['p6'] }}</p>
                    <a class="btn-create generator" href="{{ route('create') }}">
                        <i class="bi bi-plus-circle"></i> {{ $homeTexts['create-footble'] }}
                    </a>
                </div>
            </div>
        </div>


        <div class=" my-4">
            <div class="p-5">
                <h3>{{ $homeTexts['h2-5'] }}</h3>
                <div class="width50 headline__text ">
                    <p>{{ $homeTexts['p7'] }}</p>
                    <p>{{ $homeTexts['p81'] }}, <strong>{{ $homeTexts['p82'] }}</strong></p>
                    <p>{{ $homeTexts['p9'] }} ⚽🧠</p>
                </div>
            </div>
        </div>

    </main>

    <div class="footer__bottom">
        <div class="footer__copir">Footble.io © 2025</div>
        <ul class="footer__links">
            <li><a href="mailto:admin@footble.io?subject=Footble">Contact</a></li>
            <li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
        </ul>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
          const backToTopBtn = document.getElementById('back-to-top');

          // Mostrar/ocultar botón al hacer scroll
          window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
              backToTopBtn.classList.add('show');
            } else {
              backToTopBtn.classList.remove('show');
            }
          });

          // Volver arriba al hacer clic
          backToTopBtn.addEventListener('click', () => {
            window.scrollTo({
              top: 0,
              behavior: 'smooth'
            });
          });
        });
    </script>
</body>
</html>