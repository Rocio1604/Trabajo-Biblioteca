menu layout
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    

    <title>@yield('title')</title>
</head>
<body>
    <div class="container-fluid">
        <div class="row">

            <nav id="sidebarMenu" class="col-lg-2 sidebar vh-100 offcanvas-lg offcanvas-start text-white position-fixed">
                <div class="offcanvas-header d-lg-none">
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu"></button>
                </div>
                <div class="offcanvas-body flex-column">
                    <!-- Cabecera menu -->
                    <div class="d-flex flex-wrap gap-3 align-items-center px-3 sidebar-borde sidebar-header-prop">
                        <i class="bi bi-book icono fs-1"></i>
                        <div>
                            <h1 class="mb-0 fs-5">BiblioERP</h1>
                            <p class="fs-8 mb-0 text-white-50">Sistema de gestión</p>
                        </div>
                    </div>

                    <!-- Cuerpo menu -->
                    <div class="p-3 sidebar-borde sidebar-body-prop">
                        <button type="button" class="btn btn-sidebar activo rounded-3 d-flex gap-3 px-3 py-12 w-100 fw-semibold fs-7 mb-1">
                        <i class="bi bi-grid-1x2"></i>Panel de inicio</button>
                        <button type="button" class="btn btn-sidebar rounded-3 d-flex gap-3 px-3 py-12 w-100 fw-semibold fs-7 mb-1">
                        <i class="bi bi-building"></i>Bibliotecas</button>
                        <button type="button" class="btn btn-sidebar rounded-3 d-flex gap-3 px-3 py-12 w-100 fw-semibold fs-7 mb-1">
                        <i class="bi bi-person-gear"></i>Trabajadores</button>
                        <button type="button" class="btn btn-sidebar rounded-3 d-flex gap-3 px-3 py-12 w-100 fw-semibold fs-7 mb-1">
                        <i class="bi bi-people"></i>Socios</button>
                        <button type="button" class="btn btn-sidebar rounded-3 d-flex gap-3 px-3 py-12 w-100 fw-semibold fs-7 mb-1">
                        <i class="bi bi-journal-bookmark"></i>Libros</button>
                        <button type="button" class="btn btn-sidebar rounded-3 d-flex gap-3 px-3 py-12 w-100 fw-semibold fs-7 mb-1">
                        <i class="bi bi-person-up"></i>Autores</button>
                        <button type="button" class="btn btn-sidebar rounded-3 d-flex gap-3 px-3 py-12 w-100 fw-semibold fs-7 mb-1">
                        <i class="bi bi-arrow-left-right"></i>Prétamos</button>
                        <button type="button" class="btn btn-sidebar rounded-3 d-flex gap-3 px-3 py-12 w-100 fw-semibold fs-7 mb-1">
                        <i class="bi bi-file-earmark-text"></i>Recibos</button>
                    </div>

                    <!-- Cuenta menu -->
                    <div class="p-3 sidebar-borde sidebar-footer-prop">
                        <div class="mb-2">
                            <h1 class="mb-0 fs-7">Ana García</h1>
                            <p class="fs-8 mb-0 text-white-50">Admin</p>
                        </div>
                        <button type="button" class="btn text-white rounded-4 p-0 d-flex align-items-center justify-content-center gap-2 fs-7 ">
                        <i class="bi bi-box-arrow-right fs-7"></i>Cerrar Sesión</button>
                    </div>
                </div>
            </nav>

            <div class="col-lg-10 ms-lg-auto p-4">
                <body>
                    <button class="btn btn-primary d-lg-none m-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
                    <i class="bi bi-list"></i>
                </button>
                <h1>Contenido Principal</h1>
                <p>Cuerpo</p>
            </div>
        </div>
    </div>
    <script>
        let botonesSidebar = document.querySelectorAll(".sidebar-body-prop .btn-sidebar");
        botonesSidebar.forEach(boton=>{
            boton.addEventListener("click",()=>{
                botonesSidebar.forEach(b=>{
                    b.classList.remove("activo")
                })
                boton.classList.add("activo")
            })
        })
    </script>
     @yield('content')
    @yield('scripts')
</body>
</html>
