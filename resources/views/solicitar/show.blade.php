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
                <th>Veículo:</th>
                <th>Retirada :</th>
                <th>Mais informações:</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($solicitars as $solicitar)
            <tr>
                <!-- Acessando as informações do veículo através do relacionamento -->
                <td>{{ $solicitar->veiculo->marca }} {{ $solicitar->veiculo->modelo }} - {{ $solicitar->veiculo->placa }}</td>
                <td>Data: {{ \Carbon\Carbon::parse($solicitar->data_inicial)->format('d/m/Y') }} a {{ \Carbon\Carbon::parse($solicitar->data_final)->format('d/m/Y') }} <br> Hora: {{ $solicitar->hora_inicial }}</td>
                <td>
                    <a href="{{ route('solicitar.ver', $solicitar->veiculo->id) }}" class="btn btn-info btn-sm">Ver</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endsection