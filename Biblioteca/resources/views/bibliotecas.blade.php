@extends('layout.menu')
@section('title', 'Bibliotecas')

@section('content')

<div class="mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <div>
            <h1 class="fs-2 mb-2">Gestión de Bibliotecas</h1>
            <p class="m-0">Administración de bibliotecas registradas</p>
        </div>

        <button type="button"
            class="btn btn-naranja rounded-3 d-flex align-items-center gap-2 px-3 py-2"
            data-bs-toggle="modal"
            data-bs-target="#modalBiblioteca">
            <i class="bi bi-plus-lg"></i>Nueva Biblioteca
        </button>
    </div>
</div>
<div class="d-flex flex-wrap align-items-center">
    <div class="col-9">
        <div class="input-group rounded-4 input-focus">
                <span class="input-group-text border-0 bg-white rounded-start-4 bg-transparent">
                    <i class="bi bi-search fs-5 color-input"></i>
                </span>
                <input type="text" id="buscador" class="form-control border-0 rounded-end-4 py-2 bg-transparent" placeholder="Buscar por nombre, email o DNI...">
            </div>
    </div>
    <div class="col-3 rounded-4 ">
        <button id="buscar"><i class="bi bi-search fs-5 color-input"></i>Buscar</button>
    </div>
</div>

<div class="row g-4" id="caja">
    @foreach($bibliotecas as $biblio)
        <div class="col-12 col-md-6">
            <div class="card shadow-sm rounded-4 p-4 position-relative h-100 {{ !$biblio->es_activo ? 'bg-light text-muted opacity-75' : '' }}">

                <!-- BOTONES -->
                <div class="position-absolute top-0 end-0 m-3 d-flex gap-2">

                    <!-- EDITAR -->
                     @if($biblio->es_activo)
                    <button class="bg-transparent border-0"
                        data-bs-toggle="modal"
                        data-bs-target="#modalBiblioteca"
                        data-id="{{ $biblio->id }}"
                        data-nombre="{{ $biblio->nombre }}"
                        data-provincia="{{ $biblio->provincia }}"
                        data-direccion="{{ $biblio->direccion }}"
                        data-telefono="{{ $biblio->telefono }}"
                        data-correo="{{ $biblio->correo }}">
                        <i class="bi bi-pencil-square icono-editar"></i>
                    </button>
                        @endif

                    <!-- ELIMINAR -->
                    @if($biblio->es_activo)

                    <!-- DESACTIVAR -->
                    <button class="bg-transparent border-0"
                        onclick="confirmarEliminar('{{ $biblio->id }}')">
                        <i class="bi bi-trash icono-eliminar"></i>
                    </button>

                    @else

                        <!-- REACTIVAR -->
                        <button class="bg-transparent border-0"
                            onclick="reactivarBiblioteca('{{ $biblio->id }}')">
                            <i class="bi bi-arrow-counterclockwise text-success"></i>
                        </button>

                    @endif

                </div>

                <!-- CONTENIDO -->
                <h5 class="fw-semibold mb-3">{{ $biblio->nombre }}</h5>

                <p class="mb-2">
                    <i class="bi bi-geo-alt me-2"></i>
                    {{ $biblio->provincia }}
                </p>

                <p class="mb-2">
                    <i class="bi bi-house me-2"></i>
                    {{ $biblio->direccion }}
                </p>

                <p class="mb-2">
                    <i class="bi bi-telephone me-2"></i>
                    {{ $biblio->telefono }}
                </p>

                <p class="mb-0">
                    <i class="bi bi-envelope me-2"></i>
                    {{ $biblio->correo }}
                </p>

            </div>
        </div>
    @endforeach
</div>


