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
                <input type="text" id="inputBuscar" class="form-control border-0 rounded-end-4 py-2 bg-transparent" placeholder="Buscar por titulo, autor o ISBN...">
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-2">
            <select name="biblioteca_id" id="biblioteca_id" class="form-select py-2 px-3 rounded-4 col-2 input-focus bg-transparent">
                <option value="todas">Todos las bibliotecas</option>
                @foreach($bibliotecas as $biblioteca)
                    @if($biblioteca->es_activo == 1)
                        <option value="{{ $biblioteca->id }}">
                            {{ $biblioteca->nombre }}
                        </option>
                    @endif
                @endforeach
            </select>
        </div>
        <div class="col-12 col-md-6 col-xl-2">
            <select name="estado_id" id="estado_id" class="form-select py-2 px-3 rounded-4 col-2 input-focus bg-transparent">
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
                <option value="todas">Todos las categorias</option>
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
                <option value="1">Solo activos</option>
                <option value="0">Solo inactivos</option>
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
             @include('ejemplar.partials.tabla')
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
                                <select name="libro_id" id="libro_id" class="w-100 input-focus bg-transparent @error('libro_id') is-invalid @enderror" placeholder="Seleccione un libro">
                                    <option value="" selected disabled>Seleccione un libro</option> 
                                    @foreach($libros as $libro)
                                        <option value="{{ $libro->id }}" 
                                                data-isbn="{{ $libro->isbn }}" 
                                                data-activo="{{ $libro->es_activo }}"
                                                {{ old('libro_id') == $libro->id ? 'selected' : '' }} >
                                            {{ $libro->titulo }}{{ $libro->es_activo == 0 ? ' (Inactiva)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('libro_id')
                                    <div class="invalid-feedback fs-8">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-6">
                                <label for="biblioteca_id_form" class="fs-7 icono-editar fw-semibold mb-1 mt-0">Biblioteca</label>
                                <select name="biblioteca_id" id="biblioteca_id_form"  class="form-select py-2 px-3 rounded-3 col-2 input-focus bg-transparent @error('biblioteca_id') is-invalid @enderror">
                                    <option value="" >Seleccione una biblioteca</option>
                                    @foreach($bibliotecas as $biblioteca)
                                        <option value="{{ $biblioteca->id }}" data-activo="{{ $biblioteca->es_activo }}" {{ old('biblioteca_id') == $biblioteca->id ? 'selected' : '' }}>
                                            {{ $biblioteca->nombre }} {{ $biblioteca->es_activo == 0 ? ' (Inactiva)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('biblioteca_id')
                                    <div class="invalid-feedback fs-8">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="estado_id_form" class="fs-7 icono-editar fw-semibold mb-1">Estado</label>
                            <select name="estado_id" id="estado_id_form" class="form-select py-2 px-3 rounded-4 col-2 input-focus bg-transparent @error('estado_id') is-invalid @enderror">
                                    <option value="" >Seleccione un estado</option>
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
    let categoriaSelect, libroSelect;
    let modalRegistrar = document.querySelector("#registroModal");
    let registerForm = document.querySelector("#registerForm");
    let modalTitle = document.getElementById('modalTitle');
    let btnModal = document.getElementById('btnModal');
    let inputEditar = document.getElementById('editing_id');

    let bibliotecaSelectForm= document.getElementById('biblioteca_id_form');
    let opcionesBibliotecasOriginales = bibliotecaSelectForm.innerHTML;
    let libroSelectForm = document.getElementById('libro_id');
    let opcionesLibrosOriginales = libroSelectForm.innerHTML;

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
        let btn = event.relatedTarget;
        if (!btn) return;
        bibliotecaSelectForm.innerHTML = opcionesBibliotecasOriginales;
        libroSelectForm.innerHTML = opcionesLibrosOriginales;

        let bibIdAsignada = btn.getAttribute('data-biblioteca');
        let libroIdAsignado = btn.getAttribute('data-libro');

        bibliotecaSelectForm.querySelectorAll('option').forEach(opt => {
            if (opt.value === "") return;
            if (opt.dataset.activo == '0' && opt.value != bibIdAsignada) {
                opt.remove();
            }
        });

        libroSelectForm.querySelectorAll('option').forEach(opt => {
            if (opt.value === "") return;
            if (opt.dataset.activo == '0' && opt.value != libroIdAsignado) {
                opt.remove();
            }
        });
        if (libroSelect) {
            libroSelect.clearOptions(); 
            libroSelect.sync();    
        }

        if (bibIdAsignada) {
            bibliotecaSelectForm.value = bibIdAsignada;
        }
        if (libroIdAsignado) {
            libroSelectForm.value = libroIdAsignado;
        }



        
        if (btn.hasAttribute('data-id')) {
            let id = btn.getAttribute('data-id');
            configurarModal('Editar ejemplar', 'Actualizar', `/ejemplares/editar/${id}`, id);
            if (libroSelect) {
                libroSelect.setValue(btn.getAttribute('data-libro'));
            }
            document.getElementById('biblioteca_id_form').value = btn.getAttribute('data-biblioteca');
            document.getElementById('estado_id_form').value = btn.getAttribute('data-estado');
        } else {
            configurarModal('Nuevo ejemplar', 'Registrar', "{{ route('ejemplares.store') }}");
            registerForm.reset();
            let estado = document.getElementById('estado_id_form');
                if (bibliotecaSelectForm) {
                    bibliotecaSelectForm.querySelectorAll('option').forEach(opt => opt.removeAttribute('selected'));
                }
                if (estado) {
                    estado.querySelectorAll('option').forEach(opt => opt.removeAttribute('selected'));
                }
                if (libroSelectForm) libroSelectForm.clear();
                
            }
    });

    modalRegistrar.addEventListener('hidden.bs.modal', () => {
        bibliotecaSelectForm.innerHTML = opcionesBibliotecasOriginales;
        libroSelectForm.innerHTML = opcionesLibrosOriginales;
        registerForm.reset();
        if (libroSelect) libroSelect.clear(true);
        limpiarErrorValidacion();
        inputEditar.value = "";
    });

    document.addEventListener('DOMContentLoaded', function() {
        categoriaSelect = new TomSelect("#categoria_id", { 
            plugins: ['clear_button'],
            persist: false,
            create: false
        });
        libroSelect = new TomSelect("#libro_id", {
            valueField: 'value', labelField: 'text', searchField: ['text', 'isbn'],
            plugins: ['clear_button'],
            render: {
                option: (data, escape) => `<div><b>${escape(data.text)}</b><br><small>ISBN: ${escape(data.isbn)}</small></div>`,
                item: (data, escape) => `<div>${escape(data.text)} <small>(${escape(data.isbn)})</small></div>`
            }
        });
        let filtros = {
            buscar: document.getElementById('inputBuscar'),
            estado: document.getElementById('estado_id'),
            activo: document.getElementById('activo'),
            biblio: document.getElementById('biblioteca_id')
        };

        let aplicarFiltros = () => {
            let params = new URLSearchParams({
                buscar: filtros.buscar?.value || '',
                categoria_id: categoriaSelect?.getValue() || 'todas',
                estado_id: filtros.estado?.value || 'todas',
                activo: filtros.activo?.value || 'todas',
                biblioteca_id: filtros.biblio?.value || 'todas'
            });

            fetch(`{{ route('ejemplares.index') }}?${params}`, {
                headers: { "X-Requested-With": "XMLHttpRequest" }
            })
            .then(res => res.text())
            .then(html => document.querySelector('tbody').innerHTML = html)
            .catch(err => console.error('Error:', err));
        };

        let timer;
        filtros.buscar?.addEventListener('input', () => {
            clearTimeout(timer);
            timer = setTimeout(aplicarFiltros, 300);
        });

        [filtros.estado, filtros.activo, filtros.biblio].forEach(el => el?.addEventListener('change', aplicarFiltros));
        categoriaSelect.on('change', aplicarFiltros);
    });
</script>
    
    <!-- Errores de validación -->
    @if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let editarID = "{{ old('editing_id') }}";
            let modal = new bootstrap.Modal(document.getElementById('registroModal'));
        if (editarID) {
                configurarModal('Editar Ejemplar', 'Actualizar', `/ejemplares/editar/${editarID}`, editarID);
                let bibIdAsignada = "{{ old('biblioteca_id') }}";
                let libroIdAsignado = "{{ old('libro_id') }}";

                bibliotecaSelectForm.querySelectorAll('option').forEach(opt => {
                    if (opt.value === "") return;
                    if (opt.dataset.activo == '0' && opt.value != bibIdAsignada) {
                        opt.remove();
                    }
                });

                libroSelectForm.querySelectorAll('option').forEach(opt => {
                    if (opt.value === "") return;
                    if (opt.dataset.activo == '0' && opt.value != libroIdAsignado) {
                        opt.remove();
                    }
                });
                if (libroSelect) {
                    libroSelect.clearOptions();
                    libroSelect.sync();
                    libroSelect.setValue(libroIdAsignado);
                }
            } else {
                configurarModal('Nuevo ejemplar', 'Registrar', "{{ route('ejemplares.store') }}");
                let bibIdAsignada = "{{ old('biblioteca_id') }}";
                let libroIdAsignado = "{{ old('libro_id') }}";

                bibliotecaSelectForm.querySelectorAll('option').forEach(opt => {
                    if (opt.value === "") return;
                    if (opt.dataset.activo == '0' && opt.value != bibIdAsignada) {
                        opt.remove();
                    }
                });

                libroSelectForm.querySelectorAll('option').forEach(opt => {
                    if (opt.value === "") return;
                    if (opt.dataset.activo == '0' && opt.value != libroIdAsignado) {
                        opt.remove();
                    }
                });
                if (libroSelect) {
                    libroSelect.clearOptions();
                    libroSelect.sync();
                    if(libroIdAsignado) libroSelect.setValue(libroIdAsignado);
                }
            }
            modal.show();
        });
    </script>
    @endif
@endsection
