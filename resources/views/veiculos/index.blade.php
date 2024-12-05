@extends('layouts.darkMode')

@section('content_header')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<link rel="stylesheet" href="{{ asset('css/custom-dark-mode.css') }}">
@if(session('success'))
    <div class="alert alert-success" id="message" role="alert">
        {{ session('success') }}
    </div>
@endif
    <h1>Veículos</h1>
    @if (auth()->user()->cargo == 0)
        <a class="btn btn-novo" href="{{ route('veiculos.create') }}">Novo Veículo</a>

        <form action="{{ route('veiculos.index') }}" method="GET">
            <input class="btn btn-novo" name="search" placeholder="Buscar veículo" value="{{ request('search') }}">
            <button type="submit" class="btn btn-novo">Buscar</button>
        </form>
    @else 
        <form action="{{ route('veiculos.index') }}" method="GET">
            <input class="btn btn-novo" name="search" placeholder="Buscar veículo" value="{{ request('search') }}">
            <button type="submit" class="btn btn-novo">Buscar</button>
        </form>
    @endif

@endsection

@section('content')
   <div class="content">

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
   <table class="table table-bordered table-hover">
       <thead>
           <tr>
                <th>Veículos:</th>
                <th>Placa:</th>
                <th>Funcionamento:</th>
                <th>Gererciamento:</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($veiculos as $veiculo)
            <tr>
                <td>{{ $veiculo->marca}} - {{ $veiculo->modelo }}</td>
                <td>{{ $veiculo->placa}}</td>
                

                <script>
                    function toggleFuncionamento(veiculoId, isChecked) {
                        fetch(`/veiculos/${veiculoId}/mudarStatus`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                funcionamento: isChecked ? 0 : 1 
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
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
                <td>
                @if (auth()->user()->cargo == 0) 
                            <label class="toggle-switch">
                                <input type="checkbox" 
                                    id="funcionamento_{{ $veiculo->id }}" 
                                        {{ $veiculo->funcionamento ? '' : 'checked' }} 
                                            onchange="toggleFuncionamento({{ $veiculo->id }}, this.checked)">
                                                <div class="toggle-switch-background">
                                                    <div class="toggle-switch-handle"></div>
                                                </div>
                            </label>
                            </td>
                            <td>
                            <a href="{{ route('veiculos.show', $veiculo->id) }}" class="btn btn-info">Ver</a>
                            <a href="{{ route('veiculos.edit', $veiculo->id) }}" class="btn btn-warning">Editar</a>
                            <form action="{{ route('veiculos.destroy', $veiculo->id) }}" method="POST" style="display: inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Certeza que deseja excluir?')">Excluir</button>
                            </form>
                        </td>
                    @else
                        @if ($veiculo->funcionamento == 0)
                            <div class="carro1">
                            <i class="fas fa-car"></i>
                            </div>
                        @else 
                            <div class="carro2">
                            <i class="fas fa-car"></i>
                            </div>
                        @endif
                    <td>
                        <a href="{{ route('veiculos.show', $veiculo->id) }}" class="btn btn-info">Ver</a>
                    </td>

                    @endif
                            
                            
                
                </tr>
            @endforeach
        </tbody>
        @stop