@extends('layouts.darkMode')
@section('content_header')
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
    @if(session('success'))
        <div class="alert alert-success" id="message" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if(session('danger'))
        <div class="alert alert-danger" id="message" role="alert">
            {{ session('danger') }}
        </div>
    @endif
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Editar os dados do Veículo:') }} {{ $veiculo->placa }}</div>

                <div class="card-body">
                    <form action="{{ route('veiculos.update', $veiculo->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <label for="modelo" class="col-md-4 col-form-label text-md-end">{{ __('Modelo') }}</label>
                            <div class="col-md-6">
                                <input id="modelo" type="text" class="form-control @error('modelo') is-invalid @enderror" name="modelo" value="{{ old('modelo', $veiculo->modelo) }}" required autofocus>
                                @error('modelo')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="marca" class="col-md-4 col-form-label text-md-end">{{ __('Marca') }}</label>
                            <div class="col-md-6">
                                <input id="marca" type="text" class="form-control @error('marca') is-invalid @enderror" name="marca" value="{{ old('marca', $veiculo->marca) }}" required>
                                @error('marca')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="placa" class="col-md-4 col-form-label text-md-end">{{ __('Placa') }}</label>
                            <div class="col-md-6">
                                <input id="placa" type="text" class="form-control @error('placa') is-invalid @enderror" name="placa" value="{{ old('placa', $veiculo->placa) }}" required>
                                @error('placa')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="chassi" class="col-md-4 col-form-label text-md-end">{{ __('Chassi') }}</label>
                            <div class="col-md-6">
                                <input id="chassi" type="text" class="form-control @error('chassi') is-invalid @enderror" name="chassi" value="{{ old('chassi', $veiculo->chassi) }}" required>
                                @error('chassi')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="ano" class="col-md-4 col-form-label text-md-end">{{ __('Ano') }}</label>
                            <div class="col-md-6">
                                <input id="ano" type="number" class="form-control @error('ano') is-invalid @enderror" name="ano" value="{{ old('ano', $veiculo->ano) }}" required>
                                @error('ano')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="cor" class="col-md-4 col-form-label text-md-end">{{ __('Cor') }}</label>
                            <div class="col-md-6">
                                <input id="cor" type="text" class="form-control @error('cor') is-invalid @enderror" name="cor" value="{{ old('cor', $veiculo->cor) }}" required>
                                @error('cor')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="capacidade" class="col-md-4 col-form-label text-md-end">{{ __('Capacidade') }}</label>
                            <div class="col-md-6">
                                <input id="capacidade" type="number" class="form-control @error('capacidade') is-invalid @enderror" name="capacidade" value="{{ old('capacidade', $veiculo->capacidade) }}" required>
                                @error('capacidade')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="km_atual" class="col-md-4 col-form-label text-md-end">{{ __('Km atual') }}</label>
                            <div class="col-md-6">
                                <input id="km_atual" type="number" class="form-control @error('km_atual') is-invalid @enderror" name="km_atual" value="{{ old('km_atual', $veiculo->km_atual) }}" required>
                                @error('km_atual')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="km_revisao" class="col-md-4 col-form-label text-md-end">{{ __('Intervalo de KM para a revisão') }}</label>
                            <div class="col-md-6">
                                <input id="km_revisao" type="number" class="form-control @error('km_revisao') is-invalid @enderror" name="km_revisao" value="{{ old('km_revisao', $veiculo->km_revisao) }}">
                                @error('km_revisao')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror 
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="observacao" class="col-md-4 col-form-label text-md-end">{{ __('Observação') }}</label>
                            <div class="col-md-6">
                                <input id="observacao" type="text" class="form-control @error('observacao') is-invalid @enderror" name="observacao" value="{{ old('observacao', $veiculo->observacao) }}">
                                @error('observacao')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-custom">Salvar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection