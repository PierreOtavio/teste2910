@extends('layouts.darkMode')
@section('content_header')
<h1>Informações do usuário</h1>
@stop
@section('content')
     <div class="content">
        <div class="card">
            <div class="card-body">
                <h3>{{ $user->name }}</h3>
                <p><strong>E-mail:</strong> {{ $user->email }}</p>
                <p><strong>CPF:</strong> {{ $user->cpf }}</p>
            </div>
            <div class="card-footer">
                <a href="{{ url('teste/'.$user->id.'/edit') }}" class="btn btn-warning">Editar</a>
                <form action="{{ url('teste/'.$user->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</button>
                </form>
            </div>
        </div>
     </div>
@endsection