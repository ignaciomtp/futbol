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
                <div class="col-md-8">
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
                      <input type="text" class="form-control" id="first_name" name="first_name" placeholder="" value="{{ $player->first_name }}" required="">
                      <div class="invalid-feedback">
                        Valid first_name is required.
                      </div>
                    </div>

                    <div class="col-sm-4">
                      <label for="surnames" class="form-label">Apellidos</label>
                      <input type="text" class="form-control" id="surnames" name="surnames" placeholder="" value="{{ $player->surnames }}" required="">
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
                      <input type="number" class="form-control" id="debut_season" name="debut_season" placeholder="" value="{{ $player->debut_season }}" required="">
                      <div class="invalid-feedback">
                        Valid debut_season is required.
                      </div>
                    </div>

                    <div class="col-sm-3">
                      <label for="last_season" class="form-label">Retirada:</label>
                      <input type="number" class="form-control" id="last_season" name="last_season" placeholder="" value="{{ $player->last_season }}" required="">
                      <div class="invalid-feedback">
                        Valid last_season is required.
                      </div>
                    </div>
                     
                  </div>

                  <div class="row g-3">
                    <div class="col-sm-9">
                      <label for="photo" class="form-label">Foto:</label>
                      <input type="file" class="form-control" id="photo" name="photo" >

                      <label for="idd" class="form-label mt-2">Id:</label>
                      <input type="text" class="form-control" id="id" name="id" value="{{ $player->id }}" readonly>
                    </div>
                    <div class="col-sm-3">
                      @if(isset($player->photo) && $player->photo)
                        <img src="../../img/players/{{ $player->photo }}" class="thumbnail" alt="{{ $player->name }}">
                      @else
                        <img src="../../img/players/user.jpg" class="thumbnail" alt="{{ $player->name }}">
                      @endif
                    </div>
                  </div>

                  <div class="row g-3 mt-4">
                      <button class="btn btn-primary btn-lg btn-block" type="submit">Guardar</button>
                  </div>
                      
                </div> 

                <div class="col-md-4">
                  <h2>Clubs</h2>
                  <div class="mt-2">
                    <button class="btn btn-success btn-sm btn-block" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal">Añadir</button>

                    <div class="clubes p-2 mt-3" >
                      <ul id="clubs-list">
                        @foreach($clubs as $club)
                        <li>
                          @if(in_array($club->id, $playerClubs))
                           <input type="checkbox" class="form-check-input mr-4 ml-4" name="clubs[]" id="club{{ $club->id }}" checked>
                          @else
                           <input type="checkbox" class="form-check-input mr-4 ml-4" name="clubs[]" id="club{{ $club->id }}" >
                          @endif
                        <label class="form-check-label mr-4 ml-4" for="exampleCheck1">{{ $club->name }}</label>
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


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
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

  </script>
</div>

@endsection


