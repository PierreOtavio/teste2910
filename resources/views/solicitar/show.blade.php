@extends('layouts.darkMode')
@section('content_header')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<link rel="stylesheet" href="{{ asset('css/custom-dark-mode.css') }}">
@section('content')
    <h1>Minhas solicitações</h1>


<table class="table table-bordered table-hover">
    <thead>
        <tr>
             <th>Data de retirada:</th>
             <th>Hora de retirada :</th>
             <th>Data de devolução:</th>
        </tr>
             
     </thead>
     <tbody>
         @foreach ($solicitars as $solicitar)
         <tr>
             <td>{{ $solicitar->data_inicial}} </td>
             <td> {{ $solicitar->hora_inicial }}</td>
             <td>{{ $solicitar->data_final}}</td>
             <td>
                {{-- <a href="{{ route('veiculos.show', $solicitar->reserva) }}" class="btn btn-info btn-sm">Ver</a> --}}
             </td>
                 </tr>
                 @endforeach
    </tbody>
    @endsection