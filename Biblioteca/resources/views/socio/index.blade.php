@extends('layout.menu')
@section('title', 'Socios')
@section('content')
<div class="mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <div>
            <h1 class="fs-2 mb-2">Gestión de Socios</h1>
            <p class="m-0">Administración de socios y cuotas anuales</p>
        </div>
        <button type="button" class="btn btn-naranja rounded-3 d-flex align-items-center justify-content-center gap-2 px-3 py-2" data-bs-toggle="modal" data-bs-target="#registrarSocio">
                        <i class="bi bi-plus-lg fs-5"></i>Nuevo Socio</button>
    </div>
    <div class="row g-3">
        <div class="col-12 col-xl-6">
            <div class="input-group rounded-4  bg-white  shadow-sm input-focus">
                <span class="input-group-text border-0 bg-transparent ps-3">
                    <i class="bi bi-search fs-5 color-input"></i>
                </span>
                
                <input type="text" id="buscador" 
                    class="form-control border-0 py-2 bg-transparent" 
                    placeholder="Buscar por nombre, email o DNI...">
                
                <button id="buscar" class="btn btn-naranja px-4 d-flex align-items-center gap-2">
                    <i class="bi bi-search"></i>
                    <span class="d-none d-sm-inline">Buscar</span>
                </button>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <select name="cuota" id="cuota" class="form-select py-2 px-3 rounded-4 col-2 input-focus bg-transparent">
                <option value="todas">Todas estado cuotas</option>
                @foreach($estados as $estado)
                    <option value="{{ $estado->id }}">
                        Cuota {{ $estado->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <select name="estado" id="estado" class="form-select py-2 px-3 rounded-4 col-2 input-focus bg-transparent">
                <option value="todas">Todas estado socio</option>
                <option value="1">Activo</option>
                <option value="0">Inactivo</option>
            </select>
        </div>
    </div>
</div>

<div class="border rounded-4 overflow-hidden shadow-sm">
    <table class="table mb-0 align-middle">
        <thead class="table-light">
            <tr>
                <th class="px-4 py-12">SOCIO</th>
                <th class="px-4 py-12">CONTACTO</th>
                <th class="px-4 py-12">DNI</th>
                <th class="px-4 py-12">BIBLIOTECA</th>
                <th class="px-4 py-12">ESTADO CUOTA</th>
                <th class="px-4 py-12">¿ACTIVO?</th>
                <th class="px-4 py-12">ACCIONES</th>
            </tr>
        </thead>
        <tbody id="tablaSocios">
            @foreach($socios as $socio)
            <tr @if($socio->es_activo==0) class="table-light opacity-50" @endif>
                <td class="px-4 py-3">
                    <div>
                        <p class="m-0 fw-semibold">{{ $socio->nombre }}</p>
                        <p class="m-0 fs-7">Alta: {{ $socio->created_at->format('d/m/Y') }}</p>
                    </div>
                </td>
                <td class="px-4 py-3">
                    <div>
                        <p class="m-0 fs-7">{{ $socio->email }}</p>
                        <p class="m-0 fs-7">{{ $socio->telefono }}</p>
                    </div>
                </td>
                <td class="px-4 py-3 fs-7">{{ $socio->dni }}</td>
                <td class="px-4 py-3 fs-7">{{ $socio->biblioteca->nombre }}</td>
                <td class="px-4 py-3">
                    @if($socio->estado)
                        @if($socio->estado->nombre === 'Vencida')
                            <div class="d-flex flex-wrap align-items-center no-disponible rounded-3 px-2 py-1 gap-1 fw-semibold" style="width: fit-content;">
                                <i class="bi bi-x-circle fs-8"></i>
                                <span class="fs-8">Vencida</span>
                            </div>
                        @elseif($socio->estado->nombre === 'Activa')   
                            <div class="d-flex flex-wrap align-items-center disponible rounded-3 px-2 py-1 gap-1 fw-semibold" style="width: fit-content;">
                                <i class="bi bi-check2-circle fs-8"></i>
                                <span class="fs-8">Activa</span>
                            </div>
                        @else
                            <span class="text-muted fs-8">{{ $socio->estado->nombre }}</span>
                        @endif
                    @else
                        <span class="text-danger fs-8">Sin estado</span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    @if($socio->es_activo)
                    Sí
                    @else
                    No
                    @endif
                </td>
                <td class="px-4 py-3">
                    <div class="d-flex wrap-flex gap-4">
                        @if($socio->es_activo)
                            <button class="bg-transparent border-0" data-bs-toggle="modal" 
                                    data-bs-target="#registrarSocio"
                                    data-id="{{ $socio->id }}"
                                    data-nombre="{{ $socio->nombre }}"
                                    data-dni="{{ $socio->dni }}"
                                    data-email="{{ $socio->email }}"
                                    data-telefono="{{ $socio->telefono }}"
                                    data-biblioteca="{{ $socio->biblioteca_id }}">
                                <i class="bi bi-pencil-square icono-editar"></i>
                            </button>
                            <button class="bg-transparent border-0 " onclick="confirmarEliminar('{{ $socio->id }}','socio','socios')">
                                <i class="bi bi-trash icono-eliminar"></i>
                            </button>
                        @else
                            <button class="bg-transparent border-0 " onclick="confirmarReactivar('{{ $socio->id }}','socio','socios')">
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

<div class="modal fade" id="registrarSocio" tabindex="-1"
    data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-header border-0 p-3">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 pb-4 pt-0">
                    <h2 class="fs-4 fw-semibold mb-2 titulo-modal mb-2" id="modalTitle">Nuevo socio</h2>
                    <form id="registerForm"  method="POST" class="d-flex flex-column gap-3">
                        @csrf
                        <input type="hidden" id="editing_id" name="editing_id" value="{{ old('editing_id') }}">
                        <div class="row gx-3">
                            <div class="col-6">
                                <label for="dni" class="fs-7 icono-editar fw-semibold mb-1">DNI</label>
                                <input type="text" id="dni" name="dni" value="{{ old('dni') }}" class="form-control rounded-3 input-focus py-2 @error('dni') is-invalid @enderror">
                                @error('dni')
                                    <div class="invalid-feedback fs-8">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-6">
                                <label for="nombre" class="fs-7 icono-editar fw-semibold mb-1">Nombre completo</label>
                                <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" class="form-control rounded-3 input-focus py-2 @error('nombre') is-invalid @enderror">
                                @error('nombre')
                                    <div class="invalid-feedback fs-8">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row gx-3">
                            <div class="col-6">
                                <label for="email" class="fs-7 icono-editar fw-semibold mb-1">Email</label>
                                <input type="text" id="email" name="email" value="{{ old('email') }}" class="form-control rounded-3 input-focus py-2 @error('email') is-invalid @enderror">
                                @error('email')
                                    <div class="invalid-feedback fs-8">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-6">
                                <label for="telefono" class="fs-7 icono-editar fw-semibold mb-1">Teléfono</label>
                                <input type="text" id="telefono" name="telefono" value="{{ old('telefono') }}" class="form-control rounded-3 input-focus py-2 @error('telefono') is-invalid @enderror">
                                @error('telefono')
                                    <div class="invalid-feedback fs-8">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                                <label for="biblioteca" class="fs-7 icono-editar fw-semibold mb-1 mt-0">Biblioteca</label>
                                <select name="biblioteca_id" id="biblioteca"  class="form-select py-2 px-3 rounded-3 col-2 input-focus bg-transparent @error('biblioteca') is-invalid @enderror">
                                    <option value="">Seleccione una biblioteca</option>
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
        let modalRegistrar = document.querySelector("#registrarSocio");
        let registerForm = document.querySelector("#registerForm");
        let modalTitle = document.getElementById('modalTitle');
        let btnModal = document.getElementById('btnModal');
        let btnBuscar = document.getElementById('buscar');
        let selectCuota = document.getElementById('cuota');
        let selectEstado = document.getElementById('estado');
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

        modalRegistrar.addEventListener('show.bs.modal',(event)=>{
                let boton = event.relatedTarget;
                if (!boton) return;
                if (boton.hasAttribute('data-id')) {
                    let id = boton.getAttribute('data-id');
                    configurarModal('Editar socio', 'Actualizar', '/socios/editar/' + id, id);
                    document.getElementById('editing_id').value = id;
                    document.getElementById('dni').value = boton.getAttribute('data-dni');
                    document.getElementById('nombre').value = boton.getAttribute('data-nombre');
                    document.getElementById('email').value = boton.getAttribute('data-email');
                    document.getElementById('telefono').value = boton.getAttribute('data-telefono');
                    document.getElementById('biblioteca').value = boton.getAttribute('data-biblioteca');
                } else{
                    configurarModal('Nuevo socio', 'Registrar', "{{ route('socio.store') }}");
                    if (biblioteca) {
                        biblioteca.querySelectorAll('option').forEach(opt => opt.removeAttribute('selected'));
                    }
                    registerForm.reset();
                    document.getElementById('dni').value = "";
                    document.getElementById('nombre').value = "";
                    document.getElementById('email').value = "";
                    document.getElementById('telefono').value = "";
                    document.getElementById('biblioteca').value = "";
                }
        });

        modalRegistrar.addEventListener('hidden.bs.modal', () => {
            registerForm.reset();
            limpiarErrorValidacion();
            inputEditar.value = "";
        });
        
        document.addEventListener('DOMContentLoaded', function() {
            selectCuota.addEventListener('change', () => {
                btnBuscar.click(); 
            });

            selectEstado.addEventListener('change', () => {
                btnBuscar.click(); 
            });

            btnBuscar.addEventListener('click', () => {
                let inputNombre = document.getElementById('buscador');
                let nombre = inputNombre.value.trim();

                let selectCuota = document.getElementById('cuota');
                let cuota = selectCuota.value;
        
                let selectEstado = document.getElementById('estado');
                let estado = selectEstado.value;
                
                fetch("{{ route('socio.buscar') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        nombre: nombre,
                        cuota: cuota,
                        estado: estado 
                    })
                })
                .then(response => response.json())
                .then(data => {
                    let tablaSocios = document.getElementById('tablaSocios');
                    tablaSocios.innerHTML = '';

                    if (data.length === 0) {
                        tablaSocios.innerHTML = '<tr><td colspan="7" class="text-center text-muted py-4">No se encontraron resultados</td></tr>';
                        return;
                    }

                    data.forEach(socio => {
                        let estadoBadge = '';
                        if (socio.estado) {
                            if (socio.estado.nombre === 'Vencida') {
                                estadoBadge = `
                                    <div class="d-flex flex-wrap align-items-center no-disponible rounded-3 px-2 py-1 gap-1 fw-semibold" style="width: fit-content;">
                                        <i class="bi bi-x-circle fs-8"></i>
                                        <span class="fs-8">Vencida</span>
                                    </div>
                                `;
                            } else if (socio.estado.nombre === 'Activa') {
                                estadoBadge = `
                                    <div class="d-flex flex-wrap align-items-center disponible rounded-3 px-2 py-1 gap-1 fw-semibold" style="width: fit-content;">
                                        <i class="bi bi-check2-circle fs-8"></i>
                                        <span class="fs-8">Activa</span>
                                    </div>
                                `;
                            } else {
                                estadoBadge = `<span class="text-muted fs-8">${socio.estado.nombre}</span>`;
                            }
                        } else {
                            estadoBadge = '<span class="text-danger fs-8">Sin estado</span>';
                        }

                        let botonesAccion = '';
                        if (socio.es_activo) {
                            botonesAccion = `
                                <button class="bg-transparent border-0" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#registrarSocio"
                                        data-id="${socio.id}"
                                        data-nombre="${socio.nombre}"
                                        data-dni="${socio.dni}"
                                        data-email="${socio.email}"
                                        data-telefono="${socio.telefono}"
                                        data-biblioteca="${socio.biblioteca_id}">
                                    <i class="bi bi-pencil-square icono-editar"></i>
                                </button>
                                <button class="bg-transparent border-0" onclick="confirmarEliminar('${socio.id}')">
                                    <i class="bi bi-trash icono-eliminar"></i>
                                </button>
                            `;
                        } else {
                            botonesAccion = `
                                <button class="bg-transparent border-0" onclick="reactivarSocio('${socio.id}')">
                                    <i class="bi bi-arrow-counterclockwise text-success"></i>
                                </button>
                            `;
                        }

                        let fechaAlta = new Date(socio.created_at).toLocaleDateString('es-ES');
                        let bibliotecaNombre = socio.biblioteca ? socio.biblioteca.nombre : 'N/A';

                        tablaSocios.innerHTML += `
                        <tr>
                            <td class="px-4 py-3">
                                <div>
                                    <p class="m-0 fw-semibold">${socio.nombre}</p>
                                    <p class="m-0 fs-7">Alta: ${fechaAlta}</p>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div>
                                    <p class="m-0 fs-7">${socio.email}</p>
                                    <p class="m-0 fs-7">${socio.telefono}</p>
                                </div>
                            </td>
                            <td class="px-4 py-3 fs-7">${socio.dni}</td>
                            <td class="px-4 py-3 fs-7">${bibliotecaNombre}</td>
                            <td class="px-4 py-3">
                                ${estadoBadge}
                            </td>
                            <td class="px-4 py-3">
                                ${socio.es_activo ? 'Sí' : 'No'}
                            </td>
                            <td class="px-4 py-3">
                                <div class="d-flex wrap-flex gap-4">
                                    ${botonesAccion}
                                </div>
                            </td>
                        </tr>
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
            });

        });
        
    </script>

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var modalElemento = document.getElementById('registrarSocio');
                var modal = new bootstrap.Modal(modalElemento);
                
                let editarID = "{{ old('editing_id') }}"; 

                if (editarID) {
                    configurarModal('Editar socio', 'Actualizar', '/socios/editar/' + editarID, editarID);
                } else {
                    configurarModal('Nuevo socio', 'Registrar', "{{ route('socio.store') }}");
                }
                modal.show();
            });
        </script>
    @endif
@endsection