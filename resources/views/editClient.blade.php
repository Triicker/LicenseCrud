
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="container">
    <form method="POST" action="{{ route('clientes.update', ['IDCLIENTE' => $cliente->IDCLIENTE]) }}">
        @csrf
        @method('PUT')
        
        <div class="mb-3">
            <label for="NOME" class="form-label">Nome</label>
            <input type="text" class="form-control" id="NOME" name="NOME" value="{{ $cliente->NOME }}">
        </div>
        <div class="mb-3">
            <label for="APELIDO" class="form-label">Apelido</label>
            <input type="text" class="form-control" id="APELIDO" name="APELIDO" value="{{ $cliente->APELIDO }}">
        </div>
        <div class="mb-3">
            <label for="ATIVO" class="form-label">Ativo</label>
            <select class="form-select" id="ATIVO" name="ATIVO">
                <option value="1" {{ $cliente->ATIVO ? 'selected' : '' }}>Sim</option>
                <option value="0" {{ !$cliente->ATIVO ? 'selected' : '' }}>Não</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="EMPRESA" class="form-label">Empresa</label>
            <input type="text" class="form-control" id="EMPRESA" name="EMPRESA" value="{{ $cliente->empresa->NOME }}" disabled>
        </div>
        <div class="text-center">
            <button type="submit" id="btnAtualizar" class="btn-s btn-suc">Atualizar</button>
            <button type="button" class="btn-ajust btn-edi" data-bs-dismiss="modal">Cancelar</button>
        </div>
        <div id="loadingIndicator" class="spinner-border" role="status" style="display: none;">
                <span class="sr-only"></span>
        </div>

    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js" integrity="sha384-HJL3+BOe2iD6DHRK0KjLDoVl6eJ8ipuLyGtLAgq8HGGi5m84pBFXd2Z8yA+4F5eD" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8sh+oL6F5f5f5k5F5eLl5d5F5t5f5R5O5y5.5G5v5Q5" crossorigin="anonymous"></script>

<script>
    $(document).ready(function () {
        $('#btnAtualizar').on('click', function () {
            $(this).hide();
            $('#loadingIndicator').show();
            setTimeout(function () {
                $('#loadingIndicator').hide();
                $('#updateForm').submit();
            }, 6000); 
        });
    });
</script>
</body>
</html>


