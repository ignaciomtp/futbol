@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1>Players</h1>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            <tr>
              <th>Id</th>
              <th>Nombre</th>
              <th>Apelliddos</th>
              <th>Conocido</th>
              <th>País</th>
              <th>Equipos</th>
              <th>Activo</th>
            </tr>
          </thead>
          <tbody>
             @foreach($players as $player)  
              <tr>
                <td>{{ $player->id }}</td>
                <td>{{ $player->first_name }}</td>
                <td>{{ $player->surnames }}</td>
                <td>{{ $player->name }}</td>
                <td>{{ $player->birth_country }}</td>
                <td></td>
                <td>{{ $player->debut_season }} - {{ $player->last_season }}</td>
              </tr>
             @endforeach
          </tbody>
        </table>
    </div>
</div>
@endsection
