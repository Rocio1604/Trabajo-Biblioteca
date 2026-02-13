@extends('layout.menu')
@section('title', 'Autores')

@section('content')

<div class="mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <div>
            <h1 class="fs-2 mb-2">Gestión de Autores</h1>
            <p class="m-0">Catálogo de autores registrados en el sistema</p>
        </div>

        <button type="button"
            class="btn btn-naranja rounded-3 d-flex align-items-center gap-2 px-3 py-2"
            data-bs-toggle="modal"
            data-bs-target="#modalAutor">
            <i class="bi bi-plus-lg"></i>Nuevo Autor
        </button>
    </div>
</div>
<div class="d-flex flex-wrap align-items-center">
    <div class="col-9">
        <div class="input-group rounded-4 input-focus">
                <span class="input-group-text border-0 bg-white rounded-start-4 bg-transparent">
                    <i class="bi bi-search fs-5 color-input"></i>
                </span>
                <input type="text" id="buscador" class="form-control border-0 rounded-end-4 py-2 bg-transparent" placeholder="Buscar por nombre">
            </div>
    </div>
    <div class="col-3">
        <button id="buscar"><i class="bi bi-search fs-5 color-input"></i>Buscar</button>
    </div>
</div>

<div class="row g-4" id="caja">
    @foreach($autores as $autor)
        <div class="col-12 col-md-6">
            <div class="card shadow-sm rounded-4 p-4 position-relative h-100 {{ !$autor->es_activo ? 'bg-light text-muted opacity-75' : '' }}">

                <!-- BOTONES -->
                <div class="position-absolute top-0 end-0 m-3 d-flex gap-2">
                    @if($autor->es_activo)
                    <!-- EDITAR -->
                    <button class="bg-transparent border-0"
                        data-bs-toggle="modal"
                        data-bs-target="#modalAutor"
                        data-id="{{ $autor->id }}"
                        data-nombre="{{ $autor->nombre }}"
                        data-feche="{{ $autor->fecha_nacimiento }}">
                        <i class="bi bi-pencil-square icono-editar"></i>
                    </button>
                    @endif
                    <!-- ELIMINAR -->
                    @if($autor->es_activo)

                    <!-- DESACTIVAR -->
                    <button class="bg-transparent border-0"
                        onclick="confirmarEliminar('{{ $autor->id }}')">
                        <i class="bi bi-trash icono-eliminar"></i>
                    </button>

                    @else

                        <!-- REACTIVAR -->
                        <button class="bg-transparent border-0"
                            onclick="reactivarAutor('{{ $autor->id }}')">
                            <i class="bi bi-arrow-counterclockwise text-success"></i>
                        </button>

                    @endif

                </div>

                <!-- CONTENIDO -->
                <h5 class="fw-semibold mb-3">{{ $autor->nombre }}</h5>

                <p class="mb-2">
                    <i class="bi bi-calendar"></i>
                    {{ $autor->fecha_nacimiento }}
                </p>

                </p>

            </div>
        </div>
    @endforeach
</div>


<!-- MODAL CREAR / EDITAR -->
<div class="modal fade" id="modalAutor" tabindex="-1"
    data-bs-backdrop="static"
    data-bs-keyboard="false">

    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4">

            <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-4 pb-4 pt-0">

                <h2 class="fs-4 fw-semibold mb-3" id="modalTitle">Nuevo autor</h2>

                <form id="autorForm" method="POST" action="{{ route('autor.store') }}">
                    @csrf
                    <input type="hidden" id="editing_id" name="editing_id">

                    <div class="row g-3">

                        <div class="col-6">
                            <label class="fw-semibold mb-1">Nombre</label>
                            <input type="text" name="nombre" id="nombre"
                                class="form-control rounded-3">
                        </div>

                        <div class="col-6">
                            <label class="fw-semibold mb-1">Fecha de nacimiento</label>
                            <input type="text" name="fecha_nacimiento" id="fecha_nacimiento"
                                class="form-control rounded-3">
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
let modal = document.querySelector("#modalAutor");
let form = document.querySelector("#autorForm");
let modalTitle = document.getElementById('modalTitle');
let btnModal = document.getElementById('btnModal');

modal.addEventListener('show.bs.modal', (event) => {

    let boton = event.relatedTarget;

    if (boton.hasAttribute('data-id')) {

        let id = boton.getAttribute('data-id');

        modalTitle.textContent = 'Editar autor';
        btnModal.textContent = 'Actualizar';

        form.action = '/autores/editar/' + id;

        document.getElementById('editing_id').value = id;
        document.getElementById('nombre').value = boton.getAttribute('data-nombre');
        document.getElementById('fecha_nacimiento').value = boton.getAttribute('data-fecha_nacimiento');

    } else {

        modalTitle.textContent = 'Nuevo autor';
        btnModal.textContent = 'Guardar';

        form.action = "{{ route('autor.store') }}";
        form.reset();
    }
});


function confirmarEliminar(id) {

    Swal.fire({
        title: '¿Eliminar autor?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ff8000',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {

        if (result.isConfirmed) {

            let form = document.createElement('form');
            form.method = 'POST';
            form.action = '/autores/eliminar/' + id;
            form.innerHTML = `@csrf`;
            document.body.appendChild(form);
            form.submit();
        }

    });

}
function reactivarAutor(id) {

    Swal.fire({
        title: '¿Reactivar autor?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#ff8000',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, reactivar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {

        if (result.isConfirmed) {

            let form = document.createElement('form');
            form.method = 'POST';
            form.action = '/autores/reactivar/' + id;
            form.innerHTML = `@csrf`;
            document.body.appendChild(form);
            form.submit();
        }

    });
}


btnBuscar.addEventListener('click', () => {

    let inputNombre = document.getElementById('buscador');
    let nombre = inputNombre.value.trim();
    
    if (nombre !== '') {
        fetch("{{ route('autor.buscar') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                nombre: nombre
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

            data.forEach(autor => {
                
                let cardClass = !autor.es_activo ? 'bg-light text-muted opacity-75' : '';
                
                let actionButton = autor.es_activo 
                    ? `<button class="bg-transparent border-0" onclick="confirmarEliminar('${autor.id}')">
                        <i class="bi bi-trash icono-eliminar"></i>
                       </button>`
                    : `<button class="bg-transparent border-0" onclick="reactivarAutor('${autor.id}')">
                        <i class="bi bi-arrow-counterclockwise text-success"></i>
                       </button>`;

                caja.innerHTML += `
                <div class="col-12 col-md-6">
                    <div class="card shadow-sm rounded-4 p-4 position-relative h-100 ${cardClass}">

                        <div class="position-absolute top-0 end-0 m-3 d-flex gap-2">
                            
                            <button class="bg-transparent border-0"
                                data-bs-toggle="modal"
                                data-bs-target="#modalAutor"
                                data-id="${autor.id}"
                                data-nombre="${autor.nombre}"
                                data-feche="${autor.fecha_nacimiento}">
                                <i class="bi bi-pencil-square icono-editar"></i>
                            </button>

                            ${actionButton}

                        </div>

                        <h5 class="fw-semibold mb-3">${autor.nombre}</h5>
                        
                        <p class="mb-0">
                            <i class="bi bi-calendar me-2"></i>
                            ${autor.fecha_nacimiento}
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
    }
});
</script>
@endsection
