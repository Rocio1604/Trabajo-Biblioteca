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

<div class="row g-4">
    @foreach($bibliotecas as $biblio)
        <div class="col-12 col-md-6">
            <div class="card shadow-sm rounded-4 p-4 position-relative h-100 {{ !$biblio->es_activo ? 'bg-light text-muted opacity-75' : '' }}">

                <!-- BOTONES -->
                <div class="position-absolute top-0 end-0 m-3 d-flex gap-2">

                    <!-- EDITAR -->
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
                                class="form-control rounded-3">
                        </div>

                        <div class="col-6">
                            <label class="fw-semibold mb-1">Provincia</label>
                            <input type="text" name="provincia" id="provincia"
                                class="form-control rounded-3">
                        </div>

                        <div class="col-6">
                            <label class="fw-semibold mb-1">Dirección</label>
                            <input type="text" name="direccion" id="direccion"
                                class="form-control rounded-3">
                        </div>

                        <div class="col-6">
                            <label class="fw-semibold mb-1">Teléfono</label>
                            <input type="text" name="telefono" id="telefono"
                                class="form-control rounded-3">
                        </div>

                        <div class="col-6">
                            <label class="fw-semibold mb-1">Correo</label>
                            <input type="email" name="correo" id="correo"
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

    } else {

        modalTitle.textContent = 'Nueva Biblioteca';
        btnModal.textContent = 'Guardar';

        form.action = "{{ route('biblio.store') }}";
        form.reset();
    }
});


function confirmarEliminar(id) {

    Swal.fire({
        title: '¿Eliminar biblioteca?',
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
        cancelButtonText: 'Cancelar'
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


</script>
@endsection
