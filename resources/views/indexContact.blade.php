@extends('layouts.layout')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<div class="container">
    <h1 class="text-center mg-top-title">Lista de Contatos</h1>
    <button onclick="goBack()" class="btn-ajust btn-edit">Voltar</button>
    <p>Nome do cliente: @isset($data['cliente']->NOME) {{ $data['cliente']->NOME }} @endisset</p>
    <div class="d-flex justify-content-end">
    <button class="btn-btn btn-principal mg-bottom" data-bs-toggle="modal" data-bs-target="#createModal">Novo Contato</button>
    </div>
    <table id="contactTable" class="table table-striped table-bordered mt-5 text-center">
        <thead>
            <tr>
                <th scope="col" class="align-middle">ID</th>
                <th scope="col" class="align-middle">Nome</th>
                <th scope="col" class="align-middle">Apelido</th>
                <th scope="col" class="align-middle">Telefone</th>
                <th scope="col" class="align-middle">Celular</th>
                <th scope="col" class="align-middle">Email</th>
                <th scope="col" class="align-middle">Ativo</th>
                <th scope="col" class="align-middle">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['contatos'] as $contato)

                <tr>
                    <td class="align-middle">{{ $contato->IDCONTATO }}</td>
                    <td class="align-middle">{{ $contato->NOME }}</td>
                    <td class="align-middle">{{ $contato->APELIDO }}</td>
                    <td class="align-middle">{{ $contato->TELEFONE }}</td>
                    <td class="align-middle">{{ $contato->CELULAR }}</td>
                    <td class="align-middle">{{ $contato->EMAIL }}</td>
                    <td class="align-middle">{{ $contato->ATIVO == 1 ? 'Sim' : 'Não' }}</td>
                    <td class="align-middle">
                        <button class="btn-ajust btn-edit" data-contato-id="{{ $contato->IDCONTATO }}" data-bs-toggle="modal" data-bs-target="#editModal">Editar</button>
                        <a href="#" class="btn-e btn-excluir" data-contato-id="{{ $contato->IDCONTATO }}">Excluir</a>          
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
                <h5 class="modal-title" id="createModalLabel">Novo Contato</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

            
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

                                    <form method="POST" action="{{ route('contatos.store') }}">
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
                                             <label for="TELEFONE" class="form-label">Telefone</label>
                                            <input type="text" name="TELEFONE" class="form-control" required oninput="formatarTelefone(this)">
                                        </div>

                                        <div class="mb-3">
                                            <label for="CELULAR" class="form-label">Celular</label>
                                            <input type="text" name="CELULAR" class="form-control" required oninput="formatarCelular(this)">
                                        </div>

                                        <div class="mb-3">
                                            <label for="EMAIL" class="form-label">Email</label>
                                            <input type="text" name="EMAIL" class="form-control" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                                        <div class="invalid-feedback">Por favor, insira um endereço de e-mail válido.</div>
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
                                            <button type="submit" class="btn-s btn-suc">Criar Contato</button>
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
        </div>
    </div>
</div>
@foreach($data['contatos'] as $contato)
    @include('editContactModal', ['contato' => $contato, 'clientes' => $data['clientes']])
@endforeach

<script>
$(document).ready(function () {
    var dataTable = $('#contactTable').DataTable({
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
        var contatoId = $(this).data('contato-id');
        $.ajax({
            type: 'GET',
            url: "{{ route('contatos.edit', ['IDCONTATO' => '__IDCONTATO__']) }}".replace('__IDCONTATO__', contatoId),
            success: function (data) {
                $('#editModalContent').html(data);
            },
            error: function () {
                alert('Erro ao carregar os detalhes do contato.');
            }
        });
    });

    $('.btn-excluir').click(function (e) {
        e.preventDefault(); 

        var contatoId = $(this).data('contato-id');
        if (confirm('Tem certeza de que deseja excluir este contato?')) {
            $.ajax({
                type: 'POST',
                url: "{{ route('contatos.delete-web', ['IDCONTATO' => '__IDCONTATO__']) }}".replace('__IDCONTATO__', contatoId),
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                success: function (result) {
                    alert('Contato excluído com sucesso.');
                    window.location.reload();
                },
                error: function () {
                    alert('Erro ao excluir o contato.');
                }
            });
        }
    });
});
$(document).ready(function () {
    $('.create-btn').click(function () {
        $.ajax({
            type: 'GET',
            url: "{{ route('contatos.create') }}", 
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
function formatarTelefone(input) {
        var cleaned = input.value.replace(/\D/g, '');
        var formatted = cleaned.replace(/(\d{3})(\d{4})(\d{4})/, '($1) $2-$3');
        input.value = formatted;
    }
    function formatarCelular(input) {
        var cleaned = input.value.replace(/\D/g, '');
        var formatted = cleaned.replace(/(\d{3})(\d{5})(\d{4})/, '($1) $2-$3');
        input.value = formatted;
    }
</script>
@endsection