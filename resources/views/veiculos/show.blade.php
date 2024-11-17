@extends('layouts.darkMode')


@section('content_header')
    <h1>Informações do veículo</h1>
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
@stop

@section('content')
<div class="content">
    <div class="card">
        <div class="card-body">
            <div class="info-container">
                <div class="vehicle-info">
                    <h3>{{ $veiculo->placa }}</h3>
                    <p><strong>Marca:</strong> {{ $veiculo->marca }}</p>
                    <p><strong>Modelo:</strong> {{ $veiculo->modelo }}</p>
                    <p><strong>Ano:</strong> {{ $veiculo->ano }}</p>
                    <p><strong>Placa:</strong> {{ $veiculo->placa }}</p>
                    <p><strong>Cor:</strong> {{ $veiculo->cor }}</p>
                    <p><strong>Capacidade:</strong> {{ $veiculo->capacidade }}</p>
                    <p><strong>Chassi:</strong> {{ $veiculo->chassi }}</p>
                    <p><strong>Km atual:</strong> {{ $veiculo->km_atual }}</p>
                    <p><strong>Quantos Km faltam para a revisão:</strong> {{ $veiculo->km_revisao }}</p>
                    <p><strong>Funcionamento:</strong> 
                        @if ($veiculo->funcionamento == 0)
                            Disponível
                        @elseif ($veiculo->funcionamento == 1)
                            Indisponível
                        @endif
                    </p>
                </div>
                <div class="qr-code-container">
                    <p><strong>QR Code:</strong></p>
                    <img src="{{ asset('qrcodes/' . $veiculo->qr_code) }}" alt="QR Code do veículo">
                </div>
            </div>
        </div>
        <div class="card-footer">
            @if (auth()->user()->cargo == 0)
                <a href="{{ route('solicitar.create', $veiculo->id) }}" class="btn btn-info">Solicitar Veículo</a>
                <a href="{{ url('veiculos/'.$veiculo->id.'/edit') }}" class="btn btn-warning">Editar</a>
                <form action="{{ url('veiculos/'.$veiculo->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</button>
                </form>
            @else
                <a href="{{ route('solicitar.create', $veiculo->id) }}" class="btn btn-info">Solicitar Veículo</a>
            @endif
        </div>
    </div>
</div>

@endsection