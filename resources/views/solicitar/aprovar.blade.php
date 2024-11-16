@extends('layouts.darkMode')

@section('content_header')
    <h1>Situação das solicitações</h1>
@endsection

@section('content')
<table class="table table-bordered table-hover">
       <thead>
           <tr>
                <th>Id da Solicitação:</th>
                <th>Situação</th>
                <th>Data:</th>
                <th>Ações</th>
           </tr>
       </thead>
       <tbody>
        @foreach($solicitacoes as $solicitacao)
           <tr>
                <td>{{ $solicitacao->id }}</td>
                <td>{{ $solicitacao->situacao }}</td>
                <td>{{ $solicitacao->data_inicial }}</td>
                <td>
                    <form action="{{ route('solicitar.aprovarOuReprovar', $solicitacao->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <select name="situacao">
                            <option value="aprovado">Aprovar</option>
                            <option value="reprovado">Reprovar</option>
                        </select>
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </form>
                </td>
           </tr>
        @endforeach
       </tbody>
</table>
@endsection
                