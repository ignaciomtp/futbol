@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <h1>Player Form</h1>

        <form enctype="multipart/form-data" action="{{ route('newplayer') }}" method="post">
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

          <div class="row ">
            <div class="col-md-8">
              <div class="row g-3">
                <div class="col-sm-4">
                  <label for="name" class="form-label">Nombre fut:</label>
                  <input type="text" class="form-control" id="name" name="name" placeholder="" value="" required="">
                  <div class="invalid-feedback">
                    Valid name is required.
                  </div>
                </div>

                <div class="col-sm-4">
                  <label for="first_name" class="form-label">Nombre</label>
                  <input type="text" class="form-control" id="first_name" name="first_name" placeholder="" value="" required="">
                  <div class="invalid-feedback">
                    Valid first_name is required.
                  </div>
                </div>

                <div class="col-sm-4">
                  <label for="surnames" class="form-label">Apellidos</label>
                  <input type="text" class="form-control" id="surnames" name="surnames" placeholder="" value="" required="">
                  <div class="invalid-feedback">
                    Valid surnames is required.
                  </div>
                </div>   
              </div>

              <div class="row g-3">
                <div class="col-sm-3">
                  <label for="birth_country" class="form-label">País nacimiento:</label>
                  <input type="text" class="form-control" id="birth_country" name="birth_country" placeholder="" value="" required="">
                  <div class="invalid-feedback">
                    Valid birth_country is required.
                  </div>
                </div>

                <div class="col-sm-3">
                  <label for="country" class="form-label">País:</label>
                  <input type="text" class="form-control" id="country" name="country" placeholder="" value="" required="">
                  <div class="invalid-feedback">
                    Valid country is required.
                  </div>
                </div>

                <div class="col-sm-3">
                  <label for="debut_season" class="form-label">Debut:</label>
                  <input type="number" class="form-control" id="debut_season" name="debut_season" placeholder="" value="">
                  <div class="invalid-feedback">
                    Valid debut_season is required.
                  </div>
                </div>

                <div class="col-sm-3">
                  <label for="last_season" class="form-label">Retirada:</label>
                  <input type="number" class="form-control" id="last_season" name="last_season" placeholder="" value="">
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
                          <option value="{{ $position }}">{{ $position }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-sm-6">
                  <label for="photo" class="form-label">Foto:</label>
                  <input type="file" class="form-control" id="photo" name="photo" >
                </div>
              </div>

               <div class="row g-3 mt-4">
                    <button class="btn btn-primary btn-lg btn-block" type="submit">Guardar</button>
                </div>
            </div>

            <div class="col-md-4">
              <h2>Clubs</h2>
                  <div class="mt-2">

                    <div class="clubes p-2 mt-3" >
                      <ul id="clubs-list">
                        @foreach($clubs as $club)
                        <li><input type="checkbox" class="form-check-input mr-4 ml-4" name="clubs[]" id="club{{ $club->id }}" value="{{ $club->id }}"><label class="form-check-label mr-4 ml-4" for="exampleCheck1">{{ $club->name }}</label></li>
                        @endforeach
                      </ul>
                  </div>
            </div>

              
          </div>


          
        </form>
        
    </div>
</div>


@endsection
