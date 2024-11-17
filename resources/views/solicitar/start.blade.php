@extends('layouts.darkMode')

@section('content_header')
    <h1>Solicitação Do dia {{$solicitar->data_inicial}} em progresso</h1>
@stop

@section('content')
    <div class="content">
        <div class="card">
            <div class="card-body">
                <h3><strong>Data da retirada:</strong> {{ $solicitar->data_inicial }}</h3>
                <h3><strong>Hora de retirada:</strong> {{ $solicitar->hora_inicial }}</h3>
                <p><strong>Placa do veículo: {{ $solicitar->veiculo->placa }}</strong></p>
                <p><strong>Marca:</strong> {{ $solicitar->veiculo->marca }}</p>
                <p><strong>Modelo:</strong> {{ $solicitar->veiculo->modelo }}</p>
                <p><strong>Data de devolução:</strong> {{ $solicitar->data_final }}</p>
                <p><strong>Quantos KM faltam para a revisão:</strong> {{ $solicitar->veiculo->km_revisao }}</p>

                <a href="{{ route('solicitar.index', $solicitar->veiculo->id) }}" class="btn btn-info">Finalizar utilização do veículo</a>
        </div>
    </div>
@endsection