@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">CSV: Lisäyslomake</div>
                <div class="panel-body">

@if (session('info_msg'))
    <div class="alert alert-info">
        {{ session('info_msg') }}
    </div>
@endif


@if(Session::has('virheet'))
<div class="alert alert-danger">
<h3>CSV-tiedoston lukemisessa virhe</h3>
    <b>Virhe CSV-tiedoston rivillä {{ Session::get('rivinro') }}</b>
    <pre> {{ Session::get('rivi') }}</pre>
<b>Virheelliset kentät:</b>
<ul>
@foreach (Session::get('virheet') as $virhe)
    <li>{!! $virhe !!}</li>
@endforeach
</ul>
</div>
@endif

<b>CSV-tiedoston formaatti on (ei tule otsikkoriviä)</b>
<pre>
nro;palautuspvm;kuvaus
3;2018-10-21;Harjoitukset 3
</pre>

        <h2 id="uploadhead">Lisää dataa CSV-tiedostosta</h2>
        <hr>
        <form method="POST" enctype="multipart/form-data" action="{{ url('/csvsaved')}}">
        @csrf
        <br>CSV-tiedosto:<br>
        <input name="csvfile" type="file">
            <br/>
            <input type="submit" name="nappi" value="Lataa CSV-tiedosto" class="btn float-left">
        </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection