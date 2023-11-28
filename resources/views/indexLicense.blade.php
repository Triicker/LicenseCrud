@extends('layouts.layout')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<div class="container">
    <h1 class="text-center mg-top-title">Lista de Licenças</h1>
    <button onclick="goBack()" class="btn-ajust btn-edit">Voltar</button>
    <p>Nome do cliente: {{ $cliente ? $cliente->NOME : 'N/A' }}</p>

    <div class="d-flex justify-content-end">
    <button class="btn-btn btn-principal mg-bottom" data-bs-toggle="modal" data-bs-target="#createModal">Nova Licença</button>
    </div>
    <table id="licenseTable" class="table table-striped table-bordered text-center mt-5">
        <thead>
            <tr>
                <th scope="col" class="align-middle">Nome do Produto</th>
                <th scope="col" class="align-middle">Data inicio</th>
                <th scope="col" class="align-middle">Data fim</th>
                <th scope="col" class="align-middle">Ativo</th>              
                <th scope="col" class="align-middle">Ações</th>
            </tr>
        </thead>
        <tbody>
        @foreach($licencas as $licenca)
      <tr>
        <td class="align-middle">{{ $licenca->produto->NOME }}</td>
        <td class="align-middle">{{ \Carbon\Carbon::parse($licenca->DTINICIO)->format('d/m/Y') }}</td>
        <td class="align-middle">{{ \Carbon\Carbon::parse($licenca->DTFIM)->format('d/m/Y') }}</td>
        <td class="align-middle">{{ $licenca->ATIVO == 1 ? 'Sim' : 'Não' }}</td>
        <td class="align-middle">
            <button class="btn-ajust btn-edit" data-licenca-id="{{ $licenca->IDCOLIGADA }}" data-bs-toggle="modal" data-bs-target="#editModal">Editar</button>
            <a href="#" class="btn-e btn-excluir" data-licenca-id="{{ $licenca->IDCOLIGADA }}">Excluir</a>
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
                <h5 class="modal-title" id="createModalLabel">Nova Licença</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="createModalContent">
                    <form method="POST" action="{{ route('licencas.store') }}">
                        @csrf
                        @method('POST')
            
                        <input type="hidden" name="IDCLIENTE" value="{{ $cliente->IDCLIENTE }}">
                        <input type="hidden" name="IDCOLIGADA" value="{{ $coligada->IDCOLIGADA }}">
                        <div class="mb-3">
                        <label for="IDPRODUTO" class="form-label">Produto</label>
                        <select name="IDPRODUTO" class="form-select" required>
                         @foreach($produtos as $produtoOption)
                        <option value="{{ $produtoOption->IDPRODUTO }}">{{ $produtoOption->NOME }}</option>
                          @endforeach
                        </select>
                        </div>

                        <div class="mb-3">
                            <label for="DTINICIO" class="form-label">Data de Início</label>
                            <input type="DATE" class="form-control" id="DTINICIO" name="DTINICIO" value="{{ \Carbon\Carbon::parse($licenca->DTINICIO)->format('d/m/Y') }}" required>   
                        </div>

                        <div class="mb-3">
                            <label for="DTFIM" class="form-label">Data de Fim</label>
                            <input type="DATE" class="form-control" id="DTFIM" name="DTFIM" value="{{ \Carbon\Carbon::parse($licenca->DTFIM)->format('d/m/Y') }}" required>   
                        </div>

                        <div class="mb-3">
                            <label for="ATIVO" class="form-label">Ativo</label>
                            <select name="ATIVO" class="form-select" required>
                                <option value="1">Sim</option>
                                <option value="0">Não</option> 
                            </select>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn-s btn-suc">Criar Licença</button>
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

@foreach($licencas as $licenca)
    @include('editLicenceModal', ['licenca' => $licenca])
@endforeach

<script>
$(document).ready(function () {
    var dataTable = $('#licenseTable').DataTable({
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
    var licencaId = $(this).data('licenca-id');
    $.ajax({
        type: 'GET',
        url: "{{ route('licencas.edit', ['IDCOLIGADA' => '__IDCOLIGADA__']) }}".replace('__IDCOLIGADA__', licencaId),
        success: function (data) {
            $('#editModalContent').html(data);
        }, 
        error: function () {
            alert('Erro ao carregar os detalhes da licença.');
        }
    });
});


    $('.btn-excluir').click(function (e) {
        e.preventDefault(); 

        var licencaId = $(this).data('licenca-id');
        if (confirm('Tem certeza de que deseja excluir este Licenca?')) {
            $.ajax({
                type: 'POST',
                url: "{{ route('licencas.delete-web', ['IDCOLIGADA' => '__IDCOLIGADA__']) }}".replace('__IDCOLIGADA__', licencaId),
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                success: function (result) {
                    alert('Licenca excluído com sucesso.');
                    window.location.reload();
                },
                error: function () {
                    alert('Erro ao excluir a Licenca.');
                }
            });
        }
    });
});
$(document).ready(function () {
    $('.create-btn').click(function () {
        $.ajax({
            type: 'GET',
            url: "{{ route('licencas.create') }}", 
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