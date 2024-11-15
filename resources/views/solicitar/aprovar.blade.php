@extends('layouts.darkMode')
@section('content_header')
    <h1>Situação da Solicitação</h1>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <div class="card-body">
                    <form method="POST" action="{{ route('solicitar.update', $solicitacao->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Situação</label>
                            <select name="situacao" class="form-select" required>
                                <option value="">Selecione uma opção</option>
                                <option value="aprovado">Aprovar</option>
                                <option value="reprovado">Reprovar</option>
                            </select>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                Confirmar
                            </button>
                            <a href="{{ url()->previous() }}" class="btn btn-secondary">
                                Voltar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
