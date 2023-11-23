@extends('layouts.layout')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<div class="container">
    <h1 class="text-center mg-top-title">Lista de Coligadas</h1>
    <p>Nome do cliente: @isset($data['coligadas'][0]->cliente->NOME) {{ $data['coligadas'][0]->cliente->NOME }} @endisset</p>
    <div class="d-flex justify-content-end">
    <button class="btn-btn btn-principal mg-bottom" data-bs-toggle="modal" data-bs-target="#createModal">Nova Coligada</button>
</div>
    <table id="coligadaTable" class="table table-striped table-bordered text-center mt-5">
        <thead>
            <tr>
                <th scope="col" class="align-middle">Nome</th>
                <th scope="col" class="align-middle">Nome Fantasia</th>
                <th scope="col" class="align-middle">CGC</th>
                <th scope="col" class="align-middle">Email</th>
                <th scope="col" class="align-middle">Telefone</th>
                <th scope="col" class="align-middle">ID Imagem</th>
                <th scope="col" class="align-middle">Apelido</th>
                <th scope="col" class="align-middle">Ativo</th>
                <th scope="col" class="align-middle">Ações</th>
            </tr>
        </thead>
        <tbody>
        @foreach($data['coligadas'] as $coligada)
<tr>
    <td class="align-middle">{{ $coligada->NOME }}</td>
    <td class="align-middle">{{ $coligada->NOMEFANTASIA }}</td>
    <td class="align-middle">{{ $coligada->CGC }}</td>
    <td class="align-middle">{{ $coligada->EMAIL }}</td>
    <td class="align-middle">{{ $coligada->TELEFONE }}</td>
    <td class="align-middle">{{ $coligada->IDIMAGEM }}</td>
    <td class="align-middle">{{ $coligada->APELIDO }}</td>
    <td class="align-middle">{{ $coligada->ATIVO }}</td>
    <td class="align-middle">
    <a class="btn-c btn-col" href="{{ route('licencas.coligada', ['IDCOLIGADA' => $coligada->IDCOLIGADA]) }}" >Licenças</a>
    <button class="btn-ajust btn-edit" data-coligada-id="{{ $coligada->IDCOLIGADA }}" data-bs-toggle="modal" data-bs-target="#editModal">Editar</button>
    <a href="#" class="btn-e btn-excluir" data-coligada-id="{{ $coligada->IDCOLIGADA }}">Excluir</a>

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
                <h5 class="modal-title" id="createModalLabel">Nova Coligada</h5>
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

                        <form method="POST" action="{{ route('coligadas.store') }}">
                            @csrf
                            @method('POST')

                            <div class="mb-3">
                        <label for="NOME" class="form-label">Nome</label>
                        <input type="text" name="NOME" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="NOMEFANTASIA" class="form-label">Nome Fantasia</label>
                        <input type="text" name="NOMEFANTASIA" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="CGC" class="form-label">CGC</label>
                        <input type="text" name="CGC" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="EMAIL" class="form-label">Email</label>
                        <input type="text" name="EMAIL" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="TELEFONE" class="form-label">Telefone</label>
                        <input type="text" name="TELEFONE" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="IDIMAGEM" class="form-label">ID Imagem</label>
                        <input type="text" name="IDIMAGEM" class="form-control" required>
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
                        <label for="CLIENTE" class="form-label">Cliente</label>
                        <input type="text" class="form-control" id="CLIENTE_NOME" name="CLIENTE_NOME" value="{{ isset($data['cliente']->NOME) ? $data['cliente']->NOME : '' }}" disabled>
                        <input type="hidden" id="CLIENTE" name="CLIENTE" value="{{ isset($data['cliente']->IDCLIENTE) ? $data['cliente']->IDCLIENTE : '' }}">
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn-s btn-suc">Criar Coligada</button>
                        <a href="{{ route('coligadas.index') }}" class="btn-ajust btn-edi">Cancelar</a>
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


@foreach($data['coligadas'] as $coligada)
    @include('editColigadaModal', ['coligada' => $coligada])
@endforeach

<script>
$(document).ready(function () {
    var dataTable = $('#coligadaTable').DataTable({
        "searching": true,   // Ativar barra de pesquisa
        "paging": true,      // Ativar paginação
        "language": {
            "decimal": ",",
            "thousands": ".",
            "lengthMenu": "Mostrar _MENU_ registros por página", // Aqui você pode definir o texto desejado
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
        var coligadaId = $(this).data('coligada-id');
        $.ajax({
            type: 'GET',
            url: "{{ route('coligadas.edit', ['IDCOLIGADA' => '__IDCOLIGADA__']) }}".replace('__IDCOLIGADA__', coligadaId),
            success: function (data) {
                $('#editModalContent').html(data);
            },
            error: function () {
                alert('Erro ao carregar os detalhes do coligada.');
            }
        });
    });

    $('.btn-excluir').click(function (e) {
        e.preventDefault();

        var coligadaId = $(this).data('coligada-id');
        if (confirm('Tem certeza de que deseja excluir este coligada?')) {
            $.ajax({
                type: 'POST',
                url: "{{ route('coligadas.delete-web', ['IDCOLIGADA' => '__IDCOLIGADA__']) }}".replace('__IDCOLIGADA__', coligadaId),
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                success: function (result) {
                    alert('Coligada excluído com sucesso.');
                    window.location.reload();
                },
                error: function () {
                    alert('Erro ao excluir o Coligada.');
                }
            });
        }
    });
});
$(document).ready(function () {
    $('.create-btn').click(function () {
        $.ajax({
            type: 'GET',
            url: "{{ route('coligadas.create') }}",
            success: function (data) {
                $('#createModalContent').html(data);
            },
            error: function () {
                alert('Erro ao carregar o formulário de criação.');
            }
        });
    });
});
</script>

@endsection