<!-- MODAL CREAR / EDITAR -->
<div class="modal fade" id="modalBiblioteca" tabindex="-1"
    data-bs-backdrop="static"
    data-bs-keyboard="false">

    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4">

            <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-4 pb-4 pt-0">

                <h2 class="fs-4 fw-semibold mb-3" id="modalTitle">Nueva Biblioteca</h2>

                <form id="bibliotecaForm" method="POST" action="{{ route('biblio.store') }}">
                    @csrf
                    <input type="hidden" id="editing_id" name="editing_id">

                    <div class="row g-3">

                        <div class="col-6">
                            <label class="fw-semibold mb-1">Nombre</label>
                            <input type="text" name="nombre" id="nombre"
                                class="form-control rounded-3 @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}">
                            @error('nombre')
                                    <div class="invalid-feedback fs-8">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-6">
                            <label class="fw-semibold mb-1">Provincia</label>
                            <input type="text" name="provincia" id="provincia"
                                class="form-control rounded-3 @error('provincia') is-invalid @enderror" value="{{ old('provincia') }}">
                            @error('provincia')
                                    <div class="invalid-feedback fs-8">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-6">
                            <label class="fw-semibold mb-1">Dirección</label>
                            <input type="text" name="direccion" id="direccion"
                                class="form-control rounded-3 @error('direccion') is-invalid @enderror" value="{{ old('direccion') }}">
                            @error('direccion')
                                    <div class="invalid-feedback fs-8">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-6">
                            <label class="fw-semibold mb-1">Teléfono</label>
                            <input type="text" name="telefono" id="telefono"
                                class="form-control rounded-3 @error('telefono') is-invalid @enderror" value="{{ old('telefono') }}">
                            @error('telefono')
                                    <div class="invalid-feedback fs-8">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-6">
                            <label class="fw-semibold mb-1">Correo</label>
                            <input type="email" name="correo" id="correo"
                                class="form-control rounded-3 @error('correo') is-invalid @enderror" value="{{ old('correo') }}">
                            @error('correo')
                                    <div class="invalid-feedback fs-8">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <div class="row mt-4">
                        <div class="col-6">
                            <button type="button"
                                class="w-100 btn border rounded-3"
                                data-bs-dismiss="modal">
                                Cancelar
                            </button>
                        </div>

                        <div class="col-6">
                            <button type="submit"
                                class="w-100 btn btn-naranja rounded-3"
                                id="btnModal">
                                Guardar
                            </button>
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

let btnBuscar= document.getElementById('buscar');
let modal = document.querySelector("#modalBiblioteca");
let form = document.querySelector("#bibliotecaForm");
let modalTitle = document.getElementById('modalTitle');
let btnModal = document.getElementById('btnModal');

    modal.addEventListener('show.bs.modal', (event) => {

        let boton = event.relatedTarget;

        if (boton.hasAttribute('data-id')) {

            let id = boton.getAttribute('data-id');

            modalTitle.textContent = 'Editar Biblioteca';
            btnModal.textContent = 'Actualizar';

            form.action = '/biblioteca/editar/' + id;

            document.getElementById('editing_id').value = id;
            document.getElementById('nombre').value = boton.getAttribute('data-nombre');
            document.getElementById('provincia').value = boton.getAttribute('data-provincia');
            document.getElementById('direccion').value = boton.getAttribute('data-direccion');
            document.getElementById('telefono').value = boton.getAttribute('data-telefono');
            document.getElementById('correo').value = boton.getAttribute('data-correo');

        } else if (boton){

            modalTitle.textContent = 'Nueva Biblioteca';
            btnModal.textContent = 'Registrar';

            form.action = "{{ route('biblio.store') }}";
            form.reset();
        }
    });
        
    modal.addEventListener('hidden.bs.modal', () => {
        form.reset();
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    });


