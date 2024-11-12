@extends('layouts.darkMode')
@section('content_header')
<h1>Permissões do Usuário</h1>
@stop

@section('content')
<div class="content">
    <div class="card">
        <div class="card-body">
            <script>
            <h3>{{ $user->name }} <h4>Cargo:  @if ($user->cargo == 0)
                                            Responsável pelo setor
                                        @endif
                                        @if ($user->cargo == 1)
                                            Colaborador Comum
                                        @endif
                                        @if ($user->cargo == 2)
                                            Colaborador tercerizado
                                        @endif
                                    </h4>
                </h3>
                </script>
            <p><strong>Permissões:</strong></p>
            <ul>
                <li>@if ($user->cargo == 0)
                Ver, Criar, Editar e Excluir Usuários
                @endif
                @if ($user->cargo != 0)
                Ver usuários
                @endif </li>
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