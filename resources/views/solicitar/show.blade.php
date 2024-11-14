@extends('layouts.darkMode')
@section('content_header')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<link rel="stylesheet" href="{{ asset('css/custom-dark-mode.css') }}">

@section('content')
    <div class="content">
        <div class="card">
            <div class="card-body">
                <h3>{{ $veiculo->placa }}</h3>
                <p><strong>Marca:</strong> {{ $veiculo->marca }}</p>
                <p><strong>Modelo:</strong> {{ $veiculo->modelo }}</p>
                <p><strong>Ano:</strong> {{ $veiculo->ano }}</p>
                <p><strong>Placa:</strong> {{ $veiculo->placa }}</p>
                <p><strong>Cor:</strong> {{ $veiculo->cor }}</p>
                <p><strong>Capacidade:</strong> {{ $veiculo->capacidade }}</p>
                <p><strong>Chassi:</strong> {{ $veiculo->chassi }}</p>
                <p><strong>Km atual:</strong> {{ $veiculo->km_atual }}</p>
            </div>
@stop

@section('content')