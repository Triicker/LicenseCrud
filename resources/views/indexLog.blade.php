@extends('layouts.layout')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<div class="container">
    <h1 class="text-center mg-top-title">Registros de Log</h1>
    <button onclick="goBack()" class="btn-ajust btn-edit">Voltar</button>

    <table id="logTable" class="table table-striped table-bordered text-center mt-5">
        <thead>
            <tr>
                <th scope="col" class="align-middle">Data de consulta</th>
                <th scope="col" class="align-middle">Cliente</th>
                <th scope="col" class="align-middle">Produto</th>
                <th scope="col" class="align-middle">QTD Alunos</th>
                <th scope="col" class="align-middle">QTD Chamadas</th>
                <th scope="col" class="align-middle">Versão Totvs</th>
                <th scope="col" class="align-middle">Versão Worknow</th>
                <th scope="col" class="align-middle">Liberado</th>
                <th scope="col" class="align-middle">Liberado até</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
            <tr>
                <td class="align-middle">{{ $log->RECCREATEDON }}</td>
                <td class="align-middle">{{ $log->cliente->NOME }}</td>
                <td class="align-middle">{{ $log->produto->NOME }}</td>
                <td class="align-middle">{{ $log->QTDALUNOS }}</td>
                <td class="align-middle">{{ $log->QTDCHAMADAS }}</td>
                <td class="align-middle">{{ $log->VERSAOTOTVS }}</td>
                <td class="align-middle">{{ $log->VERSAOWORKNOW }}</td>
                <td class="align-middle">{{ $log->LIBERADO == 1 ? 'Sim' : 'Não' }}</td>
                <td class="align-middle">{{ $log->LIBERADOATE }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<script>
$(document).ready(function () {
    var dataTable = $('#logTable').DataTable({
        "searching": true,
        "paging": true,
        "language": {
            "decimal": ",",
            "thousands": ".",
            "lengthMenu": "Mostrar _MENU_ registros por página", 
            "zeroRecords": "Nenhum registro encontrado",
            "info": "Página _PAGE_ de _PAGES_",
            "infoEmpty": "Sem registros disponíveis",
            "infoFiltered": "(filtrado de _MAX_ registros no total)",
            "search": "Pesquisar:",
            "paginate": {
                "first": "Primeiro",
                "last": "Último",
                "next": "Próximo",
                "previous": "Anterior"
            }
        }
    });
});
function goBack() {
    window.history.back();
}
</script>

@endsection
