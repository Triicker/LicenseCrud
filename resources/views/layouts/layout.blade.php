<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/layout_empresa1.css') }}">

    <?php

$IDEMPRESA = session('IDEMPRESA'); 

if ($IDEMPRESA === 1) {
    $logoUrlFromDatabase = "/css/img_svg/icon-teste-worknow.png";
    $cor_menu_superior = "#14efca ";
    $cor_menu_lateral = "#14efca ";
    $cor_hover_menu_lateral = "#7d65ec ";
    $cor_fundo_logo_menu = "#ffffff";
    $cor_fonte_menu_lateral = "#7d65ec ";
    $cor_hover_menu_sup = "#7d65ec ";
    $cor_fonte_icon_lateral = "#ffffff";
    $cor_fonte_icon_sup = "#ffffff";
} elseif ($IDEMPRESA === 2) {
    $logoUrlFromDatabase = "/css/img_svg/icon-worknow-250px_40px.png"; 
    $cor_menu_superior = "#7d65ec ";
    $cor_menu_lateral = "#7d65ec ";
    $cor_hover_menu_lateral = "#14efca";
    $cor_fundo_logo_menu = "#ffffff";
    $cor_fonte_menu_lateral = "#14efca";
    $cor_hover_menu_sup = "#14efca";
    $cor_fonte_icon_lateral = "#ffffff";
    $cor_fonte_icon_sup = "#ffffff";
} else {
    $logoUrlFromDatabase = "/css/img_svg/icon-worknow-250px_40px.png"; 

}

?>

    <title>Document</title>

    <style>
        .bg-light-menu-sup {
            background-color:  <?php echo $cor_menu_superior; ?> !important;
        }
        .bg-light-menu-lat {
            background-color: <?php echo $cor_menu_lateral ?> !important;
        }
        .sidebar .list-group-item:hover {
            background-color: <?php echo $cor_hover_menu_lateral  ?> !important;
        }
        .logo-worknow {
            background-color: <?php echo $cor_fundo_logo_menu  ?> !important;
        }
        .navbar .btn-sup:hover {
            background-color: <?php echo  $cor_hover_menu_sup  ?> !important;
        }
        .color-if-menu {
            fill: <?php echo $cor_fonte_icon_lateral  ?> !important;
        }
        .position-sticky a {
            color: <?php echo $cor_fonte_icon_lateral  ?> !important;
        }
        .sidebar .list-group-item {
            background-color: echo <?php  $cor_menu_lateral  ?> !important;
        }
        .cor-if-menu-sup {
            fill: <?php echo $cor_fonte_icon_sup  ?> !important;
        }
        .cor-if-menu-sup-2 {
            stroke: <?php echo $cor_fonte_icon_sup  ?> !important;
        }
        .containerfluid button {
            color: <?php echo $cor_fonte_icon_sup  ?> !important;
        }
        .containerfluid a {
            color: <?php echo $cor_fonte_icon_sup  ?> !important;
        }
        .icon-div {
            background-image: url("<?php echo $logoUrlFromDatabase; ?>");
        }

        .footer {
        background-color: <?php echo $cor_menu_lateral ?> !important;
        }
    </style>
</head>

<body>
<nav class="navbar navbar-light bg-light-menu-sup">
    <div class="containerfluid">
    <button class="btn-btn btn-sup mg-right" type="button">{{ session('userName') }}</button>
    <button  type="button" hidden>{{ session('empresaNome') }}</button>
    <button  type="button" hidden>{{ session('IDEMPRESA') }}</button>
    <button  type="button" hidden>{{ session('IDUSUARIO') }}</button>

        <form method="POST" action="{{ route('auth.logout') }}">
        @csrf
        <button class="btn-sup" type="submit"><svg class="nav-img mg-right mg-top-i" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<g id="icon-exit">
