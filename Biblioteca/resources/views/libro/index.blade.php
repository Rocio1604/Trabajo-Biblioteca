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
        <div class="col-12 col-md-4 col-xl-2">
            <select name="estado" id="estado" class="form-select py-2 px-3 rounded-4 col-2 input-focus bg-transparent">
                <option value="todas">Todos los estados</option>
                <!-- @foreach($estados as $estado)
                    <option value="{{ $estado->id }}">
                        Cuota {{ $estado->nombre }}
                    </option>
                @endforeach -->
            </select>
        </div>
        <div class="col-12 col-md-4 col-xl-2">
            <select name="disponibilidad" id="disponibilidad" class="form-select py-2 px-3 rounded-4 col-2 input-focus bg-transparent">
                <option value="todas">Cualquier Disnibilidad</option>
            </select>
        </div>
        <div class="col-12 col-md-4 col-xl-2">
            <select name="activo" id="activo" class="form-select py-2 px-3 rounded-4 col-2 input-focus bg-transparent">
                <option value="todas">Todos los libros</option>
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
                <th class="px-4 py-12">ESTADO</th>
                <th class="px-4 py-12">BIBLIOTECA</th>
                <th class="px-4 py-12">DISPONIBILIDAD</th>
                <th class="px-4 py-12">¿ACTIVO?</th>
                <th class="px-4 py-12">ACCIONES</th>
            </tr>
        </thead>
        <tbody>
            @foreach($socios as $socio)
            <tr>
                <td class="px-4 py-3 fs-6 fw-bold">Cien años de soledad<!-- {{ $socio->biblioteca->nombre }} --></td>
                <td class="px-4 py-3 fs-7">978-84-376-0494-7<!-- {{ $socio->dni }} --></td>
                <td class="px-4 py-3 fs-7">Gabriel García Márquez<!-- {{ $socio->dni }} --></td>
                <td class="px-4 py-3 fs-7">Novela<!-- {{ $socio->biblioteca->nombre }} --></td>
                <td class="px-4 py-3">
                    @switch($libro->estado->id)
                        @case(1)
                            <div class="d-flex flex-wrap align-items-center disponible rounded-3 px-2 py-1 gap-1 fw-semibold" style="width: fit-content;">
                                <span class="fs-8">Nuevo<!-- {{ $socio->estado->nombre }} --></span>
                            </div>
                            @break
                        @case(2)
                        <!-- color azul en letra y de fondo azul claro -->
                             <div class="d-flex flex-wrap align-items-center disponible rounded-3 px-2 py-1 gap-1 fw-semibold" style="width: fit-content;">
                                <span class="fs-8">Bueno<!-- {{ $socio->estado->nombre }} --></span>
                            </div>
                            @break
                        @case(3)
                        <!-- color naranja en letra y de fondo naranja claro -->
                             <div class="d-flex flex-wrap align-items-center disponible rounded-3 px-2 py-1 gap-1 fw-semibold" style="width: fit-content;">
                                <span class="fs-8">Desgastado<!-- {{ $socio->estado->nombre }} --></span>
                            </div>
                            @break
                        @case(4)
                        <!-- color naranja en letra y de fondo naranja claro -->
                             <div class="d-flex flex-wrap align-items-center no-disponible rounded-3 px-2 py-1 gap-1 fw-semibold" style="width: fit-content;">
                                <span class="fs-8">Roto<!-- {{ $socio->estado->nombre }} --></span>
                            </div>
                            @break
                            
                    @endswitch
                </td>
                <td class="px-4 py-3 fs-7">Madrid<!-- {{ $libro->biblioteca->nombre }} --></td>
                <td class="px-4 py-3">
                    <!-- color verde con icono de caja -->
                    @if($socio->estado->id === 1)
                        <div class="d-flex flex-wrap align-items-center disponible rounded-3 px-2 py-1 gap-1 fw-semibold" style="width: fit-content;">
                            <i class="bi bi-check2-circle fs-8"></i>
                            <span class="fs-8">Disponible</span>
                        </div>
                    @endif
                    <!-- color gris -->
                    @if($socio->estado->id === 2)   
                        <div class="d-flex flex-wrap align-items-center no-disponible rounded-3 px-2 py-1 gap-1 fw-semibold" style="width: fit-content;">
                            <i class="bi bi-x-circle fs-8"></i>
                            <span class="fs-8">Vencida</span>
                        </div>
                    @endif
                </td>
                <td class="px-4 py-3">
                    <div class="d-flex wrap-flex gap-4">
                        @if($libro->es_activo)
                            <button class="bg-transparent border-0" data-bs-toggle="modal" 
                                    data-bs-target="#registroModal"
                                    data-id="{{ $socio->id }}"
                                    data-nombre="{{ $socio->nombre }}"
                                    data-dni="{{ $socio->dni }}"
                                    data-email="{{ $socio->email }}"
                                    data-telefono="{{ $socio->telefono }}"
                                    data-biblioteca="{{ $socio->biblioteca_id }}">
                                <i class="bi bi-pencil-square icono-editar"></i>
                            </button>
                            <button class="bg-transparent border-0 " onclick="confirmarEliminar('{{ $socio->id }}')">
                                <i class="bi bi-trash icono-eliminar"></i>
                            </button>
                        @else
                            <button class="bg-transparent border-0 " onclick="reactivarLibro('{{ $socio->id }}')">
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

