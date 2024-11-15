@extends('layouts.darkMode')

@section('content_header')
    <h1>Situação das solicitações</h1>

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
                