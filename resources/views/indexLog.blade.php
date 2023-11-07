@extends('layouts.layout')
@section('content')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<div class="container">
    <h1 class="text-center mg-top-title">Registros de Log</h1>
    <table id="logTable" class="table table-striped table-bordered text-center mt-5">
        <thead>
            <tr>
                <th scope="col" class="align-middle">Data de Criação</th>
                <th scope="col" class="align-middle">Cadastro</th>
                <th scope="col" class="align-middle">Valor Anterior</th>
                <th scope="col" class="align-middle">Valor Novo</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
            <tr>
                <td class="align-middle">{{ $log->RECCREATEDON }}</td>
                <td class="align-middle">{{ $log->CADASTRO }}</td>
                <td class="align-middle">
                    @php
                        $valorAnterior = json_decode($log->VALORANTERIOR);
                        echo json_encode($valorAnterior, JSON_PRETTY_PRINT);
                    @endphp
                </td>
                <td class="align-middle">
                    @php
                        $valorNovo = json_decode($log->VALORNOVO);
                        echo json_encode($valorNovo, JSON_PRETTY_PRINT);
                    @endphp
                </td>
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
