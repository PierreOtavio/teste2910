@extends('layouts.darkMode')
@section('content_header')
<h1>Permissões do Usuário</h1>
@stop

@section('content')
<div class="content">
    <div class="card">
        <div class="card-body">
            <h3>{{ $user->name }}</h3>
            <p><strong>Permissões:</strong></p>
            <ul>
                @foreach($permissions as $permission)
                <li>{{ $permission->name }}</li> <!-- Exibe o nome da permissão -->
                @endforeach
            </ul>
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