@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="">
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
              <th>Activo</th>
              <th></th>
              <th></th>
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
                <td>{{ $player->debut_season }} - {{ isset($player->last_season) ? $player->last_season : 'Actualidad' }}</td>
                <td>
                    <a href="{{ route('editplayer', ['id' => $player->id]) }}" class="btn btn-primary btn-sm">edit</a>
                </td>
                <td></td>
              </tr>
             @endforeach
          </tbody>
        </table>

        
    </div>

    {{ $players->links('pagination::bootstrap-5') }}
</div>
@endsection
