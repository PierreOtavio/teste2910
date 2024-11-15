@extends('layouts.darkMode')
@section('content_header')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<link rel="stylesheet" href="{{ asset('css/custom-dark-mode.css') }}">

    <h1>Solicitação para o dia {{\Carbon\Carbon::parse($solicitar->data_inicial)->format('d/m/Y')}} (A ser aprovado)</h1>
@endsection

@section('content')
    <div class="content">
        <div class="card">
            <div class="card-body">
                <h3>Placa do veículo:   {{ $solicitar->veiculo->placa }}</h3>
                <p><strong>Marca:</strong> {{ $solicitar->veiculo->marca }}</p>
                <p><strong>Modelo:</strong> {{ $solicitar->veiculo->modelo }}</p>
                <p><strong>Motivo de utilização:</strong> {{ $solicitar->motivo }}</p>
                <p><strong>Hora de retirada (A ser aprovada):</strong> {{ $solicitar->hora_inicial }}</p>
                <p><strong>Data de devolução (A ser aprovada):</strong> {{ \Carbon\Carbon::parse($solicitar->data_final)->format('d/m/y') }}</p>
                <p><strong>Observação/ões do veículo:</strong> {{ $solicitar->veiculo->observacao }}</p>
                <p><strong>Quantos KM faltam para a revisão:</strong> {{ $solicitar->veiculo->km_revisao }}</p>
            </div>
            <div class="card-footer">
                <a class="btn btn-info" href="#">Iniciar</a>
                    <form action="{{ url('veiculos/'.$solicitar->veiculo->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</button>
                    </form>
            </div>
        </div>
    </div>
@endsection
