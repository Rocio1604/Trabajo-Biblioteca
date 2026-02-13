<!DOCTYPE html>
<html lang="en">
@extends('layout.menu')
@section('title', 'Trabajadores')
@section('content')
<div class="mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <div>
            <h1 class="fs-2 mb-2">Gestión de Trabajadores</h1>
            <p class="m-0">Administración de usuarios del sistema</p>
        </div>
        <button type="button" class="btn btn-naranja rounded-3 d-flex align-items-center justify-content-center gap-2 px-3 py-2" data-bs-toggle="modal" data-bs-target="#registroModal">
                        <i class="bi bi-plus-lg fs-5"></i>Nuevo Trabajador</button>
    </div>
    <div class="row g-3">
        <div class="col-12 col-xl-6 ">
            <div class="input-group rounded-4 input-focus">
                <span class="input-group-text border-0 bg-white rounded-start-4 bg-transparent">
                    <i class="bi bi-search fs-5 color-input"></i>
                </span>
                <input type="text" class="form-control border-0 rounded-end-4 py-2 bg-transparent" placeholder="Buscar por nombre, email o usuario...">
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <select name="roles" id="roles" class="form-select py-2 px-3 rounded-4 col-2 input-focus bg-transparent">
                <option value="todas">Todos los roles</option>
                @foreach($roles as $rol)
                    <option value="{{ $rol->id }}">
                        {{ $rol->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <select name="estado" id="estado" class="form-select py-2 px-3 rounded-4 col-2 input-focus bg-transparent">
                <option value="todas">Todos los trabajadores</option>
                <option value="1">Activos</option>
                <option value="2">Inactivos</option>
            </select>
        </div>
    </div>
</div>

<div class="border rounded-4 overflow-hidden shadow-sm">
    <table class="table mb-0 align-middle">
        <thead class="table-light">
            <tr>
                <th class="px-4 py-12">TRABAJADOR</th>
                <th class="px-4 py-12">CONTACTO</th>
                <th class="px-4 py-12">ROL</th>
                <th class="px-4 py-12">BIBLIOTECA</th>
                <th class="px-4 py-12">FECHA ALTA</th>
                <th class="px-4 py-12">ESTADO</th>
                <th class="px-4 py-12">ACCIONES</th>
            </tr>
        </thead>
        <tbody>
            @foreach($usuarios as $usuario)
            <tr>
                <td class="px-4 py-3">
                    <div>
                        <p class="m-0 fw-semibold">{{ $usuario->nombre }}</p>
                        <p class="m-0 fs-7">{{ $usuario->correo }}</p>
                    </div>
                </td>
                <td class="px-4 py-3 fs-7">{{ $usuario->telefono }}</td>
                <td class="px-4 py-3 fs-7">{{ $usuario->rol->nombre }}</td>
                <td class="px-4 py-3 fs-7">{{ $usuario->biblioteca->nombre }}</td>
                <td class="px-4 py-3 fs-7">{{ $usuario->created_at->format('d/m/Y') }}</td>
                <td class="px-4 py-3">
                    <!-- color verde con icono de caja -->
                    @if($usuario->es_activo === 1)
                        <div class="d-flex flex-wrap align-items-center disponible rounded-3 px-2 py-1 gap-1 fw-semibold" style="width: fit-content;">
                            <i class="bi bi-check2-circle fs-8"></i>
                            <span class="fs-8">Activo</span>
                        </div>
                    @endif
                    <!-- color gris -->
                    @if($usuario->es_activo === 0)   
                        <div class="d-flex flex-wrap align-items-center no-disponible rounded-3 px-2 py-1 gap-1 fw-semibold" style="width: fit-content;">
                            <i class="bi bi-x-circle fs-8"></i>
                            <span class="fs-8">Inactivo</span>
                        </div>
                    @endif
                </td>
                <td class="px-4 py-3">
                    <div class="d-flex wrap-flex gap-4">
                        @if($usuario->es_activo)
                            <button class="bg-transparent border-0" data-bs-toggle="modal" 
                                    data-bs-target="#registroModal"
                                    data-id="{{ $usuario->id }}"
                                    data-nombre="{{ $usuario->nombre }}"
                                    data-correo="{{ $usuario->correo }}" 
                                    data-telefono="{{ $usuario->telefono }}"
                                    data-rol="{{ $usuario->rol_id }}"
                                    data-biblioteca="{{ $usuario->biblioteca_id }}">
                                <i class="bi bi-pencil-square icono-editar"></i>
                            </button>
                            <button class="bg-transparent border-0 " onclick="abrirModalPassword('{{ $usuario->id }}', '{{ $usuario->nombre }}', '{{ $usuario->biblioteca->nombre }}')">
                                <i class="bi bi-key text-warning"></i>
                            </button>
                            <button class="bg-transparent border-0 " onclick="confirmarEliminar('{{ $usuario->id }}')">
                                <i class="bi bi-trash icono-eliminar"></i>
                            </button>
                        @else
                            <button class="bg-transparent border-0 " onclick="reactivarTrabajador('{{ $usuario->id }}')">
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
                    <h2 class="fs-4 fw-semibold mb-2 titulo-modal mb-2" id="modalTitle">Nuevo Trabajador</h2>
                    <form id="registerForm"  method="POST" class="d-flex flex-column gap-3">
                        @csrf
                        <input type="hidden" id="editing_id" name="editing_id" value="{{ old('editing_id') }}">
                        <div class="row gx-3">
                            <div class="col-6">
                                <label for="nombre" class="fs-7 icono-editar fw-semibold mb-1">Nombre completo</label>
                                <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" class="form-control rounded-3 input-focus py-2 @error('nombre') is-invalid @enderror">
                                @error('nombre')
                                    <div class="invalid-feedback fs-8">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-6">
                                <label for="correo" class="fs-7 icono-editar fw-semibold mb-1">Correo</label>
                                <input type="email" id="correo" name="correo" value="{{ old('correo') }}" class="form-control rounded-3 input-focus py-2 @error('correo') is-invalid @enderror">
                                @error('correo')
                                    <div class="invalid-feedback fs-8">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row gx-3">
                            <div class="col-6">
                                <label for="telefono" class="fs-7 icono-editar fw-semibold mb-1">Teléfono</label>
                                <input type="text" id="telefono" name="telefono" value="{{ old('telefono') }}" class="form-control rounded-3 input-focus py-2 @error('telefono') is-invalid @enderror">
                                @error('telefono')
                                    <div class="invalid-feedback fs-8">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-6">
                                <label for="rol" class="fs-7 icono-editar fw-semibold mb-1">Rol</label>
                                <select name="rol_id" id="rol"  class="form-select py-2 px-3 rounded-3 col-2 input-focus bg-transparent @error('rol_id') is-invalid @enderror">
                                    <option value="" selected disabled>Seleccione un rol</option>
                                    @foreach($roles as $rol)
                                        <option value="{{ $rol->id }}" {{ old('rol') == $rol->id ? 'selected' : '' }}>
                                            {{ $rol->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('rol_id')
                                    <div class="invalid-feedback fs-8">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div >
                                <label for="contrasena" class="fs-7 icono-editar fw-semibold mb-1 mt-0">Contraseña</label>
                                <input type="password" id="contrasena" name="contrasena" value="{{ old('contrasena') }}" class="form-control rounded-3 input-focus py-2 @error('contrasena') is-invalid @enderror">
                                @error('contrasena')
                                    <div class="invalid-feedback fs-8">{{ $message }}</div>
                                @enderror
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

<div class="modal fade" id="passwordModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-header border-0 pb-0 px-4">
                <h5 class="modal-title fw-semibold">Cambiar Contraseña</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="passwordForm" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <p class="text-muted fs-7">Usuario: <strong id="passNombreUsuario"></strong> <span id="passBibliotecaUsuario"></span></p>
                    
                    <div class="mb-3">
                        <label class="fs-7 fw-semibold mb-1">Nueva Contraseña</label>
                        <input type="password" name="nueva_contrasena" 
                            class="form-control @if($errors->has('nueva_contrasena')) is-invalid @endif">
                        @if($errors->has('nueva_contrasena'))
                            <div class="invalid-feedback">{{ $errors->first('nueva_contrasena') }}</div>
                        @endif
                    </div>

                    <div class="mb-4">
                        <label class="fs-7 fw-semibold mb-1">Confirmar Contraseña</label>
                        <input type="password" name="confirmar_contrasena" 
                            class="form-control @if($errors->has('confirmar_contrasena')) is-invalid @endif">
                        @if($errors->has('confirmar_contrasena') && !$errors->has('nueva_contrasena'))
                            <div class="invalid-feedback">{{ $errors->first('confirmar_contrasena') }}</div>
                        @endif
                    </div>
                    <div class="row g-3">
                            <div class="col-6">
                                <button type="button" class="w-100 btn bg-transparent border rounded-3 px-4 py-2" data-bs-dismiss="modal">Cancelar</button>
                            </div>
                           <div class="col-6">
                                <button type="submit" class="w-100 btn btn-naranja rounded-3 px-4 py-2" id="btnCambiarPassword">Cambiar contraseña</button>
                           </div>
                    </div>
                </div>
                    
            </form>
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
                modalTitle.textContent = 'Editar Trabajador';
                btnModal.textContent = 'Actualizar';
                registerForm.action = '/usuarios/editar/' + id;
                document.getElementById('editing_id').value = id;
                document.getElementById('nombre').value = boton.getAttribute('data-nombre');
                document.getElementById('correo').value = boton.getAttribute('data-correo');
                document.getElementById('telefono').value = boton.getAttribute('data-telefono');
                document.getElementById('rol').value = boton.getAttribute('data-rol'); 
                document.getElementById('biblioteca').value = boton.getAttribute('data-biblioteca');

                document.getElementById('contrasena').parentElement.style.display = 'none'
            } else if (boton){
                modalTitle.textContent = 'Nuevo Trabajador';
                btnModal.textContent = 'Registrar';
                registerForm.action = "{{ route('usuario.store') }}";
                document.getElementById('biblioteca').value = "";
                document.getElementById('rol').value = "";
                document.getElementById('contrasena').parentElement.style.display = 'block'
                registerForm.reset();
            }
        })

        modalRegistrar.addEventListener('hidden.bs.modal', () => {
            registerForm.reset();
            registerForm.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        });

        function abrirModalPassword(id, nombre,biblioteca) {
            let modalRegistroElement = document.getElementById('registroModal');
            let modalRegistroBus = bootstrap.Modal.getInstance(modalRegistroElement);
            if (modalRegistroBus) {
                modalRegistroBus.hide();
            }
            let modal = new bootstrap.Modal(document.getElementById('passwordModal'));
            let form = document.getElementById('passwordForm');
            document.getElementById('passNombreUsuario').textContent = nombre;
            document.getElementById('passBibliotecaUsuario').textContent =" ("+ biblioteca + ")";
            form.action = '/usuarios/password/' + id;
            modal.show();
        }

        function confirmarEliminar(id) {
            Swal.fire({
                title: '¿Desactivar trabajador?',
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
                    form.action = '/usuarios/eliminar/' + id;
                    form.innerHTML = `@csrf`;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
        function reactivarTrabajador(id) {
            Swal.fire({
                title: '¿Reactivar trabajador?',
                text: "El trabajador volverá a estar disponible.",
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
                    form.action = '/usuarios/reactivar/' + id;
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
    @if ($errors->has('nueva_contrasena') || $errors->has('confirmar_contrasena'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('passNombreUsuario').textContent = "{{ session('nom_pass_error') }}";
                document.getElementById('passBibliotecaUsuario').textContent = " ({{ session('bib_pass_error') }})";
                
                document.getElementById('passwordForm').action = '/usuarios/password/' + "{{ session('id_pass_error') }}";
                
                var modalPassword = new bootstrap.Modal(document.getElementById('passwordModal'));
                modalPassword.show();
            });
        </script>
    @elseif ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var modalElemento = document.getElementById('registroModal');
                var modal = new bootstrap.Modal(modalElemento);
                
                let editarID = "{{ old('editing_id') }}"; 

                if (editarID) {
                    document.getElementById('modalTitle').textContent = 'Editar Trabajador';
                    document.getElementById('btnModal').textContent = 'Actualizar';
                    document.getElementById('registerForm').action = '/usuarios/editar/' + editarID;
                    document.getElementById('contrasena').parentElement.style.display = 'none';
                } else {
                    document.getElementById('modalTitle').textContent = 'Nuevo trabajador';
                    document.getElementById('btnModal').textContent = 'Registrar';
                    document.getElementById('registerForm').action = "{{ route('usuario.store') }}";
                    document.getElementById('contrasena').parentElement.style.display = 'block';
                }

                modal.show();
            });
        </script>
    @endif
@endsection
</html>