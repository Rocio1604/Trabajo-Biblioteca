<!DOCTYPE html>
<html lang="en">
@extends('layout.menu')
@section('title', 'Libros')
@section('content')
<div class="mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <div>
            <h1 class="fs-2 mb-2">Gestión de Libros</h1>
            <p class="m-0">Catálogo completo de libros del sistema</p>
        </div>
        <button type="button" class="btn btn-naranja rounded-3 d-flex align-items-center justify-content-center gap-2 px-3 py-2" data-bs-toggle="modal" data-bs-target="#registroModal">
                        <i class="bi bi-plus-lg fs-5"></i>Nuevo Libro</button>
    </div>
    <div class="row g-3">
        <div class="col-12 col-xl-6 ">
            <div class="input-group rounded-4 input-focus">
                <span class="input-group-text border-0 bg-white rounded-start-4 bg-transparent">
                    <i class="bi bi-search fs-5 color-input"></i>
                </span>
                <input type="text" class="form-control border-0 rounded-end-4 py-2 bg-transparent" placeholder="Buscar por titulo, autor o ISBN...">
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <select name="categoria" id="buscar_categoria_id" class="w-100 input-focus bg-transparent" style="
    border-radius: 15px;">
                <option value="todas">Todos las categorias</option>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}">
                        {{ $categoria->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <select name="activo" id="activo" class="form-select py-2 px-3 rounded-4 col-2 input-focus bg-transparent">
                <option value="todas">Todos los libros</option>
                <option value="1">Activos</option>
                <option value="0">Inactivos</option>
            </select>
        </div>
    </div>
</div>

<div class="border rounded-4 overflow-hidden shadow-sm">
    <table class="table mb-0 align-middle">
        <thead class="table-light">
            <tr>
                <th class="px-4 py-12">LIBRO</th>
                <th class="px-4 py-12">ISBN</th>
                <th class="px-4 py-12">AUTORES</th>
                <th class="px-4 py-12">CATEGORÍA</th>
                <th class="px-4 py-12">PRECIO</th>
                <th class="px-4 py-12">¿ACTIVO?</th>
                <th class="px-4 py-12">ACCIONES</th>
            </tr>
        </thead>
        <tbody>
            @foreach($libros as $libro)
            <tr @if($libro->es_activo==0) class="table-light opacity-50" @endif>
                <td class="px-4 py-3 fs-6 fw-semibold">{{ $libro->titulo }}</td>
                <td class="px-4 py-3 fs-7">{{ $libro->isbn }}</td>
                <td class="px-4 py-3 fs-7">
                    {{ $libro->autores->pluck('nombre')->implode(', ') }}
                </td>
                <td class="px-4 py-3 fs-7">{{ $libro->categoria->nombre }}</td>
                <td class="px-4 py-3 fs-7">€{{ number_format($libro->precio, 2) }}</td>
                <td class="px-4 py-3">
                    <!-- color verde con icono de caja -->
                    @if($libro->es_activo)
                        Sí
                    @else
                        No
                    @endif
                </td>
                <td class="px-4 py-3">
                    <div class="d-flex wrap-flex gap-4">
                        @if($libro->es_activo)
                            <button class="bg-transparent border-0" data-bs-toggle="modal" 
                                    data-bs-target="#registroModal"
                                    data-id="{{ $libro->id }}"
                                    data-titulo="{{ $libro->titulo }}"
                                    data-isbn="{{ $libro->isbn }}"
                                    data-categoria="{{ $libro->categoria->id }}"
                                    data-precio="{{ $libro->precio }}"
                                    data-autores="{{ json_encode($libro->autores->pluck('id')) }}">
                                <i class="bi bi-pencil-square icono-editar"></i>
                            </button>
                            <button class="bg-transparent border-0 " onclick="confirmarEliminar('{{ $libro->id }}','libro','libros')">
                                <i class="bi bi-trash icono-eliminar"></i>
                            </button>
                        @else
                            <button class="bg-transparent border-0 " onclick="confirmarReactivar('{{ $libro->id }}','libro','libros')">
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
                    <h2 class="fs-4 fw-semibold mb-2 titulo-modal mb-2" id="modalTitle">Nuevo Libro</h2>
                    <form id="registerForm"  method="POST" class="d-flex flex-column gap-3">
                        @csrf
                        <input type="hidden" id="editing_id" name="editing_id" value="{{ old('editing_id') }}">
                        <div class="row gx-3">
                            <div class="col-6">
                                <label for="isbn" class="fs-7 icono-editar fw-semibold mb-1">ISBN</label>
                                <input type="text" id="isbn" name="isbn" value="{{ old('isbn') }}" class="form-control rounded-3 input-focus py-2 @error('isbn') is-invalid @enderror">
                                @error('isbn')
                                    <div class="invalid-feedback fs-8">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-6">
                                <label for="titulo" class="fs-7 icono-editar fw-semibold mb-1">Título</label>
                                <input type="text" id="titulo" name="titulo" value="{{ old('titulo') }}" class="form-control rounded-3 input-focus py-2 @error('titulo') is-invalid @enderror">
                                @error('titulo')
                                    <div class="invalid-feedback fs-8">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row gx-3">
                            <div class="col-6">
                                <label for="categoria_id" class="fs-7 icono-editar fw-semibold mb-1">Categoría</label>
                                <select name="categoria_id" id="categoria_id"  class="w-100 input-focus bg-transparent @error('categoria_id') is-invalid @enderror" placeholder="Seleccione una categoría">
                                    <option value="" selected disabled>Seleccione una categoría</option> 
                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                            {{ $categoria->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                    @error('categoria_id')
                                        <div class="invalid-feedback fs-8">{{ $message }}</div>
                                    @enderror
                            </div>
                            <div class="col-6">
                                <label for="precio" class="fs-7 icono-editar fw-semibold mb-1">Precio</label>
                                <input type="text" id="precio" name="precio" value="{{ old('precio') }}" class="form-control rounded-3 input-focus py-2 @error('precio') is-invalid @enderror">
                                @error('precio')
                                    <div class="invalid-feedback fs-8">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                                <label for="autores" class="fs-7 icono-editar fw-semibold mb-1 mt-0">Autores</label>
                                <select id="autores" name="autores[]" multiple placeholder="Seleccione uno o varios autores" autocomplete="off" class="input-focus @error('autores') is-invalid @enderror">
                                        <option value="" selected disabled>Seleccione uno o varios autores</option>
                                    @foreach($autores as $autor)
                                        <option value="{{ $autor->id }}" {{ old('autores') && in_array($autor->id, old('autores')) ? 'selected' : '' }}>
                                            {{ $autor->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('autores')
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
    let categoriaSelect, autoresSelect, buscarCategoriaSelect;
    let modalRegistrar = document.querySelector("#registroModal");
    let registerForm = document.querySelector("#registerForm");
    let modalTitle = document.getElementById('modalTitle');
    let btnModal = document.getElementById('btnModal');
    let inputEditar = document.getElementById('editing_id');

    let configurarModal = (titulo, btnTexto, accion, id = "") => {
        modalTitle.textContent = titulo;
        btnModal.textContent = btnTexto;
        registerForm.action = accion;
        inputEditar.value = id;
    };
    let limpiarErrorValidacion = () => {
        registerForm.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    };

    modalRegistrar.addEventListener('show.bs.modal', (event) => {
        let boton = event.relatedTarget;
        if (!boton) return;
        if (boton.hasAttribute('data-id')) {
            let id = boton.getAttribute('data-id');
            configurarModal('Editar libro', 'Actualizar', `/libros/editar/${id}`, id);
            document.getElementById('isbn').value = boton.getAttribute('data-isbn');
            document.getElementById('titulo').value = boton.getAttribute('data-titulo');
            document.getElementById('precio').value = boton.getAttribute('data-precio');
            categoriaSelect.setValue(boton.getAttribute('data-categoria'));
            autoresSelect.setValue(JSON.parse(boton.getAttribute('data-autores')));
        } else {
            configurarModal('Nuevo libro', 'Registrar', "{{ route('libros.store') }}");
            registerForm.reset();
            document.getElementById('isbn').value ="";
            document.getElementById('titulo').value ="";
            document.getElementById('precio').value ="";
            if(categoriaSelect) categoriaSelect.clear();
            if(autoresSelect) autoresSelect.clear();
        }
    });
    modalRegistrar.addEventListener('hidden.bs.modal', () => {
        registerForm.reset();
        [categoriaSelect, autoresSelect].forEach(select => select?.clear(true));
        limpiarErrorValidacion();
        inputEditar.value = "";
    });
    document.addEventListener('DOMContentLoaded', function() {
        let tsConfig = { 
            create: false, persist: false, plugins: ['clear_button'],
            render: { no_results: (data, escape) => `<div class="no-results p-2 text-muted">No se encontró "${escape(data.input)}"</div>` }
        };
        categoriaSelect = new TomSelect("#categoria_id", tsConfig);
        buscarCategoriaSelect = new TomSelect("#buscar_categoria_id", tsConfig);
        autoresSelect = new TomSelect("#autores", {
            ...tsConfig,
            plugins: ['remove_button', 'clear_button'],
            maxItems: null
        });
    });
</script>
    @if ($errors->any())
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            let modalElemento = document.getElementById('registroModal');
            let modal = new bootstrap.Modal(modalElemento);
            let editarID = "{{ old('editing_id') }}"; 

            if (editarID) {
                configurarModal('Editar libro', 'Actualizar', `/libros/editar/${editarID}`, editarID);
            } else {
                configurarModal('Nuevo libro', 'Registrar', "{{ route('libros.store') }}");
            }

            modal.show();
        });
    </script>
    @endif
@endsection
</html>