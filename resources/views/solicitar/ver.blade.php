@extends('layouts.darkMode')


@if (auth()->user()->cargo == 0)
@section('content_header')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<link rel="stylesheet" href="{{ asset('css/custom-dark-mode.css') }}">
<h1>Solicitação do {{$solicitar->user->name}}</h1>

<script>
    setTimeout(() => {
        const successMessage = document.getElementById("message");
        if (successMessage) {
            successMessage.style.transition = "opacity 0.5s ease";
            successMessage.style.opacity = "0";
            setTimeout(() => successMessage.remove(), 500);
        }
    }, 5000);
</script>

@endsection

@section('content')
@if(session('success'))
<div class="alert alert-success" id="message" role="alert">
    {{ session('success') }}
</div>
@endif
<div class="content">
    <div class="card">
        <div class="card-body">
            <h4>Veículo:</h4>
            <ul>
                <li>
                    <p><strong> {{ $solicitar->veiculo->marca }} {{ $solicitar->veiculo->modelo }} - {{ $solicitar->veiculo->placa }}</strong></p>
                </li>
                <li>
                    <p><strong>Quilometragem atual:</strong> {{ $solicitar->veiculo->km_atual }} km </p>
                </li>
                <li>
                    <p><strong>Quilometragem até a revisão:</strong> {{ $solicitar->veiculo->km_revisao }} km </p>
                </li>
                <li>
                    <p><strong>Observações do veículo: </strong>{{ $solicitar->veiculo->observacao }}</p>
                </li>
            </ul>
            <h4>Detalhes da Solicitação</h4>
            <ul>
                <li>
                    <p><strong>Data: </strong> {{ \Carbon\Carbon::parse($solicitar->data_inicial)->format('d/m/y') }} a {{ \Carbon\Carbon::parse($solicitar->data_final)->format('d/m/y') }}</p>
                </li>
                <li>
                    <p><strong>Hora de retirada (A ser aprovada):</strong> {{ $solicitar->hora_inicial }}</p>
                </li>
                <li>
                    <p><strong>Motivo de utilização:</strong> {{ $solicitar->motivo }}</p>
                </li>
                <li>
                    <p><strong>Status: </strong>{{$solicitar->situacao}}</p>
                </li>
            </ul>
        </div>
        <div class="card-footer">
            @if($solicitar->situacao == 'Pendente')
            <form action="{{ route('solicitar.aceitar', $solicitar->id) }}" method="POST" style="display: inline-block;">
                @csrf
                <button type="submit" class="btn btn-success">Aceitar</button>
            </form>

            <form action="{{ route('solicitar.recusar', $solicitar->id) }}" method="POST" style="display: inline-block;">
                @csrf
                <button type="submit" class="btn btn-success" id="danger2">Recusar</button>
            </form>
            @else
            <a class="btn btn-secundary" onclick="history.back()">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
            @endif
        </div>
    </div>
</div>
@endsection
@else
@section('content_header')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<link rel="stylesheet" href="{{ asset('css/custom-dark-mode.css') }}">
<h1>Minha solicitação</h1>
@endsection

@section('content')
<div class="content">
    <div class="card">
        <div class="card-body">
            <h4>Veículo:</h4>
            <ul>
                <li>
                    <p><strong> {{ $solicitar->veiculo->marca }} {{ $solicitar->veiculo->modelo }} - {{ $solicitar->veiculo->placa }}</strong></p>
                </li>
                <li>
                    <p><strong>Quilometragem atual:</strong> {{ $solicitar->veiculo->km_atual }} km </p>
                </li>
                <li>
                    <p><strong>Quilometragem até a revisão:</strong> {{ $solicitar->veiculo->km_revisao }} km </p>
                </li>
                <li>
                    <p><strong>Observações do veículo: </strong>{{ $solicitar->veiculo->observacao }}</p>
                </li>
            </ul>
            <h4>Detalhes da Solicitação</h4>
            <ul>
                <li>
                    <p><strong>Data: </strong> {{ \Carbon\Carbon::parse($solicitar->data_inicial)->format('d/m/y') }} a {{ \Carbon\Carbon::parse($solicitar->data_final)->format('d/m/y') }}</p>
                </li>
                <li>
                    <p><strong>Hora de retirada: </strong> {{ $solicitar->hora_inicial }}</p>
                </li>
                <li>
                    <p><strong>Motivo de utilização:</strong> {{ $solicitar->motivo }}</p>
                </li>
                <li>
                    <p><strong>Status: </strong>{{$solicitar->situacao}}</p>
                </li>
            </ul>
        </div>
        <div class="card-footer">
            @if ($solicitar->situacao == 'Aceito')
            <a class="btn btn-info" href="{{route('solicitar.start', $solicitar->id)}}">Iniciar</a>
            <a class="btn btn-secundary" onclick="history.back()">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
            @else 
            <a class="btn btn-secundary" onclick="history.back()">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
            @endif
        </div>
        @endsection
        @endif