<g id="Group">
<path id="Vector" fill-rule="evenodd" clip-rule="evenodd" d="M10.654 3.10428C11.0069 2.99352 11.3797 2.97061 11.7426 3.03737C12.1055 3.10414 12.4484 3.25873 12.744 3.48881C13.0396 3.71889 13.2797 4.01809 13.4452 4.36252C13.6106 4.70695 13.6968 5.08708 13.6969 5.47256V17.5274C13.6968 17.9129 13.6106 18.293 13.4452 18.6374C13.2797 18.9819 13.0396 19.2811 12.744 19.5112C12.4484 19.7412 12.1055 19.8958 11.7426 19.9626C11.3797 20.0294 11.0069 20.0064 10.654 19.8957L5.9267 18.4119C5.43986 18.2591 5.01307 17.9462 4.70963 17.5196C4.40619 17.093 4.24227 16.5754 4.24219 16.0436V6.95633C4.24227 6.42456 4.40619 5.90698 4.70963 5.48037C5.01307 5.05377 5.43986 4.74086 5.9267 4.58806L10.654 3.10428ZM14.4848 4.90542C14.4848 4.6868 14.5678 4.47713 14.7155 4.32254C14.8633 4.16795 15.0637 4.0811 15.2727 4.0811H17.6363C18.2632 4.0811 18.8644 4.34165 19.3077 4.80542C19.751 5.26919 20 5.89819 20 6.55406V7.37838C20 7.59701 19.917 7.80668 19.7692 7.96127C19.6215 8.11586 19.4211 8.2027 19.2121 8.2027C19.0031 8.2027 18.8027 8.11586 18.655 7.96127C18.5072 7.80668 18.4242 7.59701 18.4242 7.37838V6.55406C18.4242 6.33544 18.3412 6.12577 18.1935 5.97118C18.0457 5.81659 17.8453 5.72974 17.6363 5.72974H15.2727C15.0637 5.72974 14.8633 5.6429 14.7155 5.48831C14.5678 5.33372 14.4848 5.12405 14.4848 4.90542ZM19.2121 14.7973C19.4211 14.7973 19.6215 14.8841 19.7692 15.0387C19.917 15.1933 20 15.403 20 15.6216V16.4459C20 17.1018 19.751 17.7308 19.3077 18.1946C18.8644 18.6583 18.2632 18.9189 17.6363 18.9189H15.2727C15.0637 18.9189 14.8633 18.832 14.7155 18.6774C14.5678 18.5228 14.4848 18.3132 14.4848 18.0945C14.4848 17.8759 14.5678 17.6663 14.7155 17.5117C14.8633 17.3571 15.0637 17.2702 15.2727 17.2702H17.6363C17.8453 17.2702 18.0457 17.1834 18.1935 17.0288C18.3412 16.8742 18.4242 16.6645 18.4242 16.4459V15.6216C18.4242 15.403 18.5072 15.1933 18.655 15.0387C18.8027 14.8841 19.0031 14.7973 19.2121 14.7973ZM9.75742 10.6757C9.54846 10.6757 9.34806 10.7625 9.2003 10.9171C9.05254 11.0717 8.96953 11.2814 8.96953 11.5C8.96953 11.7186 9.05254 11.9283 9.2003 12.0829C9.34806 12.2375 9.54846 12.3243 9.75742 12.3243H9.75821C9.96717 12.3243 10.1676 12.2375 10.3153 12.0829C10.4631 11.9283 10.5461 11.7186 10.5461 11.5C10.5461 11.2814 10.4631 11.0717 10.3153 10.9171C10.1676 10.7625 9.96717 10.6757 9.75821 10.6757H9.75742Z" class="cor-if-menu-sup"/>
<path id="Vector_2" d="M15.2695 11.5002H19.209M19.209 11.5002L17.6332 9.85156M19.209 11.5002L17.6332 13.1488" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="cor-if-menu-sup-2"/>
</g>
</g>
</svg>
</button>
    </form>
    </div>
</nav>

<div class="container-fluid content">
    <div class="container-fluid">
        
        <div class="row">
        <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light-menu-lat sidebar">

        <div class="logo-worknow">
        <div class="icon-div"></div>
        </div>

    <div class="position-sticky">
        <ul class="nav flex-column">

        <li class="nav-item">
            <a class="nav-link list-group-item icon-home" aria-current="page" href="{{ route('login') }}">
                <svg class="nav-img mg-right mg-top-i" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
    <g id="icone-home">
        <path id="icon-home" d="M20.0012 38.0004V28.0004H28.0012V38.0004C28.0012 39.1004 28.9012 40.0004 30.0012 40.0004H36.0012C37.1012 40.0004 38.0012 39.1004 38.0012 38.0004V24.0004H41.4012C42.3212 24.0004 42.7612 22.8604 42.0612 22.2604L25.3412 7.20043C24.5812 6.52043 23.4212 6.52043 22.6612 7.20043L5.94117 22.2604C5.26117 22.8604 5.68117 24.0004 6.60117 24.0004H10.0012V38.0004C10.0012 39.1004 10.9012 40.0004 12.0012 40.0004H18.0012C19.1012 40.0004 20.0012 39.1004 20.0012 38.0004" class="color-if-menu"/>
    </g>
</svg>
Inicio</a>
            </li>
            <li class="nav-item">
            <a class="nav-link list-group-item" href="{{ route('clientes.index') }}" onmouseover="adicionarClasseHover(this)" onmouseout="removerClasseHover(this)">
            <svg class="nav-img mg-right mg-top-i" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<g id="mdi:users">
