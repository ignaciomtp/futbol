@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="row">
            <div class="col-6">
                <form class="d-flex" method="POST" action="{{ route('multipleresult') }}">
                  @csrf
                  <select class="form-select" name="club">
                      @foreach($clubs as $club)
                        <option value="{{ $club->id }}">{{ $club->name }}</option>
                      @endforeach
                  </select>
                  <button class="btn btn-outline-success" type="submit">Search Players</button>
                </form>
            </div>
        </div>
    </div>


</div>
@endsection