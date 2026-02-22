<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.4.6/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <style>
        a{
            color:white;
            text-decoration: none;
        }
    </style>

    <title>@yield('title')</title>
</head>
<body>
    <div class="container-fluid">
        <div class="row">

            <nav id="sidebarMenu" class="col-xl-2 sidebar vh-100 offcanvas-xl offcanvas-start text-white position-fixed">
                <div class="offcanvas-header d-xl-none">
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
                        <a href="{{route('panelinicio')}}" class="btn btn-sidebar {{ Route::is('panelinicio') ? 'activo' : '' }} rounded-3 d-flex gap-3 px-3 py-12 w-100 fw-semibold fs-7 mb-1 text-decoration-none">
                            <i class="bi bi-grid-1x2"></i>
                            <span>Panel inicio</span>
                        </a>
                        <a href="{{route('biblio.index')}}" class="btn btn-sidebar {{ Route::is('biblio.*') ? 'activo' : '' }} rounded-3 d-flex gap-3 px-3 py-12 w-100 fw-semibold fs-7 mb-1 text-decoration-none">
                            <i class="bi bi-building"></i>
                            <span>Bibliotecas</span>
                        </a>
                        @can('admin')
                        <a href="{{route('usuario.index')}}" class="btn btn-sidebar {{ Route::is('usuario.*') ? 'activo' : '' }} rounded-3 d-flex gap-3 px-3 py-12 w-100 fw-semibold fs-7 mb-1 text-decoration-none">
                            <i class="bi bi-person-gear"></i>
                            <span>Usuarios</span>
                        </a>
                        @endcan
                        <a href="{{ route('socio.index') }}" class="btn btn-sidebar {{ Route::is('socio.*') ? 'activo' : '' }} rounded-3 d-flex gap-3 px-3 py-12 w-100 fw-semibold fs-7 mb-1 text-decoration-none">
                            <i class="bi bi-people"></i>
                            <span>Socios</span>
                        </a>
                        <a href="{{route('libros.index')}}" class="btn btn-sidebar {{ Route::is('libros.*') ? 'activo' : '' }} rounded-3 d-flex gap-3 px-3 py-12 w-100 fw-semibold fs-7 mb-1 text-decoration-none">
                            <i class="bi bi-journal-bookmark"></i>
                            <span>Libros</span>
                        </a>
                        <a href="{{route('ejemplares.index')}}" class="btn btn-sidebar {{ Route::is('ejemplares.*') ? 'activo' : '' }} rounded-3 d-flex gap-3 px-3 py-12 w-100 fw-semibold fs-7 mb-1 text-decoration-none">
                            <i class="bi bi-archive"></i>
                            <span>Ejemplares</span>
                        </a>
                        <a href="{{route('autor.index')}}" class="btn btn-sidebar {{ Route::is('autor.*') ? 'activo' : '' }} rounded-3 d-flex gap-3 px-3 py-12 w-100 fw-semibold fs-7 mb-1 text-decoration-none">
                            <i class="bi bi-person-up"></i>
                            <span>Autores</span>
                        </a>
                        <a href="{{route('prestamo.index')}}" class="btn btn-sidebar {{ Route::is('prestamo.*') ? 'activo' : '' }} rounded-3 d-flex gap-3 px-3 py-12 w-100 fw-semibold fs-7 mb-1 text-decoration-none">
                            <i class="bi bi-arrow-left-right"></i>
                            <span>Préstamos</span>
                        </a>
                        <a href="{{route('recibo.index')}}" class="btn btn-sidebar {{ Route::is('recibo.*') ? 'activo' : '' }} rounded-3 d-flex gap-3 px-3 py-12 w-100 fw-semibold fs-7 mb-1 text-decoration-none">
                            <i class="bi bi-file-earmark-text"></i>
                            <span>Recibos</span>
                        </a>
                    </div>

                    <!-- Cuenta menu -->
                    <div class="p-3 sidebar-borde sidebar-footer-prop">
                        <div class="mb-2">
                            <h1 class="mb-0 fs-7">{{ auth()->user()->nombre ?? 'Sin nombre' }}</h1>
                            <p class="fs-8 mb-0 text-white-50">{{ auth()->user()->rol?->nombre ?? 'Sin Rol'}}</p>
                            <div class="small text-warning mt-2">
                                <i class="bi bi-geo-alt"></i> 
                                {{ auth()->user()->biblioteca?->nombre ?? 'Administrador' }}
                            </div>
                        </div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn text-white rounded-4 p-0 d-flex align-items-center justify-content-center gap-2 fs-7 ">
                            <i class="bi bi-box-arrow-right fs-7"></i>Cerrar Sesión</button>
                        </form>
                        
                    </div>
                </div>
            </nav>

            <div style="height: 80px; background-color: transparent; background-color: #292524;" class="d-xl-none d-flex align-items-center justify-content-between px-3">
                    <button class="btn btn-naranja  m-2 border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
                        <i class="bi bi-list"></i>
                    </button>
                    <div class="d-flex flex-wrap gap-3 align-items-center px-3 sidebar-borde sidebar-header-prop">
                        <i class="bi bi-book icono fs-1" ></i>
                        <div>
                            <h1 class="mb-0 fs-5 text-white">BiblioERP</h1>
                            <p class="fs-8 mb-0 text-white-50">Sistema de gestión</p>
                        </div>
                    </div>
                </div>

            <div class="col-xl-10 ms-xl-auto p-4 bg-light vh-100 overflow-auto ">
                    
                @yield('content')
            </div>
        </div>
    </div>
    <script>
        function confirmarReactivar(id,mensaje,url) {
            Swal.fire({
                title: '¿Reactivar ' + mensaje + '?',
                text: "El " + mensaje + " volverá a estar disponible.",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                confirmButtonText: 'Sí, reactivar',
                cancelButtonText: 'Cancelar',
                customClass: { popup: 'rounded-4' }
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '/' + url + '/reactivar/' + id;
                    form.innerHTML = `@csrf`;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
        function confirmarEliminar(id,mensaje,url,body="") {
            Swal.fire({
                title:mensaje=="recibo"?'¿Anular recibo?': '¿Desactivar ' + mensaje + '?',
                text:body,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ff8000',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, desactivar',
                cancelButtonText: 'Cancelar',
                customClass: { popup: 'rounded-4' }
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '/' + url + '/eliminar/' + id;
                    form.innerHTML = `@csrf`;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.4.6/dist/js/tom-select.complete.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Alerta de exito -->
    @if(session('success'))
        <script>
            Swal.fire({
                title: '¡Éxito!',
                text: '{{ session("success") }}',
                icon: 'success',
                confirmButtonColor: '#ff8000',
                confirmButtonText: 'Aceptar',
                customClass: {
                    popup: 'rounded-4',
                }
            });
        </script>
    @endif
    <!-- Errores de base -->
    @if(session('error'))
    <script>
        Swal.fire({
            title: 'Error Crítico',
            text: '{{ session("error") }}',
            icon: 'error',
            confirmButtonColor: '#ff8000'
        });
    </script>
    @endif
    @yield('scripts')
</body>
</html>
