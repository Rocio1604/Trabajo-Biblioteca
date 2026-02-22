@extends('layout.menu')

@section('title', 'Gestión de Préstamos')

@section('content')
<div class="container-fluid">


    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-arrow-left-right me-2"></i>Gestión de Préstamos
            </h5>
            <button class="btn btn-naranja btn-sm" data-bs-toggle="modal" data-bs-target="#registroModal">
                <i class="bi bi-plus-lg me-1"></i>Nuevo Préstamo
            </button>
        </div>

        <div class="card-body">

            {{-- Filtros --}}
            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <input type="text" id="busqueda" class="form-control" placeholder="Buscar por socio, libro o número de préstamo...">
                </div>
                <div class="col-md-2">
                    <select id="filtroEstado" class="form-select">
                        <option value="todos">Todos los estados</option>
                        @foreach($estados as $estado)
                            <option value="{{ $estado->id }}">{{ $estado->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select id="filtroBiblio" class="form-select">
                        <option value="todos">Todas las bibliotecas</option>
                        @foreach($bibliotecas as $biblioteca)
                            <option value="{{ $biblioteca->id }}">{{ $biblioteca->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button id="btnBuscar" class="btn btn-naranja w-100">
                        <i class="bi bi-search me-1"></i>Buscar
                    </button>
                </div>
            </div>

            {{-- Tabla --}}
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle">
                    <thead class="bg-white">
                        <tr class="text-center">
                            <th>Nº Préstamo</th>
                            <th>Socio</th>
                            <th>Biblioteca</th>
                            <th>Libro</th>
                            <th>Fecha Préstamo</th>
                            <th>Fecha Devolución</th>
                            <th>Multa</th>
                            <th>Estado</th>
                            <th>¿Es activo?</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tablaPrestamos">
                        @forelse($prestamos as $prestamo)
                            <tr @if($prestamo->es_activo==0) class="table-light opacity-50" @endif>
                                <td>{{ $prestamo->numero_prestamo }}</td>
                                <td>{{ $prestamo->socio->nombre }}</td>
                                <td>{{ $prestamo->biblioteca->nombre }}</td>
                                <td>{{ $prestamo->ejemplar->libro->titulo }}</td>
                                <td>{{ $prestamo->fecha_prestamo->format('d/m/Y') }}</td>
                                <td>{{ $prestamo->fecha_devolucion->format('d/m/Y') }}</td>
                                @php
                                    $hoy = \Carbon\Carbon::today();
                                    $vencimiento = \Carbon\Carbon::parse($prestamo->fecha_devolucion);
                                    
                                    if ($prestamo->estado_id == 2 || $prestamo->estado_id == 4) {
                                        $multaMostrada = $prestamo->multa;
                                    } else {
                                        if ($hoy->gt($vencimiento)) {
                                            $dias = $vencimiento->diffInDays($hoy);
                                            $multaMostrada = floor($dias / 30) * 5;
                                        } else {
                                            $multaMostrada = 0;
                                        }
                                    }
                                @endphp
                                <td>€{{ number_format($multaMostrada, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $prestamo->estado_id == 1 ? 'warning' : ($prestamo->estado_id == 2 ? 'success' : 'danger') }}">
                                        {{ $prestamo->estado->nombre }}
                                    </span>
                                </td>
                                <td>@if($prestamo->es_activo)
                                    Sí
                                    @else
                                    No
                                    @endif
                                </td>
                               <td>
                                <div class="d-flex wrap-flex gap-3 justify-content-center">
                                    @if($prestamo->es_activo == 0)
                                        {{-- Solo muestra Reactivar si fue Perdido (4) o Atrasado (3) --}}
                                        @if($prestamo->estado_id == 3 || $prestamo->estado_id == 4)
                                            <button class="border-0 bg-transparent text-primary" 
                                                    onclick="confirmarReactivar('{{ $prestamo->id }}','prestamo','prestamos')" 
                                                    title="Reactivar préstamo" 
                                                    style="cursor: pointer;">
                                                <i class="bi bi-arrow-counterclockwise text-success" style="font-size: 1.2rem;"></i>
                                            </button>
                                        @endif
                                    @else
                                        {{-- CASO: ACTIVO --}}
                                        @if($prestamo->estado_id == 1 || $prestamo->estado_id == 3)
                                            <button class="border-0 bg-transparent text-success" style="cursor: pointer;" title="Devolver libro" onclick="confirmarDevolucion('{{ $prestamo->id }}', '{{ $multaMostrada }}')">
                                                <i class="bi bi-arrow-return-left"></i>
                                            </button>
                                            <button class="border-0 bg-transparent text-danger" onclick="confirmarPerdido('{{ $prestamo->id }}', '{{ $multaMostrada }}', '{{ $prestamo->ejemplar->libro->precio }}')" style="cursor: pointer;" title="Libro perdido">
                                                <i class="bi bi-journal-x"></i>
                                            </button>
                                            
                                            @if($prestamo->estado_id == 1)
                                                <button class="border-0 bg-transparent btn-editar" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#registroModal"
                                                    data-id="{{ $prestamo->id }}"
                                                    data-socio="{{ $prestamo->socio->id }}"
                                                    data-ejemplar="{{ $prestamo->ejemplar->id }}"
                                                    data-fechadev="{{ $prestamo->fecha_devolucion->format('Y-m-d') }}"
                                                    style="cursor: pointer;" title="Editar préstamo">
                                                    <i class="bi bi-pencil-square icono-editar"></i>
                                                </button>
                                            @endif
                                            
                                            <button class="border-0 bg-transparent btn-eliminar" onclick="confirmarEliminar('{{ $prestamo->id }}','prestamo','prestamos')" style="cursor: pointer;" title="Eliminar préstamo">
                                                <i class="bi bi-trash icono-eliminar"></i>
                                            </button>

                                        @elseif($prestamo->estado_id == 2 || $prestamo->estado_id == 4)
                                            @if($prestamo->estado_id == 4)
                                                <button class="border-0 bg-transparent text-success" onclick="confirmarEncontrado('{{ $prestamo->id }}')" title="Marcar como Encontrado">
                                                    <i class="bi bi-box-seam"></i>
                                                </button>
                                            @endif
                                            <button class="border-0 bg-transparent btn-eliminar" onclick="confirmarEliminar('{{ $prestamo->id }}','prestamo','prestamos')" style="cursor: pointer;">
                                                <i class="bi bi-trash icono-eliminar"></i>
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">No hay préstamos registrados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- {{-- ===================== MODAL CREAR ===================== --}} -->
<div class="modal fade" id="registroModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" id="registerForm">
                @csrf
                <input type="hidden" id="editing_id" name="editing_id" value="{{ old('editing_id')}}">
                <div class="modal-header">
                    <h5 class="modal-title fw-semibold" id="modalTitle">
                        <i class="bi bi-plus-circle me-2"></i>Nuevo Préstamo
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Socio <span class="text-danger">*</span></label>
                            <select name="socio_id" class="form-select @error('socio_id') is-invalid @enderror" id="socio_id">
                                <option value="">Seleccione un socio</option>
                                @foreach($socios ?? [] as $socio)
                                    <option value="{{ $socio->id }}" {{ old('socio_id') == $socio->id ? 'selected' : '' }}>{{ $socio->nombre }} - {{ $socio->dni }}</option>
                                @endforeach
                            </select>
                            @error('socio_id')
                                <div class="invalid-feedback fs-8">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Ejemplar <span class="text-danger">*</span></label>
                            <select name="ejemplar_id" id="ejemplar_id" class="form-select @error('ejemplar_id') is-invalid @enderror">
                                <option value="">Seleccione un ejemplar</option>
                                @foreach($ejemplares as $ejemplar)
                                    <option value="{{ $ejemplar->id }}" 
                                            data-disponibilidad="{{ $ejemplar->disponibilidad_id }}" 
                                            data-estado="{{ $ejemplar->es_activo }}">
                                        {{ $ejemplar->libro->titulo }} - Estado: {{ $ejemplar->estado->nombre }}
                                        {{ $ejemplar->es_activo == 0 ? '[Inactivo]' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ejemplar_id')
                                <div class="invalid-feedback fs-8">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Fecha Devolución <span class="text-danger">*</span></label>
                            <input type="date" name="fecha_devolucion" id="fecha_devolucion" class="form-control @error('fecha_devolucion') is-invalid @enderror" value="{{ old('fecha_devolucion') }}" >
                             @error('fecha_devolucion')
                                <div class="invalid-feedback fs-8">{{ $message }}</div>
                            @enderror
                        </div>


                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnModal">
                        <i class="bi bi-save me-1"></i>Guardar Préstamo
                    </button>
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
    let inputEditar = document.getElementById('editing_id');
    let ejemplarSelect = document.getElementById('ejemplar_id');
    let opcionesOriginales = ejemplarSelect.innerHTML;
    
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
        ejemplarSelect.innerHTML = opcionesOriginales;

        let ejemplarIdAsignado = btn.getAttribute('data-ejemplar');
        ejemplarSelect.querySelectorAll('option').forEach(opt => {
            let disp = opt.dataset.disponibilidad;
            let estado = opt.dataset.estado;
            if (opt.value != ejemplarIdAsignado) {
                if (disp != '1' || estado == '0') {
                    opt.remove();
                }
            }
        });

        if (ejemplarIdAsignado) {
            ejemplarSelect.value = ejemplarIdAsignado;
        }

        if (btn.hasAttribute('data-id')) {
            let id = btn.getAttribute('data-id');
            configurarModal('Editar Prestamo', 'Actualizar', `/prestamos/editar/${id}`, id);
           
            document.getElementById('socio_id').value = btn.getAttribute('data-socio');     
            document.getElementById('ejemplar_id').value = btn.getAttribute('data-ejemplar');   
            document.getElementById('fecha_devolucion').value = btn.getAttribute('data-fechadev');
            
        } else {
            registerForm.reset();
            configurarModal('Nuevo Prestamo', 'Registrar', "{{ route('prestamo.store') }}");
            let opcionTemp = document.createElement('option');
            opcionTemp.value = "";
            opcionTemp.textContent = "Seleccione un ejemplar";
            ejemplarSelect.prepend(opcionTemp);
            ejemplarSelect.value = "";
            let socio = document.getElementById('socio_id');
            let ejemplar = document.getElementById('ejemplar_id');
            if (ejemplar) {
                ejemplar.querySelectorAll('option').forEach(opt => opt.removeAttribute('selected'));
            }
            if (socio) {
                socio.querySelectorAll('option').forEach(opt => opt.removeAttribute('selected'));
            }
            
            document.getElementById('fecha_devolucion').value =""
                
            }
    });

    modalRegistrar.addEventListener('hidden.bs.modal', () => {
        ejemplarSelect.innerHTML = opcionesOriginales;
        registerForm.reset();
        limpiarErrorValidacion();
        inputEditar.value = "";
    });

    document.addEventListener('DOMContentLoaded', function () {
        // Búsqueda
        document.getElementById('btnBuscar').addEventListener('click', buscarPrestamos);
        document.getElementById('busqueda').addEventListener('keyup', function(e) {
            if (e.key === 'Enter') buscarPrestamos();
        });
        document.getElementById('filtroEstado').addEventListener('change', buscarPrestamos);
        document.getElementById('filtroBiblio').addEventListener('change', buscarPrestamos);
        function buscarPrestamos() {
            const busqueda = document.getElementById('busqueda').value;
            const estado = document.getElementById('filtroEstado').value;
            const biblioteca = document.getElementById('filtroBiblio').value;

            fetch('{{ route("prestamo.buscar") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ busqueda, estado,biblioteca })
            })
            .then(r => r.json())
            .then(data => actualizarTabla(data))
            .catch(e => {
                console.log("Error detallado:", e);
                alert('Error: ' + e.message);
            });
        }

        function actualizarTabla(prestamos) {
            const tbody = document.getElementById('tablaPrestamos');
            if (prestamos.length === 0) {
                tbody.innerHTML = '<tr><td colspan="10" class="text-center text-muted py-4">No se encontraron préstamos</td></tr>';
                return;
            }
            tbody.innerHTML = prestamos.map(p => {
                const hoy = new Date();
                hoy.setHours(0, 0, 0, 0);

                const fechaP = new Date(p.fecha_prestamo).toLocaleDateString('es-ES');
                const fechaD = new Date(p.fecha_devolucion).toLocaleDateString('es-ES');
                const vencimiento = new Date(p.fecha_devolucion);
                vencimiento.setHours(0, 0, 0, 0);
                const badge = p.estado_id == 1 ? 'warning' : (p.estado_id == 2 ? 'success' : 'danger');
                let multaMostrada = 0;
                if (p.estado_id == 2 || p.estado_id == 4) {
                    multaMostrada = p.multa;
                } else {
                    if (hoy.getTime() > vencimiento.getTime()) {
                        const diffTime = hoy - vencimiento;
                        const dias = Math.floor(diffTime / (1000 * 60 * 60 * 24));
                        multaMostrada = Math.floor(dias / 30) * 5;
                    } else {
                        multaMostrada = 0;
                    }
                }
                const filaClase = p.es_activo == 0 ? 'table-light opacity-50' : '';

                return `<tr class="${filaClase}">
                            <td>${p.numero_prestamo}</td>
                            <td>${p.socio.nombre}</td>
                            <td>${p.biblioteca.nombre}</td>
                            <td>${p.ejemplar.libro.titulo}</td>
                            <td>${fechaP}</td>
                            <td>${fechaD}</td>
                            <td>€${parseFloat(multaMostrada).toFixed(2)}</td>
                            <td>
                                <span class="badge bg-${p.estado_id == 1 ? 'warning' : (p.estado_id == 2 ? 'success' : 'danger')}">
                                    ${p.estado.nombre}
                                </span>
                            </td>
                            <td>${p.es_activo ? 'Sí' : 'No'}</td>
                            <td>
                                <div class="d-flex wrap-flex gap-3 justify-content-center">
                                    ${p.es_activo == 0 ? `
                                        ${(p.estado_id == 3 || p.estado_id == 4) ? `
                                            <button class="border-0 bg-transparent text-primary" 
                                                    onclick="confirmarReactivar('${p.id}','prestamo','prestamos')" 
                                                    title="Reactivar préstamo" 
                                                    style="cursor: pointer;">
                                                <i class="bi bi-arrow-counterclockwise text-success" style="font-size: 1.2rem;"></i>
                                            </button>
                                        ` : ''}
                                    ` : `
                                        ${(p.estado_id == 1 || p.estado_id == 3) ? `
                                            <button class="border-0 bg-transparent text-success" style="cursor: pointer;" title="Devolver libro" onclick="confirmarDevolucion('${p.id}', '${multaMostrada}')">
                                                <i class="bi bi-arrow-return-left"></i>
                                            </button>
                                            <button class="border-0 bg-transparent text-danger" onclick="confirmarPerdido('${p.id}', '${multaMostrada}', '${p.ejemplar.libro.precio}')" style="cursor: pointer;" title="Libro perdido">
                                                <i class="bi bi-journal-x"></i>
                                            </button>
                                            
                                            ${p.estado_id == 1 ? `
                                                <button class="border-0 bg-transparent btn-editar" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#registroModal"
                                                    data-id="${p.id}"
                                                    data-socio="${p.socio_id}"
                                                    data-ejemplar="${p.ejemplar_id}"
                                                    data-fechadev="${p.fecha_devolucion}"
                                                    style="cursor: pointer;" title="Editar préstamo">
                                                    <i class="bi bi-pencil-square icono-editar"></i>
                                                </button>
                                            ` : ''}
                                            
                                            <button class="border-0 bg-transparent btn-eliminar" onclick="confirmarEliminar('${p.id}','prestamo','prestamos')" style="cursor: pointer;" title="Eliminar préstamo">
                                                <i class="bi bi-trash icono-eliminar"></i>
                                            </button>

                                        ` : (p.estado_id == 2 || p.estado_id == 4) ? `
                                            ${p.estado_id == 4 ? `
                                                <button class="border-0 bg-transparent text-success" onclick="confirmarEncontrado('${p.id}')" title="Marcar como Encontrado">
                                                    <i class="bi bi-box-seam"></i>
                                                </button>
                                            ` : ''}
                                            <button class="border-0 bg-transparent btn-eliminar" onclick="confirmarEliminar('${p.id}','prestamo','prestamos')" style="cursor: pointer;">
                                                <i class="bi bi-trash icono-eliminar"></i>
                                            </button>
                                        ` : ''}
                                    `}
                                </div>
                            </td>
                        </tr>`;
            }).join('');
        }

    });

    function confirmarDevolucion(id, multa) {
        let tieneMulta=0 ;
        let montoMulta = parseInt(multa);
        if(montoMulta>0) tieneMulta=montoMulta;

        let titulo = tieneMulta ? '¡Préstamo con Multa!' : '¿Confirmar devolución?';
        let icono = tieneMulta ? 'warning' : 'question';
        let textoBoton = tieneMulta ? 'Sí, generar multa y devolver' : 'Sí, devolver libro';
        
        let mensaje = tieneMulta 
            ? `Se generará un recibo de €${montoMulta} automáticamente.` 
            : "El libro volverá a estar disponible.";

        Swal.fire({
            title: titulo,
            text: mensaje,
            icon: icono,
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: textoBoton,
            cancelButtonText: 'Cancelar',
            customClass: { popup: 'rounded-4' }
        }).then((result) => {
            if (result.isConfirmed) {
                let form = document.createElement('form');
                form.method = 'POST';
                form.action = '/prestamos/' + id + '/devolver'; 
                form.innerHTML = `
                    @csrf
                    <input type="hidden" name="monto_multa" value="${montoMulta}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function confirmarPerdido(id, multaAtraso, precioLibro) {
        let atraso = parseFloat(multaAtraso) || 0;
        let precio = parseFloat(precioLibro) || 0;
        let mensaje = "";
        
        if (atraso > 0) {
            mensaje = `Se generará un recibo por retraso de €${atraso} y un cargo por pérdida de libro.`;
        } else {
            mensaje = `Se generará un recibo por pérdida de libro:<br> €5 (si es 1º vez) o €${precio.toFixed(2)}. si es reincidente.`;
        }

        Swal.fire({
            title: '¿Confirmar libro perdido?',
            html: mensaje,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, confirmar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                let form = document.createElement('form');
                form.method = 'POST';
                form.action = '/prestamos/' + id + '/perdido'; 

                form.innerHTML = `
                    @csrf
                    <input type="hidden" name="multa_atraso" value="${atraso}">
                    <input type="hidden" name="precio_libro" value="${precio}">
                `;

                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function confirmarEncontrado(id){
        Swal.fire({
            title: '¿Confirmar libro encontrado?',
            text: "El libro volverá a estar disponible",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, confirmar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                let form = document.createElement('form');
                form.method = 'POST';
                form.action = '/prestamos/' + id + '/encontrado'; 

                form.innerHTML = `@csrf`;

                document.body.appendChild(form);
                form.submit();
            }
        });
    }
    registerForm.addEventListener('submit', function() {
        let btnSubmit = document.getElementById('btnModal');
        btnSubmit.disabled = true;
        btnSubmit.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...`;
    });

</script>
<!-- Errores de validación -->
    @if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let editarID = "{{ old('editing_id') }}";
            let modal = new bootstrap.Modal(document.getElementById('registroModal'));
            ejemplarSelect.innerHTML = opcionesOriginales; 
        if (editarID) {
                configurarModal('Editar Prestamo', 'Actualizar', `/prestamos/editar/${editarID}`, editarID);
                let ejemplarIdAsignado = "{{ old('ejemplar_id') }}";
                ejemplarSelect.querySelectorAll('option').forEach(opt => {
                    let disp = opt.dataset.disponibilidad;
                    let estado = opt.dataset.estado;
                    if (opt.value != ejemplarIdAsignado) {
                        if (disp != '1' || estado == '0') {
                            opt.remove();
                        }
                    }
                });
            } else {
                let ejemplarIdAsignado = "{{ old('ejemplar_id') }}";
                ejemplarSelect.querySelectorAll('option').forEach(opt => {
                    let disp = opt.dataset.disponibilidad;
                    let estado = opt.dataset.estado;
                    if (opt.value != ejemplarIdAsignado) {
                        if (disp != '1' || estado == '0') {
                            opt.remove();
                        }
                    }
                });
                configurarModal('Nuevo Prestamo', 'Registrar', "{{ route('prestamo.store') }}");
            }
            modal.show();
        });
    </script>
    @endif
@endsection