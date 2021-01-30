@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Kaikki opiskelijaresurssit</div>
@if (session('info'))
    <div class="alert alert-info">
        {{ session('info') }}
    </div>
@endif

                <div class="panel-body">
<table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">id</th>
      <th scope="col">name</th>
      <th scope="col">email</th>
      <th scope="col">luotu</th>
    </tr>
  </thead>
  <tbody>

@foreach ($users as $opiskelija)

    <tr class="bg-success">
      <th scope="row">{{ $opiskelija->id }}</th>
      <td>{{ $opiskelija->name }}</td>
      <td>{{ $opiskelija->email }}</td>
    <td>{{ $opiskelija->created_at }}</td>
      <td><a href="scores?id={{ $opiskelija->id }}"> Harkkapalautukset </a></td>
    </tr>

@endforeach

  </tbody>
</table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection