@extends('layouts.darkMode')

@section('content_header')
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
                <h3>Solicitação do {{ $solicitar->veiculo->marca}} {{ $solicitar->veiculo->modelo}} - Em progresso</h3>
                <h4><strong>Retirada prevista:</strong> {{ \Carbon\Carbon::parse($solicitar->data_inicial)->format('d/m/Y') }} às {{ \Carbon\Carbon::parse($solicitar->hora_inicial)->format('H\hi') }}</h4>
                <form action="{{ route('solicitar.prosseguir', $solicitar->id) }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <p><strong>Placa do veículo:</strong></p>
                        <div class="col-md-6">
                            <input id="placa_confirmar" type="text" class="form-control @error('placa_confirmar') is-invalid @enderror" name="placa_confirmar" required>
                            @error('placa_confirmar')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror   
                        </div>
                    </div>
                    <div class="row mb-3">
                        <p><strong>Km marcado no velocímetro:</strong></p>
                        <div class="col-md-6">
                            <input id="velocimetro_inicio" type="text" class="form-control @error('velocimetro_inicio') is-invalid @enderror" name="velocimetro_inicio" required>
                            @error('velocimetro_inicio')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    
                </div>
                
                <div class="card-footer">
                    <button type="submit" class="btn btn-info">Prosseguir</button>
                </div>
            </form>
            </div>
@endsection