<path id="Vector" d="M16 17.0001V19.0001H2V17.0001C2 17.0001 2 13.0001 9 13.0001C16 13.0001 16 17.0001 16 17.0001ZM12.5 7.50005C12.5 6.80782 12.2947 6.13113 11.9101 5.55556C11.5256 4.97998 10.9789 4.53138 10.3394 4.26647C9.69985 4.00157 8.99612 3.93226 8.31718 4.0673C7.63825 4.20235 7.01461 4.5357 6.52513 5.02518C6.03564 5.51466 5.7023 6.1383 5.56725 6.81724C5.4322 7.49617 5.50152 8.1999 5.76642 8.83944C6.03133 9.47899 6.47993 10.0256 7.0555 10.4102C7.63108 10.7948 8.30777 11.0001 9 11.0001C9.92826 11.0001 10.8185 10.6313 11.4749 9.97493C12.1313 9.31855 12.5 8.42831 12.5 7.50005ZM15.94 13.0001C16.5547 13.4758 17.0578 14.0805 17.4137 14.7716C17.7696 15.4626 17.9697 16.2233 18 17.0001V19.0001H22V17.0001C22 17.0001 22 13.3701 15.94 13.0001ZM15 4.00005C14.3117 3.99622 13.6385 4.20201 13.07 4.59005C13.6774 5.43879 14.0041 6.45634 14.0041 7.50005C14.0041 8.54377 13.6774 9.56132 13.07 10.4101C13.6385 10.7981 14.3117 11.0039 15 11.0001C15.9283 11.0001 16.8185 10.6313 17.4749 9.97493C18.1313 9.31855 18.5 8.42831 18.5 7.50005C18.5 6.57179 18.1313 5.68156 17.4749 5.02518C16.8185 4.3688 15.9283 4.00005 15 4.00005Z" class="color-if-menu"/>
</g>
</svg>
Clientes</a>
            </li>
            <li class="nav-item">
                <a class="nav-link list-group-item" href="{{ route('produtos.index') }}" onmouseover="adicionarClasseHover(this)" onmouseout="removerClasseHover(this)">
                <svg class="nav-img mg-right mg-top-i" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<g id="mdi:contact">
<path id="Vector" d="M6 17C6 15 10 13.9 12 13.9C14 13.9 18 15 18 17V18H6M15 9C15 9.79565 14.6839 10.5587 14.1213 11.1213C13.5587 11.6839 12.7956 12 12 12C11.2044 12 10.4413 11.6839 9.87868 11.1213C9.31607 10.5587 9 9.79565 9 9C9 8.20435 9.31607 7.44129 9.87868 6.87868C10.4413 6.31607 11.2044 6 12 6C12.7956 6 13.5587 6.31607 14.1213 6.87868C14.6839 7.44129 15 8.20435 15 9ZM3 5V19C3 19.5304 3.21071 20.0391 3.58579 20.4142C3.96086 20.7893 4.46957 21 5 21H19C19.5304 21 20.0391 20.7893 20.4142 20.4142C20.7893 20.0391 21 19.5304 21 19V5C21 4.46957 20.7893 3.96086 20.4142 3.58579C20.0391 3.21071 19.5304 3 19 3H5C4.46957 3 3.96086 3.21071 3.58579 3.58579C3.21071 3.96086 3 4.46957 3 5Z" class="color-if-menu"/>
</g>
</svg>
Produtos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link list-group-item" href="{{ route('logs.index') }}" onmouseover="adicionarClasseHover(this)" onmouseout="removerClasseHover(this)">
                <svg class="nav-img mg-right mg-top-i" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<g id="mdi:contact">
<path id="Vector" d="M6 17C6 15 10 13.9 12 13.9C14 13.9 18 15 18 17V18H6M15 9C15 9.79565 14.6839 10.5587 14.1213 11.1213C13.5587 11.6839 12.7956 12 12 12C11.2044 12 10.4413 11.6839 9.87868 11.1213C9.31607 10.5587 9 9.79565 9 9C9 8.20435 9.31607 7.44129 9.87868 6.87868C10.4413 6.31607 11.2044 6 12 6C12.7956 6 13.5587 6.31607 14.1213 6.87868C14.6839 7.44129 15 8.20435 15 9ZM3 5V19C3 19.5304 3.21071 20.0391 3.58579 20.4142C3.96086 20.7893 4.46957 21 5 21H19C19.5304 21 20.0391 20.7893 20.4142 20.4142C20.7893 20.0391 21 19.5304 21 19V5C21 4.46957 20.7893 3.96086 20.4142 3.58579C20.0391 3.21071 19.5304 3 19 3H5C4.46957 3 3.96086 3.21071 3.58579 3.58579C3.21071 3.96086 3 4.46957 3 5Z" class="color-if-menu"/>
</g>
</svg>
Logs</a>
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

<footer class="footer">
        <p> Todos os direitos reservados à 2023 &copy; Worknow</p>
    </footer>
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