<div class="modal fade" id="resgistroModal" tabindex="-1"
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
                                <label for="categoria" class="fs-7 icono-editar fw-semibold mb-1">Categoría</label>
                                <select name="categoria" id="categoria"  class="form-select py-2 px-3 rounded-3 col-2 input-focus bg-transparent @error('categoria') is-invalid @enderror">
                                    <option value="" selected disabled>Seleccione una categoría</option>
                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria->id }}" {{ old('categoria') == $categoria->id ? 'selected' : '' }}>
                                            {{ $categoria->nombre }}
                                        </option>
                                    @endforeach
                                    </select>
                                    @error('categoria')
                                        <div class="invalid-feedback fs-8">{{ $message }}</div>
                                    @enderror
                            </div>
                            <div class="col-6">
                                <label for="estado" class="fs-7 icono-editar fw-semibold mb-1">Estado</label>
                                <select name="estado" id="estado"  class="form-select py-2 px-3 rounded-3 col-2 input-focus bg-transparent @error('estado') is-invalid @enderror">
                                    <option value="" selected disabled>Seleccione un estado</option>
                                    @foreach($estados as $estado)
                                        <option value="{{ $estado->id }}" {{ old('estado') == $estado->id ? 'selected' : '' }}>
                                            {{ $estado->nombre }}
                                        </option>
                                    @endforeach
                                    </select>
                                    @error('estado')
                                        <div class="invalid-feedback fs-8">{{ $message }}</div>
                                    @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                                <label for="biblioteca" class="fs-7 icono-editar fw-semibold mb-1 mt-0">Biblioteca</label>
                                <select name="biblioteca" id="biblioteca"  class="form-select py-2 px-3 rounded-3 col-2 input-focus bg-transparent @error('biblioteca') is-invalid @enderror">
                                    <option value="" selected disabled>Seleccione una biblioteca</option>
                                    @foreach($bibliotecas as $biblioteca)
                                        <option value="{{ $biblioteca->id }}" {{ old('biblioteca') == $biblioteca->id ? 'selected' : '' }}>
                                            {{ $biblioteca->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('biblioteca')
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
                modalTitle.textContent = 'Editar libro';
                btnModal.textContent = 'Actualizar';
                registerForm.action = '/libros/editar/' + id;
                document.getElementById('editing_id').value = id;
                /* 
                document.getElementById('dni').value = boton.getAttribute('data-dni');
                document.getElementById('nombre').value = boton.getAttribute('data-nombre');
                document.getElementById('email').value = boton.getAttribute('data-email');
                document.getElementById('telefono').value = boton.getAttribute('data-telefono');
                document.getElementById('biblioteca').value = boton.getAttribute('data-biblioteca'); */
            } else if (boton){
                modalTitle.textContent = 'Nuevo libro';
                btnModal.textContent = 'Registrar';
                registerForm.action = "{{ route('libros.store') }}";
                document.getElementById('biblioteca').value = "";
                document.getElementById('estado').value = "";
                registerForm.reset();
            }
        })

        modalRegistrar.addEventListener('hidden.bs.modal', () => {
            registerForm.reset();
            registerForm.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        });

        function confirmarEliminar(id) {
            Swal.fire({
                title: '¿Desactivar libro?',
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
                    form.action = '/socios/eliminar/' + id;
                    form.innerHTML = `@csrf`;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
        function reactivarLibro(id) {
            Swal.fire({
                title: '¿Reactivar libro?',
                text: "El libro volverá a estar disponible.",
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
                    form.action = '/socios/reactivar/' + id;
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
            var modalElemento = document.getElementById('registroModal');
            var modal = new bootstrap.Modal(modalElemento);
            
            let editarID = "{{ old('editing_id') }}"; 

            if (editarID) {
                document.getElementById('modalTitle').textContent = 'Editar Libro';
                document.getElementById('btnModal').textContent = 'Actualizar';
                document.getElementById('registerForm').action = '/socios/editar/' + editarID;
            } else {
                document.getElementById('modalTitle').textContent = 'Nuevo socio';
                document.getElementById('btnModal').textContent = 'Registrar';
                document.getElementById('registerForm').action = "{{ route('socio.store') }}";
            }

            modal.show();
        });
    </script>
    @endif
@endsection
</html>