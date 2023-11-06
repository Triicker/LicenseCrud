<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Document</title>
    <style>
    .sidebar {
        position: fixed;
        top: 0;
        left: 0; 
        height: 100%;
        width: 250px;
        background-color: #2ea5b8; 
        padding-top: 60px;
    }

    .sidebar.show {
        width: 250px; 
    }

    .sidebar .list-group-item {
        border: none;
        background-color: #F8F9FA; 
        color: black; 
        cursor: pointer;
    }

    .sidebar .list-group-item:hover {
        background-color: #000;
    }

    .content {
        margin-left: 0;
        transition: margin-left 0.3s;
    }

    .content.open {
        margin-left: 250px;
    }
</style>

</head>
<body>

<nav class="navbar navbar-light bg-light">
    <div class="container-fluid">
    <button class="btn btn-link ms-auto" type="button">{{ session('userName') }}</button>
        <form method="POST" action="{{ route('auth.logout') }}">
        @csrf
        <button class="btn btn-link" type="submit">Sair</button>
    </form>
    </div>
</nav>

<div class="container-fluid content">
    <div class="container-fluid">
        <div class="row">
        <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
    <div class="position-sticky">
        <ul class="nav flex-column">
            <li class="nav-item">
            <a class="nav-link list-group-item" aria-current="page" href="{{ route('login') }}">Inicio</a>
            </li>
            <li class="nav-item">
            <a class="nav-link list-group-item" href="{{ route('clientes.index') }}" onmouseover="adicionarClasseHover(this)" onmouseout="removerClasseHover(this)">Clientes</a>
            </li>
            <li class="nav-item">
                <a class="nav-link list-group-item" href="{{ route('contatos.index') }}" onmouseover="adicionarClasseHover(this)" onmouseout="removerClasseHover(this)">Contatos</a>
            </li>
        </ul>
    </div>
</nav>

            <main class="col-md-5 ms-sm-auto col-lg-10 px-md-8">
                @yield('content')
            </main>
        </div>
    </div>
</div>


</body>
</html>
<script>
    function adicionarClasseHover(element) {
        element.classList.add('bg-dark'); 
    }

    function removerClasseHover(element) {
        element.classList.remove('bg-dark');
    }
</script>
