<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <title>BiblioERP</title>
</head>
<body style="background-color: #fcf9ee;">
    <div class="container-95">

        <!-- Cabecera -->
        <nav class="mt-4 mb-5">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                <div>
                    <div class="d-flex flex-wrap gap-4 align-items-center">
                        <i class="bi bi-book icono"></i>
                        <div>
                            <h1 class="mb-0 fs-2">BiblioERP</h1>
                            <p class="fs-5 mb-0">Consulta Pública de Libros</p>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-naranja rounded-4 d-flex align-items-center justify-content-center gap-3 px-4 px-12" data-bs-toggle="modal" data-bs-target="#loginModal">
                    <i class="bi bi-box-arrow-in-right fs-5"></i>  Acceso Empleados</button>
            </div>
            <p>Explora nuestro cátalogo de libros disponibles en todas las bibliotecas</p>
        </nav>

        <!-- Filtros -->
        <div class="row g-3 bg-white px-2 pb-3 border rounded-4 shadow-sm mb-4">
            <div class="col-12 col-xl-6 ">
                <div class="input-group rounded-4 input-focus">
                    <span class="input-group-text border-0 bg-white rounded-start-4">
                        <i class="bi bi-search fs-5 color-input"></i>
                    </span>
                    <input type="text" class="form-control border-0 rounded-end-4 px-12" placeholder="Buscar por título, autor o ISBN...">
                </div>
            </div>
            <div class="col-12 col-md-4 col-xl-2">
                <select name="provincias" id="provincias" class="form-select px-12 px-3 rounded-4 col-2 input-focus">
                    <option value="todas">Todas las provincias</option>
                    <option value="madrid">Madrid</option>
                    <option value="barcelona">Barcelona</option>
                </select>
            </div>
            <div class="col-12 col-md-4 col-xl-2">
                <select name="categorias" id="categorias" class="form-select px-12 px-3 rounded-4 col-2 input-focus">
                    <option value="todas">Todas las categorias</option>
                    <option value="madrid">Madrid</option>
                    <option value="barcelona">Barcelona</option>
                </select>
            </div>
            <div class="col-12 col-md-4 col-xl-2">
                <select name="disponibilidad" id="disponibilidad" class="form-select px-12 ps-4 pe-5 rounded-4 col-1 input-focus">
                    <option value="todos">Todos</option>
                    <option value="disponible">Solo disponibles</option>
                    <option value="no-disponible">No disponibles</option>
                </select>
            </div>
        </div>
        <p class="mb-3">Mostrando <span class="fw-bold">7</span> resultados</p>

        <!-- Lista de libros -->
        <div class="row g-3 px-2">
           

            <!-- <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                <div class="tarjeta bg-white p-3 border rounded-3">
                    <p class="fw-semibold mb-1">Cien años de soledad</p>
                    <p class="fs-7 mb-2 ">Gabriel García Márquez</p>
                    <div class="mb-2">
                        <div class="d-flex flex-wrap gap-2 mb-2">
                            <i class="bi bi-geo-alt fs-8"></i>
                            <span class="fs-8">Biblioteca Central de Madrid</span>
                        </div>
                        <div class="d-flex flex-wrap gap-2 align-items-center ">
                            <span class="etiqueta rounded-1">Novela</span>
                            <p class="fs-8 text-body-tertiary m-0">ISBN: <span>978-84-376-0494-7</span></p>
                        </div>
                    </div>
                    <p class="no-disponible fw-semibold fs-7 py-2 m-0 text-center rounded-3">No disponible</p>
                </div>
            </div> -->
            
            
            
            
        </div>

        <!-- Modal -->

        <div class="modal fade" id="loginModal" tabindex="-1"
            data-bs-backdrop="static"
            data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-login">
                <div class="modal-content rounded-4">
                    <div class="modal-header border-0 p-3">
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body px-4 pb-5 pt-0">
                        <div class="text-center mb-4">
                            <div class="d-flex justify-content-center align-items-center mb-3">
                                <div class="  rounded-circle logo-modal">
                                    <i class="bi bi-book fs-1 icono text-white"></i>
                                </div>
                            </div>
                            <h2 class="fs-4 fw-semibold mb-2">Iniciar Sesión</h2>
                            <p>Acceso para empleados de BiblioERP</p>
                        </div>
                            <form id="loginForm">
                                <div class="mb-3">
                                    <label class="form-label fs-7 fw-semibold" for="usuario">Usuario</label>
                                    <div class="input-group rounded-3 input-focus">
                                        <span class="input-group-text border-0 bg-white rounded-start-3">
                                            <i class="bi bi-person fs-5 color-input"></i>
                                        </span>
                                        <input type="text" id="usuario" name="usuario" class="form-control border-0 rounded-end-3 px-12" placeholder="Ingresa tu usuario">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fs-7 fw-semibold" for="password">Contraseña</label>
                                    <div class="input-group rounded-3 input-focus">
                                        <span class="input-group-text border-0 bg-white rounded-start-3">
                                            <i class="bi bi-lock fs-5 color-input"></i>
                                        </span>
                                        <input type="text" id="password" name="password" class="form-control border-0 rounded-end-3 px-12" placeholder="Ingresa tu usuario">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-naranja rounded-3 py-2 w-100 fw-semibold">Iniciar Sesión</button>
                            </form>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

    <script>
        let loginModal = document.querySelector("#loginModal");
        let loginForm = document.querySelector("#loginForm");
        loginModal.addEventListener("show.bs.modal",()=>{
            loginForm.reset()
        })
    </script>
</body>
</html>