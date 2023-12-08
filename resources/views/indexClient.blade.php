@extends('layouts.layout')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<div class="container">
    <h1 class="text-center mg-top-title">Lista de Clientes</h1>
    <button onclick="goBack()" class="btn-ajust btn-edit">Voltar</button>

    <div class="d-flex justify-content-end">
    <button class="btn-btn btn-principal mg-bottom" data-bs-toggle="modal" data-bs-target="#createModal">Novo Cliente</button>
</div>
<table id="clienteTable" class="table table-striped table-bordered text-center mt-5">
        <thead>
            <tr>
                <th scope="col" class="align-middle">ID</th>
                <th scope="col" class="align-middle">Nome</th>
                <th scope="col" class="align-middle">Apelido</th>
                <th scope="col" class="align-middle">Ativo</th>
                <th scope="col" class="align-middle">Ações</th>
            </tr>
        </thead>
        <tbody>
        @foreach($data['clientes'] as $cliente)
<tr>
    <td class="align-middle">{{ $cliente->IDCLIENTE }}</td>
    <td class="align-middle">{{ $cliente->NOME }}</td>
    <td class="align-middle">{{ $cliente->APELIDO }}</td>
    <td class="align-middle">{{ $cliente->ATIVO == 1 ? 'Sim' : 'Não' }}</td>
    <td class="align-middle">
    <a class="btn-c btn-col" href="{{ route('contatos.cliente', ['IDCLIENTE' => $cliente->IDCLIENTE]) }}">Contato</a>
    <a class="btn-c btn-col" href="{{ route('coligadas.cliente', ['IDCLIENTE' => $cliente->IDCLIENTE]) }}">Coligada</a>
    <button class="btn-ajust btn-edit" data-cliente-id="{{ $cliente->IDCLIENTE }}" data-bs-toggle="modal" data-bs-target="#editModal">Editar</button>
    <a href="#" class="btn-e btn-excluir" data-cliente-id="{{ $cliente->IDCLIENTE }}">Excluir</a>
    </td>
</tr>
@endforeach
        </tbody>
    </table>
</div>

<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Novo Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <div id="createModalContent">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-16">
                <div class="row">
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('clientes.store') }}">
                            @csrf
                            @method('POST')

                            <div class="mb-3">
                                <label for="NOME" class="form-label">Nome</label>
                                <input type="text" name="NOME" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="APELIDO" class="form-label">Apelido</label>
                                <input type="text" name="APELIDO" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="ATIVO" class="form-label">Ativo</label>
                                <select name="ATIVO" class="form-select" required>
                                    <option value="1">Sim</option>
                                    <option value="0">Não</option> 
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="IDEMPRESA" class="form-label">Empresa</label>
                                <input type="text" class="form-control" id="EMPRESA" name="EMPRESA" value="{{ $cliente->empresa->NOME }}" disabled>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn-s btn-suc">Criar Cliente</button>
                                <button type="button" class="btn-ajust btn-edi" data-bs-dismiss="modal">Cancelar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

                </div>
            </div>
        </div>
    </div>
</div>

@foreach($data['clientes'] as $cliente)
    @include('editUserModal', ['cliente' => $cliente, 'empresas' => $data['empresas']])
@endforeach

<script>
$(document).ready(function () {
    var dataTable = $('#clienteTable').DataTable({
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
    $('.btn-edit').click(function () {
        var clienteId = $(this).data('cliente-id');
        $.ajax({
            type: 'GET',
            url: "{{ route('clientes.edit', ['IDCLIENTE' => '__IDCLIENTE__']) }}".replace('__IDCLIENTE__', clienteId),
            success: function (data) {
                $('#editModalContent').html(data);
            }, 
            error: function () {
                alert('Erro ao carregar os detalhes do cliente.');
            }
        });
    });

    $('.btn-excluir').click(function (e) {
        e.preventDefault(); 

        var clienteId = $(this).data('cliente-id');
        if (confirm('Tem certeza de que deseja excluir este cliente?')) {
            $.ajax({
                type: 'POST',
                url: "{{ route('clientes.delete-web', ['IDCLIENTE' => '__IDCLIENTE__']) }}".replace('__IDCLIENTE__', clienteId),
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                success: function (result) {
                    alert('Cliente excluído com sucesso.');
                    window.location.reload();
                },
                error: function () {
                    alert('Não é possível excluir o cliente. Existem contatos associados.');
                }
            });
        }
    });
});
$(document).ready(function () {
    $('.create-btn').click(function () {
        $.ajax({
            type: 'GET',
            url: "{{ route('clientes.create') }}", 
            success: function (data) {
                $('#createModalContent').html(data);
            },
            error: function () {
                alert('Erro ao carregar o formulário de criação.');
            }
        });
    });
});
function goBack() {
    window.history.back();
}
</script>
@endsection
