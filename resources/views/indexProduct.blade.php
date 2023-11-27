@extends('layouts.layout')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<div class="container">
    <h1 class="text-center mg-top-title">Lista de Produtos</h1>
    <button onclick="goBack()" class="btn-ajust btn-edit">Voltar</button>
    <div class="d-flex justify-content-end">
    <button class="btn-btn btn-principal mg-bottom" data-bs-toggle="modal" data-bs-target="#createModal">Novo Produto</button>
</div>
    <table id="productTable" class="table table-striped table-bordered text-center mt-5">
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
        @foreach($produtos as $produto)
<tr>
    <td class="align-middle">{{ $produto->IDPRODUTO }}</td>
    <td class="align-middle">{{ $produto->NOME }}</td>
    <td class="align-middle">{{ $produto->APELIDO }}</td>
    <td class="align-middle">{{ $produto->ATIVO == 1 ? 'Sim' : 'Não' }}</td>
    <td class="align-middle">
    <button class="btn-ajust btn-edit" data-produto-id="{{ $produto->IDPRODUTO }}" data-bs-toggle="modal" data-bs-target="#editModal">Editar</button>
    <a href="#" class="btn-e btn-excluir" data-produto-id="{{ $produto->IDPRODUTO }}">Excluir</a>

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
                <h5 class="modal-title" id="createModalLabel">Novo Produto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <div id="createModalContent">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-16">
                <div class="row">
                    <div class="text-center">
                        <h1>Criar Produtos</h1>
                    </div>
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

                        <form method="POST" action="{{ route('produtos.store') }}">
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

                            <div class="text-center">
                                <button type="submit" class="btn-s btn-suc">Criar Produto</button>
                                <a href="{{ route('produtos.index') }}" class="btn-ajust btn-edi">Cancelar</a>
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


@foreach($produtos as $produto)
    @include('editProductModal', ['produto' => $produto])
@endforeach

<script>
$(document).ready(function () {
    var dataTable = $('#productTable').DataTable({
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
        var produtoId = $(this).data('produto-id');
        $.ajax({
            type: 'GET',
            url: "{{ route('produtos.edit', ['IDPRODUTO' => '__IDPRODUTO__']) }}".replace('__IDPRODUTO__', produtoId),
            success: function (data) {
                $('#editModalContent').html(data);
            },
            error: function () {
                alert('Erro ao carregar os detalhes do produto.');
            }
        });
    });

    $('.btn-excluir').click(function (e) {
        e.preventDefault();

        var produtoId = $(this).data('produto-id');
        if (confirm('Tem certeza de que deseja excluir este produto?')) {
            $.ajax({
                type: 'POST',
                url: "{{ route('produtos.delete-web', ['IDPRODUTO' => '__IDPRODUTO__']) }}".replace('__IDPRODUTO__', produtoId),
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                success: function (result) {
                    alert('Produto excluído com sucesso.');
                    window.location.reload();
                },
                error: function () {
                    alert('Erro ao excluir o produto.');
                }
            });
        }
    });
});
$(document).ready(function () {
    $('.create-btn').click(function () {
        $.ajax({
            type: 'GET',
            url: "{{ route('produtos.create') }}",
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
