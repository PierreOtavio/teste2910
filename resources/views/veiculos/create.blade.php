@extends('layouts.darkMode')

@section('content_header')
    <h1>Cadastrar Novo Veículo</h1>

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
    
@stop

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"> <h3>Cadastrar Novo Veículo</h3> </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('veiculos.store') }}">
                        @csrf

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row mb-3">
                            <label for="marca" class="col-md-4 col-form-label text-md-end">{{ __('Marca') }}</label>
                            <div class="col-md-6">
                                <input id="marca" type="text" class="form-control @error('marca') is-invalid @enderror" name="marca" value="{{ old('marca') }}">
                                @error('marca')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label for="modelo" class="col-md-4 col-form-label text-md-end">{{ __('Modelo') }}</label>
                            <div class="col-md-6">
                                <input id="modelo" type="text" class="form-control @error('modelo') is-invalid @enderror" name="modelo" value="{{ old('modelo') }}">
                                @error('modelo')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="placa" class="col-md-4 col-form-label text-md-end">{{ __('Placa') }}</label>
                            <div class="col-md-6">
                                <input id="placa" type="text" class="form-control @error('placa') is-invalid @enderror" name="placa" value="{{ old('placa') }}">
                                @error('placa')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="ano" class="col-md-4 col-form-label text-md-end">{{ __('Ano') }}</label>
                            <div class="col-md-6">
                                <input id="ano" type="text" class="form-control @error('ano') is-invalid @enderror" name="ano" value="{{ old('ano') }}">
                                @error('ano')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="capacidade" class="col-md-4 col-form-label text-md-end">{{ __('Capacidade') }}</label>
                            <div class="col-md-6">
                                <input id="capacidade" type="number" placeholder="N° de pessoas" class="form-control @error('capacidade') is-invalid @enderror" name="capacidade" value="{{ old('capacidade') }}">
                                @error('capacidade')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="cor" class="col-md-4 col-form-label text-md-end">{{ __('Cor') }}</label>
                            <div class="col-md-6">
                                <input id="cor" type="text" class="form-control @error('cor') is-invalid @enderror" name="cor" value="{{ old('cor') }}">
                                @error('cor')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="km_atual" class="col-md-4 col-form-label text-md-end">{{ __('Km atual') }}</label>
                            <div class="col-md-6">
                                <input id="km_atual" type="number" class="form-control @error('km_atual') is-invalid @enderror" name="km_atual" value="{{ old('km_atual') }}">
                                @error('km_atual')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror 
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="km_revisao" class="col-md-4 col-form-label text-md-end">{{ __('Intervalo de KM para a revisão') }}</label>
                            <div class="col-md-6">
                                <input id="km_revisao" type="number" class="form-control @error('km_revisao') is-invalid @enderror" name="km_revisao" value="{{ old('km_revisao') }}">
                                @error('km_revisao')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror 
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="chassi" class="col-md-4 col-form-label text-md-end">{{ __('Chassi') }}</label>
                            <div class="col-md-6">
                                <input id="chassi" type="text" class="form-control @error('chassi') is-invalid @enderror" name="chassi" value="{{ old('chassi') }}">
                                @error('chassi')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="observacao" class="col-md-4 col-form-label text-md-end">{{ __('Observação') }}</label>
                            <div class="col-md-6">
                                <input id="observacao" type="text" class="form-control @error('observacao') is-invalid @enderror" name="observacao" value="{{ old('observacao') }}">
                                @error('observacao')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="funcionamento" class="col-md-4 col-form-label text-md-end">Disponibilidade do Veículo</label>
                            <div class="col-md-6">
                                <div class="custom-select-wrapper"> 
                                    <select name="funcionamento" id="funcionamento" class="custom-select">
                                        <option value="0" {{ old('funcionamento') == 0 ? 'selected' : '' }}>Disponível</option>
                                        <option value="1" {{ old('funcionamento') == 1 ? 'selected' : '' }}>Indisponível</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-custom">
                                    {{ __('Salvar') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection