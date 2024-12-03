@extends('layouts.darkMode')
@section('content_header')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <h1>Usuários</h1>
    @if (auth()->user()->cargo == 0) 
        <a class="btn btn-novo" href="{{ route('teste.create') }}">Novo Usuário</a>

        <form action="{{ route('teste.index') }}" method="GET">
            <input class="btn btn-novo" name="search" placeholder="Buscar usuário" value="{{ request('search') }}">
            <button type="submit" class="btn btn-novo">Buscar</button>
        </form>
    @else
        <form action="{{ route('teste.index') }}" method="GET">
            <input class="btn btn-novo" name="search" placeholder="Buscar usuário" value="{{ request('search') }}">
            <button type="submit" class="btn btn-novo">Buscar</button>
        </form>
    @endif

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
   <div class="content">
    @if (session('sucess'))
        <div class="alert alert-success" id="message" role="alert">
           {{ session('sucess') }}
        </div>
    @endif

    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Status:</th>
                <th>Nome:</th>
                <th>E-mail:</th>
                @if (auth()->user()->cargo ==0)
                <th>CPF:</th>
                @endif
                <th>Permissões:</th>
                <th>Gerenciamento:</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <script>
                        function toggleStatus(userId, isChecked) {
    fetch(`/teste/${userId}/mudarStatusU`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            status: isChecked ? 'Ativo' : 'Inativo'
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erro na requisição');
        }
        return response.json(); // Espera um JSON como resposta
    })
    .then(data => {
        if (data.success) {
            alert('Status atualizado com sucesso!');
        } else {
            throw new Error(data.message);
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao atualizar status!');
        document.getElementById(`status_${userId}`).checked = !isChecked; // Reverte o estado do switch
    });
}

                    </script>
                                    <td>
                                    @if (auth()->user()->cargo == 0) 
                                                <label class="toggle-switch">
                                                    <input type="checkbox" 
                                                    id="status_{{ $user->id }}" 
                                                    {{ $user->status === 'Ativo' ? 'checked' : '' }} 
                                                    onchange="toggleStatus({{ $user->id }}, this.checked)">
                                                                    <div class="toggle-switch-background">
                                                                        <div class="toggle-switch-handle"></div>
                                                                    </div>
                                                </label>
                                    @endif
                                    </td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    @if (auth()->user()->cargo ==0)
                    <td>{{ $user->cpf }}</td>
                    @endif
                    <td>
                        <a href="{{ route('teste.permissao', $user->id) }}"> <i class="fas fa-user"></i> </a>
                    </td>
                    <td>
                        @if (auth()->user()->cargo == 0) 
                            <a href="{{ route('teste.show', $user->id) }}" class="btn btn-info">Ver</a>
                            <a href="{{ route('teste.edit', $user->id) }}" class="btn btn-info">Editar</a>
                            <form action="{{ route('teste.destroy', $user->id) }}" method="POST" style="display: inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Certeza que deseja excluir?')">Excluir</button>
                            </form>
                        @else
                            <a href="{{ route('teste.show', $user->id) }}" class="btn btn-info">Ver</a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
