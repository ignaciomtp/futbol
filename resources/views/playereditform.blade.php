@extends('layouts.app')

@section('content')
<div class="container">
    <div class="justify-content-center">
            <h1>Player Form</h1>

            <form enctype="multipart/form-data" action="{{ route('updateplayer') }}" method="post">
              @csrf

              @if ($errors->any())
                  <div class="alert alert-danger">
                      <ul>
                          @foreach ($errors->all() as $error)
                              <li>{{ $error }}</li>
                          @endforeach
                      </ul>
                  </div>
              @endif

              <div class="row">
                <div class="col-md-6">
                  <div class="row g-3">
                    <div class="col-sm-4">
                      <label for="name" class="form-label">Nombre fut:</label>
                      <input type="text" class="form-control" id="name" name="name" placeholder="" value="{{ $player->name }}" required="">
                      <div class="invalid-feedback">
                        Valid name is required.
                      </div>
                    </div>

                    <div class="col-sm-4">
                      <label for="first_name" class="form-label">Nombre</label>
                      <input type="text" class="form-control" id="first_name" name="first_name" placeholder="" value="{{ $player->first_name }}" >
                      <div class="invalid-feedback">
                        Valid first_name is required.
                      </div>
                    </div>

                    <div class="col-sm-4">
                      <label for="surnames" class="form-label">Apellidos</label>
                      <input type="text" class="form-control" id="surnames" name="surnames" placeholder="" value="{{ $player->surnames }}" >
                      <div class="invalid-feedback">
                        Valid surnames is required.
                      </div>
                    </div>                  
                  </div>

                  <div class="row g-3">
                    <div class="col-sm-3">
                      <label for="birth_country" class="form-label">País nacimiento:</label>
                      <input type="text" class="form-control" id="birth_country" name="birth_country" placeholder="" value="{{ $player->birth_country }}" required="">
                      <div class="invalid-feedback">
                        Valid birth_country is required.
                      </div>
                    </div>

                    <div class="col-sm-3">
                      <label for="country" class="form-label">País:</label>
                      <input type="text" class="form-control" id="country" name="country" placeholder="" value="{{ $player->country }}" required="">
                      <div class="invalid-feedback">
                        Valid country is required.
                      </div>
                    </div>

                    <div class="col-sm-3">
                      <label for="debut_season" class="form-label">Debut:</label>
                      <input type="number" class="form-control" id="debut_season" name="debut_season" placeholder="" value="{{ $player->debut_season }}" >
                      <div class="invalid-feedback">
                        Valid debut_season is required.
                      </div>
                    </div>

                    <div class="col-sm-3">
                      <label for="last_season" class="form-label">Retirada:</label>
                      <input type="number" class="form-control" id="last_season" name="last_season" placeholder="" value="{{ $player->last_season }}" >
                      <div class="invalid-feedback">
                        Valid last_season is required.
                      </div>
                    </div>
                     
                  </div>

                  <div class="row g-3">
                    <div class="col-sm-3">
                      <label for="position" class="form-label">Posición:</label>
                      <select class="form-select" id="position" name="position">
                        <option value=""></option>
                        @foreach($positions as $position)
                          @if($player->position === $position)
                            <option value="{{ $position }}" selected>{{ $position }}</option>
                          @else
                            <option value="{{ $position }}">{{ $position }}</option>
                          @endif
                        @endforeach
                      </select>
                    </div>
                    <div class="col-sm-6">
                      <label for="photo" class="form-label">Foto:</label>
                      <input type="file" class="form-control" id="photo" name="photo" >

                      <label for="idd" class="form-label mt-2">Id:</label>
                      <input type="text" class="form-control" id="id" name="id" value="{{ $player->id }}" readonly>
                    </div>
                    <div class="col-sm-3">
                      @if(isset($player->photo) && $player->photo)
                        <img src="../../../img/players/{{ $player->photo }}" class="thumbnail" alt="{{ $player->name }}">
                      @else
                        <img src="../../../img/players/user.jpg" class="thumbnail" alt="{{ $player->name }}">
                      @endif
                    </div>
                  </div>

                  <div class="row g-3 mt-4">
                      <button class="btn btn-primary btn-lg btn-block" type="submit">Guardar</button>
                  </div>
                      
                </div> 

                <div class="col-md-3">
                  <h2>Clubs</h2>
                  <div class="mt-2">
                    <button class="btn btn-success btn-sm btn-block" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal">Añadir</button>

                    <div class="clubes p-2 mt-3" >
                      <ul id="clubs-list">
                        @foreach($clubs as $club)
                        <li>
                          @if(in_array($club->id, $playerClubs))
                           <input type="checkbox" class="form-check-input mr-4 ml-4" name="clubs[]" id="club{{ $club->id }}" value="{{ $club->id }}" checked>
                          @else
                           <input type="checkbox" class="form-check-input mr-4 ml-4" name="clubs[]" id="club{{ $club->id }}" value="{{ $club->id }}">
                          @endif
                        <label class="form-check-label mr-4 ml-4" for="club{{ $club->id }}">{{ $club->name }}</label>
                        </li>
                        @endforeach
                      </ul>
                      
                    </div>
                  </div>
                </div>    

                <div class="col-md-3">
                  <h2>Títulos</h2>
                  <div class="mt-2">
                    <button class="btn btn-success btn-sm btn-block" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal2">Añadir</button>

                    <button class="btn btn-sm btn-warning floatright" type="button" onclick="updateTitles()">Guardar</button>

                    
                    <div class="alert alert-success oculto" id="updated-titles">
                        Títulos actualizados
                    </div>
                    

                    <div class="clubes p-2 mt-3" >
                      <ul id="titles-list">
                        @foreach($titles as $title)
                        <li>
                        <label class="form-check-label mr-4 ml-4 smallfont" for="title{{ $title->id }}">{{ $title->name }}</label>
                        @if(array_key_exists($title->id, $playerTitles))
                            <input type="number" class="numtitles" id="num-titles-{{ $title->id }}" data-title-id="{{ $title->id }}" value="{{ $playerTitles[$title->id] }}">
                        @else
                            <input type="number" class="numtitles" id="num-titles-{{ $title->id }}" data-title-id="{{ $title->id }}" value="0">
                        @endif
                        </li>
                        @endforeach
                      </ul>
                    </div>
                  </div>
                </div>      
    
              </div>

            </form>
                
    </div>
