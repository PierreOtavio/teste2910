<!DOCTYPE html>
<html>
<head>
    <title>Relatório de uso do Veículo</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .details { margin-top: 20px; }
        hr { margin-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Relatório de uso do Veículo</h1>
    </div>
    {{-- pdf.blade: --}}
<div class="details">
        <h2>O.S: {{ $solicitar->id }}</h2>
        <p><strong>Colaborador:</strong> {{ $solicitar->user->name }}</p>
        <p><strong>ID do colaborador:</strong> {{ $user->id }}</p>
        <p><strong>Email do Colaborador:</strong> {{ $user->email }}</p>
        <p><strong>Responsável que aceitou a solicitação:</strong> {{ $solicitar->responsavel2->name ?? 'Não informado' }}</p>
        <p><strong>Horário que foi aceito:</strong> {{ $solicitar->hora_aceito }}</p>
        <p><strong>Data em que foi aceito:</strong> {{ \Carbon\Carbon::parse($solicitar->data_aceito)->format('d/m/y') }}</p>
        <p><strong>Veículo utilizado:</strong> {{ $veiculo->marca }} {{ $veiculo->modelo }}</p>
        <p><strong>Placa:</strong> {{ $veiculo->placa }}</p>
        <p><strong>Data Inicial:</strong> {{ \Carbon\Carbon::parse($solicitar->data_inicial)->format('d/m/y') }}</p>
        <p><strong>Data Final:</strong> {{ \Carbon\Carbon::parse($solicitar->data_final)->format('d/m/y') }}</p>
        <p><strong>Hora Inicial:</strong> {{ \Carbon\Carbon::parse($solicitar->hora_inicial)->format('h:i A') }}</p>
        <p><strong>Hora Final:</strong> {{ \Carbon\Carbon::parse($solicitar->hora_final)->format('h:i A') }}</p>
        <p><strong>Quilometragem Inicial:</strong> {{ $veiculo->velocimetro_inicio }} km</p>
        <p><strong>Quilometragem Final:</strong> {{ $veiculo->velocimetro_final }} km</p>
        <p><strong>Quilômetros Percorridos:</strong> {{ $veiculo->velocimetro_final - $veiculo->velocimetro_inicio }} km</p>
        <p><strong>Observações:</strong> {{ $solicitar->obs_user }}</p>
        <hr>
    </div>
</body>
</html>