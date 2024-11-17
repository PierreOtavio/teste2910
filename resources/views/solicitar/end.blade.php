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
                <h3>Solicitação do {{ $solicitar->veiculo->marca}} {{ $solicitar->veiculo->modelo}} - Finalizando</h3>
                <h4><strong>Devolução prevista:</strong> {{ \Carbon\Carbon::parse($solicitar->data_final)->format('d/m/Y') }}</h4>
                <div class="row mb-3">
                    <p><strong>Km marcado no velocímetro:</strong></p>
                    <div class="col-md-6">
                        <input id="velocimetro_final" type="text" class="form-control @error('velocimetro_final') is-invalid @enderror" name="velocimetro_final" required>
                        @error('velocimetro_final')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
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

            </div>
            <div class="card-footer">
                <a href="{{ route('solicitar.show', $solicitar->veiculo->id) }}" class="btn btn-info">Finalizar</a>
            </div>
    </div>
@endsection