</div>


<!-- Modal Clubes Form -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Añadir Club</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="clubForm" enctype="multipart/form-data">
          <label for="club" class="form-label">Club:</label>
          <input type="text" class="form-control" id="club" name="name" placeholder="" required>

          <label for="badge" class="form-label">Escudo:</label>
          <input type="file" class="form-control" id="badge" name="badge" >
          
        </form>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="createClub()">Save changes</button>
      </div>
    </div>
  </div>

</div>


<!-- Modal Títulos -->
<div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Añadir Título</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="titleForm" >
          <label for="name" class="form-label">Título:</label>
          <input type="text" class="form-control" id="name" name="name" placeholder="" required>

          <label for="number" class="form-label">Veces que lo ganó:</label>
          <input type="text" class="form-control" id="number" name="number" >
          
        </form>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="createTitle()">Save changes</button>
      </div>
    </div>
  </div>

</div>




<script type="text/javascript">

  function createClub() {
    //console.log(window.location.origin);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    let playerId = $('#id').val();

    let url = window.location.origin + '/player/club/' + playerId;

    let clubForm = $('#clubForm')[0];

    var formData = new FormData(clubForm); 

    $.ajax({
      url: url,
      type : 'POST',
      data: formData,
      contentType: false, // Importante: no establecer el tipo de contenido
      processData: false, // Importante: no procesar los datos
      success: function(result){
        $('#clubs-list').append(result);
        clubForm.reset();
      },
      error: function(error) {
          // Manejar el error
          console.error(error);
      }
    });   
  }  


  function createTitle() {
    //console.log(window.location.origin);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    let playerId = $('#id').val();

    let url = window.location.origin + '/player/title/' + playerId;

    let titleForm = $('#titleForm')[0];

    var formData = new FormData(titleForm); 

    $.ajax({
      url: url,
      type : 'POST',
      data: formData,
      contentType: false, // Importante: no establecer el tipo de contenido
      processData: false, // Importante: no procesar los datos
      success: function(result){
        $('#titles-list').append(result);
        titleForm.reset();
      },
      error: function(error) {
          // Manejar el error
          console.error(error);
      }
    });   
  }  


  function updateTitles() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    let playerId = $('#id').val();

    let url = window.location.origin + '/player/titlesupdate/' + playerId;

    let titleInputs = $('.numtitles').get();

    let titles = titleInputs
      .filter(elem => $(elem).val() !== '0')  // Filtra elementos donde titlesWon no sea '0'
      .map(elem => ({
        id: $(elem).attr('data-title-id'),
        number: $(elem).val()
      }));

    console.log(titles);

    $.ajax({
      url: url,
      type : 'POST',
      data: {titles: titles},
      success: function(result){
        console.log(result);
        $('#updated-titles').removeClass('oculto');
      },
      error: function(error) {
          // Manejar el error
          console.error(error);
      }
    });   

  }

  </script>

@endsection


