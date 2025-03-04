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
@stop

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"> <h2>Fazer uma solicitação: {{ $veiculo->marca }} {{ $veiculo->modelo }} - {{ $veiculo->cor }} </h2>
                    <h4>Placa: {{ $veiculo->placa }} </h4> </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('solicitar.store') }}">
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

                        <input type="hidden" name="veiculo_id" value="{{ $veiculo->id }}">
                        
                        <div class="row mb-3">
                            <label for="data_inicial" class="col-md-4 col-form-label text-md-end">{{ __('Data de retirada:') }}</label>
                            <div class="col-md-6">
                                <input id="data_inicial" type="date" class="form-control @error('data_inicial') is-invalid @enderror" name="data_inicial">
                                @error('data_inicial')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="hora_inicial" class="col-md-4 col-form-label text-md-end">{{ __('Hora de retirada:') }}</label>
                            <div class="col-md-6">
                                <input id="hora_inicial" type="time" class="form-control @error('hora_inicial') is-invalid @enderror" name="hora_inicial">
                                @error('hora_inicial')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        
                        <div class="row mb-3">
                            <label for="data_final" class="col-md-4 col-form-label text-md-end">{{ __('Data de devolução:') }}</label>
                            <div class="col-md-6">
                                <input id="data_final" type="date" class="form-control @error('data_final') is-invalid @enderror" name="data_final">
                                @error('data_final')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        

                        <div class="row mb-3">
                            <label for="motivo" class="col-md-4 col-form-label text-md-end">{{ __('Motivo de utilização:') }}</label>
                            <div class="col-md-6">
                                <input id="motivo" type="text" class="form-control @error('motivo') is-invalid @enderror" name="motivo">
                                @error('motivo')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
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