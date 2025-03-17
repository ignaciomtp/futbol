@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
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
                  <input type="number" class="form-control" id="debut_season" name="debut_season" placeholder="" value="" required="">
                  <div class="invalid-feedback">
                    Valid debut_season is required.
                  </div>
                </div>

                <div class="col-sm-3">
                  <label for="last_season" class="form-label">Retirada:</label>
                  <input type="number" class="form-control" id="last_season" name="last_season" placeholder="" value="" required="">
                  <div class="invalid-feedback">
                    Valid last_season is required.
                  </div>
                </div>
                 
              </div>

              <div class="row g-3">
                  <label for="photo" class="form-label">Foto:</label>
                  <input type="file" class="form-control" id="photo" name="photo" >
              </div>

              <div class="row g-3 mt-4">
                  <button class="btn btn-primary btn-lg btn-block" type="submit">Guardar</button>
              </div>

              
            </form>
        </div>
    </div>
</div>
@endsection
