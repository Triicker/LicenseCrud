<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            <div class="card">
                <div class="card-header text-center">
                    <h1>Excluir Contato</h1>
                </div>
                <div class="card-body">
                    <p>Você tem certeza de que deseja excluir o Contato {{ $contato->NOME }}?</p>
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>ID</th>
                                <td>{{ $contato->IDCONTATO }}</td>
                            </tr>
                            <tr>
                                <th>ID</th>
                                <td>{{ $contato->IDCLIENTE }}</td>
                            </tr>
                            <tr>
                                <th>Nome</th>
                                <td>{{ $contato->NOME }}</td>
                            </tr>
                            <tr>
                                <th>Apelido</th>
                                <td>{{ $contato->APELIDO }}</td>
                            </tr>
                            <tr>
                                <th>Telefone</th>
                                <td>{{ $contato->TELEFONE }}</td>
                            </tr>
                            <tr>
                                <th>Celular</th>
                                <td>{{ $contato->CELULAR }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $contato->EMAIL }}</td>
                            </tr>
                            <tr>
                                <th>Cliente</th>
                                <td>{{ $cliente->NOME }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <form method="POST" action="{{ route('contatos.destroyContact', ['IDCONTATO' => $contato->IDCONTATO]) }}">
                        @csrf
                        @method('DELETE')

                        <div class="text-center">
                            <button type="submit" class="btn btn-danger">Sim, Excluir</button>
                            <a href="{{ route('contatos.indexContact') }}" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
