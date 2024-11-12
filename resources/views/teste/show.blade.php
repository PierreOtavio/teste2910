@extends('layouts.darkMode')
@section('content_header')
<h1>Informações do usuário</h1>
@stop
@section('content')
     <div class="content">
        <div class="card">
            <div class="card-body"><h3>{{ $user->name }}</h3>
                <p><strong>E-mail:</strong> {{ $user->email }}</p>
                <p><strong>CPF:</strong> {{ $user->cpf }}</p>
                <p><strong>Cargo:</strong> 
                    @if ($user->cargo == 0)
                        Responsável pelo setor
                    @endif
                    @if ($user->cargo == 1)
                        Colaborador Comum
                    @endif
                    @if ($user->cargo == 2)
                        Colaborador tercerizado
                    @endif
                </p>  
            </div>
            <div class="card-footer">
                @if (auth()->user()->cargo == 0) 
                <a href="{{ url('teste/'.$user->id.'/edit') }}" class="btn btn-warning">Editar</a>
                <form action="{{ url('teste/'.$user->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</button>
                </form>
                @else
                <a href="{{ route('teste.show', $user->id) }}" class="btn btn-info">Ver</a>
                @endif
            </div>
        </div>
     </div>
@endsection