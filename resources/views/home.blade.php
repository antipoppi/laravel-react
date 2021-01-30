@extends('layouts.master')

@section('content')
<div class="container">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">Home</div>

            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <p>{{ Auth::user()->name }}, welcome! </p>
            </div>
        </div>
    </div>
</div>

@endsection