@extends('layout.menu')

@section('title', 'Panel Inicio')
@section('content')

<div class="container-80 d-flex flex-wrap">
    <nav class="mt-4 mb-5">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                <div>
                    <div class="d-flex flex-wrap gap-4 align-items-center">
                        <i class="bi bi-book icono"></i>
                        <div>
                            <h1 class="mb-0 fs-2">Gestión bibliotecas</h1>
                            <p class="fs-5 mb-0">Administración de bibliotecas por provincia</p>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-naranja rounded-4 d-flex align-items-center justify-content-center gap-3 px-4 py-12" data-bs-toggle="modal" data-bs-target="#loginModal">
                    <i class="bi bi-box-arrow-in-right fs-5"></i>  Nueva biblioteca</button>
            </div>
    </nav>
    <div class="row g-3 bg-white px-2 pb-3 border rounded-4 shadow-sm mb-4">
            <div class="col-12 col-xl-6 ">
                <div class="input-group rounded-4 input-focus">
                    <span class="input-group-text border-0 bg-white rounded-start-4">
                        <i class="bi bi-search fs-5 color-input"></i>
                    </span>
                    <input type="text" class="form-control border-0 rounded-end-4 py-12" placeholder="Buscar por provincia o responsable">
                </div>
            </div>
    </div>
    <!-- bibliotecas -->
    <div class="col-12 col-md-6"></div>
    <!-- modal biblioteca -->
    <div class="modal fade" id="biblioModal" tabindex="-1"
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
                            <h2 class="fs-4 fw-semibold mb-2">Nueva biblioteca</h2>
                        </div>
                            <form id="biblioForm">
                                <div class="mb-3">
                                    <label class="form-label fs-7 fw-semibold" for="provincia">Provincia</label>
                                    <div class="input-group rounded-3 input-focus">
                                        <input type="text" id="provincia" name="provincia" class="form-control border-0 rounded-end-3 py-12" placeholder="Ingresa la provincia">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fs-7 fw-semibold" for="direccion">Dirección</label>
                                    <div class="input-group rounded-3 input-focus">
                                        <input type="text" id="direccion" name="direccion" class="form-control border-0 rounded-end-3 py-12" placeholder="Ingresa la dirección">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fs-7 fw-semibold" for="telefono">Teléfono</label>
                                    <div class="input-group rounded-3 input-focus">
                                        <input type="text" id="telefono" name="telefono" class="form-control border-0 rounded-end-3 py-12" placeholder="Ingresa el teléfono">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-light rounded-3 py-2 w-100 fw-semibold">Cancelar</button>
                                <button type="submit" class="btn btn-naranja rounded-3 py-2 w-100 fw-semibold">Agregar biblioteca</button>
                            </form>
                    </div>
                </div>
            </div>
        </div>
</div>

@endsection
@section('scripts')
<script>
        let biblioModal = document.querySelector("#biblioModal");
        let biblioForm = document.querySelector("#biblioForm");
        loginModal.addEventListener("show.bs.modal",()=>{
            loginForm.reset()
        })
    </script>
@endsection