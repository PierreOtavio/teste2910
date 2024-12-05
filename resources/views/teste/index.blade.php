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

    <style>
        .status-indicator {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: inline-block;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .status-indicator.active {
            background-color: #00d68f;
        }
        .status-indicator.inactive {
            background-color: crimson;
        }
    </style>
    
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Status:</th>
                <th>Nome:</th>
                <th>E-mail:</th>
                @if (auth()->user()->cargo == 0)
                    <th>CPF:</th>
                @endif
                <th>Permissões:</th>
                <th>Gerenciamento:</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>
                        @if (auth()->user()->cargo == 0)
                            <div 
                                class="status-indicator {{ $user->status === 'Ativo' ? 'active' : 'inactive' }}" 
                                id="status_{{ $user->id }}" 
                                onclick="toggleStatus({{ $user->id }})"
                            ></div>
                        @else
                            @if ($user->status == "Ativo")
                                <div class="bolinha1">
                                    <i class="fas fa-user"></i>
                                </div>
                            @else
                                <div class="bolinha2">
                                    <i class="fas fa-user"></i>
                                </div>
                            @endif
                        @endif
                    </td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    @if (auth()->user()->cargo == 0)
                        <td>{{ $user->cpf }}</td>
                    @endif
                    <td>
                        <a href="{{ route('teste.permissao', $user->id) }}"> <i class="fas fa-user"></i> </a>
                    </td>
                    <td>
                        @if (auth()->user()->cargo == 0)
                            <a href="{{ route('teste.show', $user->id) }}" class="btn btn-info">Ver</a>
                            <a href="{{ route('teste.edit', $user->id) }}" class="btn btn-warning">Editar</a>
                        @else
                            <a href="{{ route('teste.show', $user->id) }}" class="btn btn-info">Ver</a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <script>
   function toggleStatus(userId) {
    const indicator = document.getElementById(`status_${userId}`);
    const isActive = indicator.classList.contains('active');
    
    fetch(`/teste/${userId}/mudarStatusU`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            status: isActive ? 'Inativo' : 'Ativo'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Alterna a cor com base no novo status
            indicator.classList.toggle('active', !isActive);
            indicator.classList.toggle('inactive', isActive);

            // Cria o alerta de sucesso
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-success position-fixed';
            alertDiv.id = 'message';
            alertDiv.style.top = '150px';
            alertDiv.style.right = '560px';
            alertDiv.style.zIndex = '1050';
            alertDiv.innerHTML = data.message;

            document.body.appendChild(alertDiv);

            // Remove o alerta após 3 segundos
            setTimeout(() => {
                if (alertDiv) {
                    alertDiv.remove();
                }
            }, 3000);
        } else {
            throw new Error(data.message);
        }
    })
    .catch(error => {
        console.error('Erro:', error);

        // Cria o alerta de erro
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-danger position-fixed';
        alertDiv.style.top = '150px';
        alertDiv.style.right = '560px';
        alertDiv.style.zIndex = '1050';
        alertDiv.innerHTML = 'Erro ao atualizar status!';
        
        document.body.appendChild(alertDiv);

        // Remove o alerta após 3 segundos
        setTimeout(() => {
            if (alertDiv) {
                alertDiv.remove();
            }
        }, 3000);
    });
}
</script>    
@endsection