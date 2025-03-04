@extends('adminlte::page')
@section('css')
<link rel="stylesheet" href="{{ asset('css/custom-dark-mode.css') }}">

@endsection

@section('content_top_nav_right')
<li class="nav-item">
    <a id="dark-mode-toggle" class="nav-link" href="#" role="button">
        <i class="fas fa-moon"></i>
        </a>
</li>
<li class="nav-item dropdown">

    <a id="notification-toggle" class="nav-link" href="{{ route('solicitar.notificacoes') }}" role="button">

        <i class="fas fa-bell"></i>
        <span id="notification-badge" class="badge badge-danger" style="display: none;">0</span>
    </a>

    <div id="notification-dropdown" class="dropdown-menu dropdown-menu-right shadow-lg" style="display: none; width: 300px;">
        <div class="dropdown-header">Notificações</div>
        <div id="notification-list" class="p-2">
            <p class="text-muted text-center">Sem notificações</p>
        </div>
        <div class="dropdown-footer text-center">
            <a href="{{ route('solicitar.notificacoes') }}">Ver todas</a>
        </div>
    </div>
</li>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Configuração do Dark Mode
            const darkModeToggle = document.getElementById('dark-mode-toggle');
            const body = document.body;
            const darkMode = localStorage.getItem('darkMode');

            if (darkMode === 'enabled') {
                body.classList.add('dark-mode');
                darkModeToggle.innerHTML = '<i class="fas fa-sun"></i>';
            }

            darkModeToggle.addEventListener('click', function () {
                body.classList.toggle('dark-mode');
                if (body.classList.contains('dark-mode')) {
                    localStorage.setItem('darkMode', 'enabled');
                    darkModeToggle.innerHTML = '<i class="fas fa-sun"></i>';
                } else {
                    localStorage.setItem('darkMode', 'disabled');
                    darkModeToggle.innerHTML = '<i class="fas fa-moon"></i>';
                }
            });

            // Função para atualizar a contagem de notificações
            function atualizarNotificacoes() {
    fetch('/notificacoes/contar') // Endpoint que retorna o número de notificações não lidas
        .then(response => response.json())
        .then(data => {
            const badge = document.getElementById('notification-badge');
            if (data.count > 0) {
                badge.style.display = 'inline-block';  // Exibe o badge
                badge.textContent = data.count;       // Atualiza o número de notificações
            } 
        })
        .catch(error => console.error('Erro ao buscar notificações:', error));
}


            // Função para atualizar a lista de notificações no dropdown
            function atualizarListaNotificacoes() {
                fetch('/notificacoes/listar') // Endpoint que retorna as notificações
                    .then(response => response.json())
                    .then(notifications => {
                        const notificationList = document.getElementById('notification-list');
                        notificationList.innerHTML = ''; // Limpa a lista atual

                        if (notifications.length > 0) {
                            notifications.forEach(notification => {
                                const notificationItem = document.createElement('div');
                                notificationItem.classList.add('p-2');
                                notificationItem.innerHTML = `
                                    <p>${notification.data.message}</p> <!-- Acessando o conteúdo da notificação -->
                                    <small>${new Date(notification.created_at).toLocaleString()}</small>
                                `;
                                notificationList.appendChild(notificationItem);
                            });
                        } else {
                            notificationList.innerHTML = '<p class="text-muted text-center">Sem notificações</p>';
                        }
                    })
                    .catch(error => console.error('Erro ao buscar notificações:', error));
            }

            // Atualiza notificações a cada 10 segundos
            setInterval(() => {
                atualizarNotificacoes();
                atualizarListaNotificacoes(); // Atualiza a lista de notificações no dropdown
            }, 10000);

            // Chama ao carregar a página
            atualizarNotificacoes();
            atualizarListaNotificacoes();   
        });
    </script>
@endsection