function confirmarEliminar(id) {

    Swal.fire({
        title: '¿Desactivar biblioteca?',
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
            form.action = '/biblioteca/eliminar/' + id;
            form.innerHTML = `@csrf`;
            document.body.appendChild(form);
            form.submit();
        }

    });

}
function reactivarBiblioteca(id) {

    Swal.fire({
        title: '¿Reactivar biblioteca?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#ff8000',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, reactivar',
        cancelButtonText: 'Cancelar',
        customClass: { popup: 'rounded-4' }
    }).then((result) => {

        if (result.isConfirmed) {

            let form = document.createElement('form');
            form.method = 'POST';
            form.action = '/biblioteca/reactivar/' + id;
            form.innerHTML = `@csrf`;
            document.body.appendChild(form);
            form.submit();
        }

    });
}
btnBuscar.addEventListener('click', () => {

    let inputProvincia = document.getElementById('buscador');
    let provincia = inputProvincia.value.trim(); 
    
    if (provincia !== '') {
        fetch("{{ route('biblio.buscar') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                provincia: provincia  
            })
        })
        .then(response => response.json())
        .then(data => {

            let caja = document.getElementById('caja');
            caja.innerHTML = '';

            if (data.length === 0) {
                caja.innerHTML = '<p class="text-muted">No se encontraron resultados</p>';
                return;
            }

            data.forEach(biblio => {
                
                // Determinar clases CSS según estado
                let cardClass = !biblio.es_activo ? 'bg-light text-muted opacity-75' : '';
                
                // Botón de eliminar o reactivar según estado
                let actionButton = biblio.es_activo 
                    ? `<button class="bg-transparent border-0" onclick="confirmarEliminar('${biblio.id}')">
                        <i class="bi bi-trash icono-eliminar"></i>
                       </button>`
                    : `<button class="bg-transparent border-0" onclick="reactivarBiblioteca('${biblio.id}')">
                        <i class="bi bi-arrow-counterclockwise text-success"></i>
                       </button>`;

                caja.innerHTML += `
                <div class="col-12 col-md-6">
                    <div class="card shadow-sm rounded-4 p-4 position-relative h-100 ${cardClass}">

                        <!-- BOTONES -->
                        <div class="position-absolute top-0 end-0 m-3 d-flex gap-2">
                            
                            <!-- EDITAR -->
                            <button class="bg-transparent border-0"
                                data-bs-toggle="modal"
                                data-bs-target="#modalBiblioteca"
                                data-id="${biblio.id}"
                                data-nombre="${biblio.nombre}"
                                data-provincia="${biblio.provincia}"
                                data-direccion="${biblio.direccion}"
                                data-telefono="${biblio.telefono}"
                                data-correo="${biblio.correo}">
                                <i class="bi bi-pencil-square icono-editar"></i>
                            </button>

                            <!-- ELIMINAR / REACTIVAR -->
                            ${actionButton}

                        </div>

                        <!-- CONTENIDO -->
                        <h5 class="fw-semibold mb-3">${biblio.nombre}</h5>

                        <p class="mb-2">
                            <i class="bi bi-geo-alt me-2"></i>
                            ${biblio.provincia}
                        </p>

                        <p class="mb-2">
                            <i class="bi bi-house me-2"></i>
                            ${biblio.direccion}
                        </p>

                        <p class="mb-2">
                            <i class="bi bi-telephone me-2"></i>
                            ${biblio.telefono}
                        </p>

                        <p class="mb-0">
                            <i class="bi bi-envelope me-2"></i>
                            ${biblio.correo}
                        </p>

                    </div>
                </div>
                `;
            });

        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo realizar la búsqueda'
            });
        });
    } else {
        Swal.fire({
            icon: 'warning',
            title: 'Atención',
            text: 'Por favor ingresa una provincia para buscar'
        });
    }
});
</script>
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
    <!-- Errores de validación -->
    @if ($errors->any())
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            var modalElemento = document.getElementById('modalBiblioteca');
            var modal = new bootstrap.Modal(modalElemento);
            
            let editarID = "{{ old('editing_id') }}"; 

            if (editarID) {
                document.getElementById('modalTitle').textContent = 'Editar biblioteca';
                document.getElementById('btnModal').textContent = 'Actualizar';
                document.getElementById('bibliotecaForm').action = '/biblioteca/editar/' + editarID;
            } else {
                document.getElementById('modalTitle').textContent = 'Nueva biblioteca';
                document.getElementById('btnModal').textContent = 'Registrar';
                document.getElementById('bibliotecaForm').action = "{{ route('biblio.store') }}";
            }

            modal.show();
        });
    </script>
    @endif
@endsection
</html>