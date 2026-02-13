<!DOCTYPE html>
<html lang="en">
@extends('layout.menu')
@section('title', 'Ejemplares')
@section('content')
<div class="mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <div>
            <h1 class="fs-2 mb-2">Gestión de Ejemplares</h1>
            <p class="m-0">Catálogo completo de ejemplares del sistema</p>
        </div>
        <button type="button" class="btn btn-naranja rounded-3 d-flex align-items-center justify-content-center gap-2 px-3 py-2" data-bs-toggle="modal" data-bs-target="#registroModal">
                        <i class="bi bi-plus-lg fs-5"></i>Nuevo Ejemplar</button>
    </div>
    <div class="row g-3">
        <div class="col-12 col-xl-4">
            <div class="input-group rounded-4 input-focus">
                <span class="input-group-text border-0 bg-white rounded-start-4 bg-transparent">
                    <i class="bi bi-search fs-5 color-input"></i>
                </span>
                <input type="text" class="form-control border-0 rounded-end-4 py-2 bg-transparent" placeholder="Buscar por titulo, autor o ISBN...">
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-2">
            <select name="categoria" id="categoria" class="form-select py-2 px-3 rounded-4 col-2 input-focus bg-transparent">
                <option value="todas">Todos las categorias</option>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}">
                        {{ $categoria->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-md-6 col-xl-2">
            <select name="estado" id="estado" class="form-select py-2 px-3 rounded-4 col-2 input-focus bg-transparent">
                <option value="todas">Cualquier Estado</option>
                @foreach($estados as $estado)
                    <option value="{{ $estado->id }}">
                        {{ $estado->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-md-6 col-xl-2">
            <select name="categoria_id" id="categoria_id"  class="w-100 input-focus bg-transparent " placeholder="Seleccione una categoría" >
                <option value="" selected disabled>Seleccione una categoría</option> 
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}">
                        {{ $categoria->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-md-6  col-xl-2">
            <select name="activo" id="activo" class="form-select py-2 px-3 rounded-4 col-2 input-focus bg-transparent">
                <option value="todas">Todos los libros</option>
                <option value="activos">Solo activos</option>
                <option value="inactivos">Solo inactivos</option>
            </select>
        </div>  
    </div>
</div>

<div class="border rounded-4 overflow-hidden shadow-sm">
    <table class="table mb-0 align-middle">
        <thead class="table-light">
            <tr>
                <th class="px-4 py-12">LIBRO</th>
                <th class="px-4 py-12">AUTORES</th>
                <th class="px-4 py-12">CATEGORÍA</th>
                <th class="px-4 py-12">BIBLIOTECA</th>
                <th class="px-4 py-12">ESTADO</th>
                <th class="px-4 py-12">DISPONIBILIDAD</th>
                <th class="px-4 py-12">¿ACTIVO?</th>
                <th class="px-4 py-12">ACCIONES</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ejemplares as $ejemplar)
            <tr>
                <td class="px-4 py-3">
                    <div>
                        <p class="m-0 fw-semibold">{{ $ejemplar->libro->titulo }}</p>
                        <p class="m-0 fs-7">{{ $ejemplar->libro->isbn }}</p>
                    </div>
                </td>
                <td class="px-4 py-3 fs-7">
                    {{ $ejemplar->libro->autores->pluck('nombre')->implode(', ') }}
                </td>
                <td class="px-4 py-3 fs-7">{{ $ejemplar->libro->categoria->nombre }}</td>
                <td class="px-4 py-3 fs-7">{{ $ejemplar->biblioteca->nombre }}</td>
                <td class="px-4 py-3 fs-7">
                    @switch($ejemplar->estado->id)
                        @case(1)
                            <div class="d-flex flex-wrap align-items-center disponible rounded-3 px-2 py-1 gap-1 fw-semibold" style="width: fit-content;">
                                <span class="fs-8">{{ $ejemplar->estado->nombre }}</span>
                            </div>
                            @break
                        @case(2)
                        <!-- color azul en letra y de fondo azul claro -->
                             <div class="d-flex flex-wrap align-items-center etiqueta-azul rounded-3 px-2 py-1 gap-1 fw-semibold" style="width: fit-content;">
                                <span class="fs-8">{{ $ejemplar->estado->nombre }}</span>
                            </div>
                            @break
                        @case(3)
                        <!-- color naranja en letra y de fondo naranja claro -->
                             <div class="d-flex flex-wrap align-items-center etiqueta-naranja rounded-3 px-2 py-1 gap-1 fw-semibold" style="width: fit-content;">
                                <span class="fs-8">{{ $ejemplar->estado->nombre }}</span>
                            </div>
                            @break
                        @case(4)
                        <!-- color naranja en letra y de fondo naranja claro -->
                             <div class="d-flex flex-wrap align-items-center no-disponible rounded-3 px-2 py-1 gap-1 fw-semibold" style="width: fit-content;">
                                <span class="fs-8">{{ $ejemplar->estado->nombre }}</span>
                            </div>
                            @break

                    @endswitch
                </td>
                <td class="px-4 py-3">
                    <!-- color verde con icono de caja -->
                    @if($ejemplar->disponibilidad->id === 1)
                        <div class="d-flex flex-wrap align-items-center disponible rounded-3 px-2 py-1 gap-1 fw-semibold" style="width: fit-content;">
                            <i class="bi bi-check2-circle fs-8"></i>
                            <span class="fs-8">Disponible</span>
                        </div>
                    @endif
                    <!-- color gris -->
                    @if($ejemplar->disponibilidad->id === 2)   
                        <div class="d-flex flex-wrap align-items-center etiqueta-gris rounded-3 px-2 py-1 gap-1 fw-semibold" style="width: fit-content;">
                            <i class="bi bi-x-circle fs-8"></i>
                            <span class="fs-8">No disponible</span>
                        </div>
                    @endif
                </td>
                <td class="px-4 py-3">
                    <!-- color verde con icono de caja -->
                    @if($ejemplar->es_activo)
                        Sí
                    @else
                        No
                    @endif
                </td>
                <td class="px-4 py-3">
                    <div class="d-flex wrap-flex gap-4">
                        @if($ejemplar->es_activo)
                            <button class="bg-transparent border-0" data-bs-toggle="modal" 
                                    data-bs-target="#registroModal"
                                    data-id="{{ $ejemplar->id }}"
                                    data-libro="{{ $ejemplar->libro->id }}"
                                    data-biblioteca="{{ $ejemplar->biblioteca->id }}"
                                    data-estado="{{ $ejemplar->estado->id }}">
                                <i class="bi bi-pencil-square icono-editar"></i>
                            </button>
                            <button class="bg-transparent border-0 " onclick="confirmarEliminar('{{ $ejemplar->id }}')">
                                <i class="bi bi-trash icono-eliminar"></i>
                            </button>
                        @else
                            <button class="bg-transparent border-0 " onclick="reactivarEjemplar('{{ $ejemplar->id }}')">
                                <i class="bi bi-arrow-counterclockwise text-success"></i>
                            </button>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>


<!-- Modal -->

<div class="modal fade" id="registroModal" tabindex="-1"
    data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-header border-0 p-3">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 pb-4 pt-0">
                    <h2 class="fs-4 fw-semibold mb-2 titulo-modal mb-2" id="modalTitle">Nuevo Ejemplar</h2>
                    <form id="registerForm"  method="POST" class="d-flex flex-column gap-3">
                        @csrf
                        <input type="hidden" id="editing_id" name="editing_id" value="{{ old('editing_id') }}">
                        <div class="row gx-3">
                            <div class="col-6">
                                <label for="libro_id" class="fs-7 icono-editar fw-semibold mb-1">Libro</label>
                                <select name="libro_id" id="libro_id"  class="w-100 input-focus bg-transparent @error('libro_id') is-invalid @enderror" placeholder="Seleccione un libro">
                                    <option value="" selected disabled>Seleccione un libro</option> 
                                    @foreach($libros as $libro)
                                        <option value="{{ $libro->id }}" data-isbn="{{ $libro->isbn }}" {{ old('libro_id') == $libro->id ? 'selected' : '' }} >
                                            {{ $libro->titulo }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('libro_id')
                                    <div class="invalid-feedback fs-8">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-6">
                                <label for="biblioteca_id" class="fs-7 icono-editar fw-semibold mb-1 mt-0">Biblioteca</label>
                                <select name="biblioteca_id" id="biblioteca_id"  class="form-select py-2 px-3 rounded-3 col-2 input-focus bg-transparent @error('biblioteca_id') is-invalid @enderror">
                                    <option value="" selected disabled>Seleccione una biblioteca</option>
                                    @foreach($bibliotecas as $biblioteca)
                                        <option value="{{ $biblioteca->id }}" {{ old('biblioteca_id') == $biblioteca->id ? 'selected' : '' }}>
                                            {{ $biblioteca->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('biblioteca_id')
                                    <div class="invalid-feedback fs-8">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="estado_id" class="fs-7 icono-editar fw-semibold mb-1">Estado</label>
                            <select name="estado_id" id="estado_id" class="form-select py-2 px-3 rounded-4 col-2 input-focus bg-transparent @error('estado_id') is-invalid @enderror">
                                    <option value="" selected disabled>Seleccione un estado</option>
                                @foreach($estados as $estado)
                                    <option value="{{ $estado->id }}" {{ old('estado_id') == $estado->id ? 'selected' : '' }}>
                                        {{ $estado->nombre }}
                                    </option>
                                @endforeach
                            </select>
                                @error('estado_id')
                                    <div class="invalid-feedback fs-8">{{ $message }}</div>
                                @enderror
                        </div>
                        <div class="row g-3">
                            <div class="col-6">
                                <button type="button" class="w-100 btn bg-transparent border rounded-3 px-4 py-2" data-bs-dismiss="modal">Cancelar</button>
                            </div>
                           <div class="col-6">
                                <button type="submit" class="w-100 btn btn-naranja rounded-3 px-4 py-2" id="btnModal">Registrar</button>
                           </div>
                        </div>
                    </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <script>
        let modalRegistrar = document.querySelector("#registroModal");
        let registerForm = document.querySelector("#registerForm");
        let modalTitle = document.getElementById('modalTitle');
        let btnModal = document.getElementById('btnModal');

        modalRegistrar.addEventListener('show.bs.modal',(event)=>{
            let boton = event.relatedTarget;
            if (boton.hasAttribute('data-id')) {
                let id = boton.getAttribute('data-id');
                console.log(id);
                modalTitle.textContent = 'Editar ejemplar';
                btnModal.textContent = 'Actualizar';
                registerForm.action = '/ejemplares/editar/' + id;
                document.getElementById('editing_id').value = id;

                 
                libroSelect.clear(); 
                libroSelect.setValue(boton.getAttribute('data-libro'));
                document.getElementById('biblioteca_id').value = boton.getAttribute('data-biblioteca');
                document.getElementById('estado_id').value = boton.getAttribute('data-estado');
            } else if (boton){
                modalTitle.textContent = 'Nuevo ejemplar';
                btnModal.textContent = 'Registrar';
                registerForm.action = "{{ route('ejemplares.store') }}";
                document.getElementById('biblioteca_id').value = "";
                document.getElementById('estado_id').value = "";
                registerForm.reset();
            }
        })

        modalRegistrar.addEventListener('hidden.bs.modal', () => {
            libroSelect.clear(); 
            registerForm.reset();
            registerForm.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        });

        function confirmarEliminar(id) {
            Swal.fire({
                title: '¿Desactivar ejemplar?',
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
                    form.action = '/ejemplares/eliminar/' + id;
                    form.innerHTML = `@csrf`;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        let categoriaSelect = new TomSelect("#categoria_id", {
            create: false,
            maxItems: 1, 
            persist: false,
            plugins: ['clear_button'], 
            render: {
                no_results: function(data, escape) {
                    return '<div class="no-results p-2 text-muted">No se encontró la categoría "' + escape(data.input) + '"</div>';
                }
            }
        });

        let libroSelect = new TomSelect("#libro_id", {
            create: false,
            maxItems: 1, 
            valueField: 'value',
            labelField: 'text',
            searchField: ['text', 'isbn'], 
            dataAttr: 'data', 
            persist: false,
            plugins: ['clear_button'], 
            render: {
                option: function(data, escape) {
                    return `<div>
                                <span class="fw-bold">${escape(data.text)}</span>
                                <br>
                                <small class="text-muted">ISBN: ${escape(data.isbn)}</small>
                            </div>`;
                },
                item: function(data, escape) {
                    return `<div>${escape(data.text)} <small class="text-muted">(${escape(data.isbn)})</small></div>`;
                },
                no_results: function(data, escape) {
                    return '<div class="no-results">No se encontró el libro "' + escape(data.input) + '"</div>';
                }
            }
        });

        function reactivarEjemplar(id) {
            Swal.fire({
                title: '¿Reactivar ejemplar?',
                text: "El ejemplar volverá a estar disponible.",
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
                    form.action = '/ejemplares/reactivar/' + id;
                    form.innerHTML = `@csrf`;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

    </script>
    <!-- Alerta de exito -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    <!-- Errores de validación -->
    @if ($errors->any())
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            let modalElemento = document.getElementById('registroModal');
            let modal = new bootstrap.Modal(modalElemento);
            
            let editarID = "{{ old('editing_id') }}"; 

            if (editarID) {
                document.getElementById('modalTitle').textContent = 'Editar Ejemplar';
                document.getElementById('btnModal').textContent = 'Actualizar';
                document.getElementById('registerForm').action = '/ejemplares/editar/' + editarID;
            } else {
                document.getElementById('modalTitle').textContent = 'Nuevo ejemplar';
                document.getElementById('btnModal').textContent = 'Registrar';
                document.getElementById('registerForm').action = "{{ route('ejemplares.store') }}";
            }

            modal.show();
        });
    </script>
    @endif
@endsection
</html>