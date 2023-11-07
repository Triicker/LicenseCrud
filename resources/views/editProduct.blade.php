
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="container ">
    <h1 class="text-center">Editar Produto</h1>

    <form method="POST" action="{{ route('produtos.update', ['IDPRODUTO' => $produto->IDPRODUTO]) }}">
        @csrf
        @method('PUT')
        
        <div class="mb-3">
            <label for="NOME" class="form-label">Nome</label>
            <input type="text" class="form-control" id="NOME" name="NOME" value="{{ $produto->NOME }}">
        </div>
        <div class="mb-3">
            <label for="APELIDO" class="form-label">Apelido</label>
            <input type="text" class="form-control" id="APELIDO" name="APELIDO" value="{{ $produto->APELIDO }}">
        </div>
        <div class="mb-3">
            <label for="ATIVO" class="form-label">Ativo</label>
            <select class="form-select" id="ATIVO" name="ATIVO">
                <option value="1" {{ $produto->ATIVO ? 'selected' : '' }}>Sim</option>
                <option value="0" {{ !$produto->ATIVO ? 'selected' : '' }}>Não</option>
            </select>
        </div>
        <div class="text-center">
            <button type="submit" class="btn-s btn-suc">Atualizar</button>
            <a href="{{ route('produtos.index') }}" class="btn-ajust btn-edi">Cancelar</a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8sh+oL6F5f5f5k5F5eLl5d5F5t5f5R5O5y5.5G5v5Q5" crossorigin="anonymous"></script>
</body>
</html>


