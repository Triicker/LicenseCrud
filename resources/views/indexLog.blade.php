@extends('layouts.layout')
@section('content')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<div class="container">
    <h1 class="text-center mg-top-title">Registros de Log</h1>
    <table id="logTable" class="table table-striped table-bordered text-center mt-5">
        <thead>
            <tr>
                <th scope="col" class="align-middle">Data de consulta</th>
                <th scope="col" class="align-middle">Usuário</th>
                <th scope="col" class="align-middle">Liberado</th>
                <th scope="col" class="align-middle">Liberado até</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
            <tr>
                <td class="align-middle">{{ $log->RECCREATEDON }}</td>
                <td class="align-middle">{{ $log->USUARIO }}</td>
                <td class="align-middle">{{ $log->LIBERADO }}</td>
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
</script>
@endsection
