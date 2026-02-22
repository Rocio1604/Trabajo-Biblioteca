
@extends('layout.menu')

@section('title', 'Gestión de Recibos')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Gestión de Recibos</h3>
                    <button type="button" class="btn btn-naranja d-flex gap-3" data-bs-toggle="modal" data-bs-target="#modalForm">
                        <i class="bi bi-plus-lg"></i>Nuevo Recibo
                    </button>
                </div>
                <!-- Buscadores  -->
                <div class="card-body border-bottom">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" 
                                    id="buscador" 
                                    class="form-control" 
                                    placeholder="Buscar por nombre de socio o número de recibo...">
                            </div>
                    </div>
        
                        
                        <div class="col-12 col-md-2">
                            <select id="selectTipo" class="form-select">
                                <option value="todos">Todos los tipos</option>
                                @foreach($tipos as $tipo)
                                    <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        
                        <div class="col-12 col-md-2">
                            <select id="selectEstado" class="form-select">
                                <option value="todos">Todos los estados</option>
                                @foreach($estados as $estado)
                                    <option value="{{ $estado->id }}">{{ $estado->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-12 col-md-2">
                            <button id="btnBuscar" class="btn btn-naranja w-100">
                                <i class="bi bi-search"></i> Buscar
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Número</th>
                                    <th>Tipo</th>
                                    <th>Socio</th>
                                    <th>Concepto</th>
                                    <th>Fecha</th>
                                    <th>Importe</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tablaRecibos">
                                @forelse($recibos as $recibo)
                                    
                                    <tr>
                                        <td>
                                            <small class="text-muted">{{ $recibo->numero_recibo }}</small>
                                        </td>
                                        <td>
                                            @if($recibo->tipo->id == 1)
                                                <span class="badge bg-info">{{ $recibo->tipo->nombre }}</span>
                                            @else
                                                <span class="badge bg-danger">{{ $recibo->tipo->nombre }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $recibo->socio->nombre }}</td>
                                        <td>{{ Str::limit($recibo->concepto, 40) }}</td>
                                        <td>{{ $recibo->fecha->format('Y-m-d') }}</td>
                                        <td><strong>€{{ number_format($recibo->importe, 2) }}</strong></td>
                                        <td>
                                            @if($recibo->estado_id == 1)
                                                <span class="badge bg-success">{{$recibo->estado->nombre}}</span>
                                            @elseif($recibo->estado_id == 2)
                                                <span class="badge bg-warning text-dark">{{$recibo->estado->nombre}}</span>
                                            @elseif($recibo->estado_id == 3)
                                                <span class="badge bg-danger text-white">{{$recibo->estado->nombre}}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($recibo->es_activo==1 && $recibo->estado_id==2)
                                            <div class="btn-group" role="group">
                                                <button type="button" 
                                                        class="btn btn-sm" 
                                                        onclick="cobrarRecibo('{{ $recibo->id }}', '{{ $recibo->importe }}')">
                                                    <i class="bi bi-cash-stack text-success"></i>
                                                </button>
                                                <!-- Botón Editar -->
                                                <button type="button" 
                                                        class="btn btn-sm" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#modalForm"
                                                        data-id="{{ $recibo->id }}"
                                                        data-socio="{{ $recibo->socio->id }}"
                                                        data-tipo="{{ $recibo->tipo->id }}" 
                                                        data-concepto="{{ $recibo->concepto }}"
                                                        data-importe="{{ $recibo->importe }}">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>

                                                <!-- Botón Eliminar -->
                                                <button class="bg-transparent border-0"
                                                    onclick="confirmarEliminar('{{ $recibo->id }}','recibo','recibos')">
                                                    <i class="bi bi-trash icono-eliminar"></i>
                                                </button>
                                            </div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No hay recibos registrados</td>
                                    </tr>

                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Crear Recibo -->
<div class="modal fade" id="modalForm" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Nuevo Recibo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="registerForm">
                @csrf
                <input type="hidden" id="editing_id" name="editing_id" value="{{ old('editing_id') }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Socio *</label>
                        <select name="socio_id" id="socio_id" class="form-select @error('socio_id') is-invalid @enderror" >
                            <option value="">Seleccione un socio</option>
                            @foreach($socios as $socio)
                                <option value="{{ $socio->id }}" {{ old('socio_id') == $socio->id ? 'selected' : '' }}>
                                    {{ $socio->nombre }} - {{ $socio->dni }}
                                </option>
                            @endforeach
                        </select>
                        @error('socio_id')
                                <div class="invalid-feedback fs-8">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tipo *</label>
                        <select name="tipo_id" id="tipo_id" class="form-select  @error('tipo_id') is-invalid @enderror" >
                            <option value="">Selecciona un tipo</option>
                            @foreach($tipos as $tipo)
                                <option value="{{ $tipo->id }}" {{ old('tipo_id') == $tipo->id ? 'selected' : '' }}>{{ $tipo->nombre }}</option>
                            @endforeach
                        </select>
                        @error('tipo_id')
                                <div class="invalid-feedback fs-8">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Concepto *</label>
                        <input type="text" id="concepto" name="concepto" class="form-control  @error('concepto') is-invalid @enderror" 
                               placeholder="Ej: Cuota anual 2026" value="{{ old('concepto') }}" >
                        @error('concepto')
                            <div class="invalid-feedback fs-8">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Importe (€) *</label>
                        <input type="number" step="0.01" name="importe" id="importe" class="form-control  @error('importe') is-invalid @enderror" 
                               placeholder="50.00" value="{{ old('importe') }}" >
                        @error('importe')
                            <div class="invalid-feedback fs-8">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnModal">Crear Recibo</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>

    let modalRegistrar = document.querySelector("#modalForm");
    let registerForm = document.querySelector("#registerForm");
    let modalTitle = document.getElementById('modalTitle');
    let slTipo = document.getElementById('tipo_id');
    let slSocio = document.getElementById('socio_id');
    let btnModal = document.getElementById('btnModal');
    let btnBuscar = document.getElementById('btnBuscar');
    let inputEditar = document.getElementById('editing_id');

    let metodosPago = @json($metodos);

    function cobrarRecibo(id, monto) {
        Swal.fire({
            title: 'Registrar Pago',
            html: `Monto a cobrar: <strong>${monto}€</strong>`,
            icon: 'info',
            input: 'select',
            inputOptions: metodosPago, 
            inputPlaceholder: 'Seleccione método de pago',
            showCancelButton: true,
            confirmButtonText: 'Confirmar Pago',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#28a745',
            inputValidator: (value) => {
                if (!value) {
                    return 'Seleccione un metodo de pago.';
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                enviarPago(id, result.value);
            }
        });
    }

    function enviarPago(id, metodoId) {
        let form = document.createElement('form');
        form.method = 'POST';
        form.action = `/recibos/pagar/${id}`; 
        
        form.innerHTML = `
            @csrf
            <input type="hidden" name="metodo_id" value="${metodoId}">
        `;
        
        document.body.appendChild(form);
        form.submit();
    }

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
            configurarModal('Editar Recibo', 'Actualizar', '/recibos/editar/' + id, id);
            document.getElementById('editing_id').value = id;
            slSocio.value = boton.getAttribute('data-socio');
            slTipo.value = boton.getAttribute('data-tipo');
            document.getElementById('concepto').value = boton.getAttribute('data-concepto');
            document.getElementById('importe').value = boton.getAttribute('data-importe');
        } else if (boton){
            configurarModal('Nuevo Recibo', 'Registrar', "{{ route('recibo.store') }}");
            registerForm.reset();
            if (slSocio) {
                slSocio.querySelectorAll('option').forEach(opt => opt.removeAttribute('selected'));
            }
            if (slTipo) {
                slTipo.querySelectorAll('option').forEach(opt => opt.removeAttribute('selected'));
            }
            document.getElementById('concepto').value = '';
            document.getElementById('importe').value = '';
        }
    })
    modalRegistrar.addEventListener('hidden.bs.modal', () => {
            registerForm.reset();
            limpiarErrorValidacion();
            inputEditar.value = "";        
    });

    btnBuscar.addEventListener('click', () => {
        let inputBusqueda = document.getElementById('buscador');
        let busqueda = inputBusqueda.value.trim();
        
        let selectTipo = document.getElementById('selectTipo');
        let tipo = selectTipo.value;
        
        let selectEstado = document.getElementById('selectEstado');
        let estado = selectEstado.value;
        
        fetch("{{ route('recibo.buscar') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                busqueda: busqueda,
                tipo: tipo,         
                estado: estado      
            })
        })
        .then(response => response.json())
        .then(data => {
            let tablaRecibos = document.getElementById('tablaRecibos');
            tablaRecibos.innerHTML = '';

            if (data.length === 0) {
                tablaRecibos.innerHTML = '<tr><td colspan="8" class="text-center text-muted py-4">No se encontraron resultados</td></tr>';
                return;
            }

            data.forEach(recibo => {
                
                let tipoBadge = '';
                if (recibo.tipo.id == 1) {
                    tipoBadge = `<span class="badge bg-info">${recibo.tipo.nombre}</span>`;
                } else {
                    tipoBadge = `<span class="badge bg-danger">${recibo.tipo.nombre}</span>`;
                }

                let estadoBadge = '';
                if (recibo.estado.nombre === 'Pagado') {
                    estadoBadge = `<span class="badge bg-success">Pagado</span>`;
                } else {
                    estadoBadge = `<span class="badge bg-warning text-dark">Pendiente</span>`;
                }

                let fecha = new Date(recibo.fecha).toLocaleDateString('es-ES');

                let concepto = recibo.concepto.length > 40 
                    ? recibo.concepto.substring(0, 40) + '...' 
                    : recibo.concepto;

                let importe = parseFloat(recibo.importe).toFixed(2);

                let nombreSocio = recibo.socio ? recibo.socio.nombre : 'N/A';

                let botonesAccion = '';
                if (recibo.es_activo) {
                    botonesAccion = `
                        <div class="btn-group" role="group">
                            <button type="button" 
                                    class="btn btn-sm" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalForm"
                                    data-id="${recibo.id}"
                                    data-socio="${recibo.socio_id}"
                                    data-tipo="${recibo.tipo_id}"
                                    data-concepto="${recibo.concepto}"
                                    data-importe="${recibo.importe}"
                                    title="Editar">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <button type="button" 
                                    class="btn btn-sm" 
                                    onclick="confirmarEliminar(${recibo.id}, 'recibo', 'recibos')"
                                    title="Dar de baja">
                                <i class="bi bi-trash icono-eliminar"></i>
                            </button>
                        </div>
                    `;
                }

                tablaRecibos.innerHTML += `
                <tr>
                    <td>
                        <small class="text-muted">${recibo.numero_recibo}</small>
                    </td>
                    <td>${tipoBadge}</td>
                    <td>${nombreSocio}</td>
                    <td>${concepto}</td>
                    <td>${fecha}</td>
                    <td><strong>€${importe}</strong></td>
                    <td>${estadoBadge}</td>
                    <td>${botonesAccion}</td>
                </tr>
                `;
            });

        })
        .catch(error => {
            console.error('Error:', error);
            alert('No se pudo realizar la búsqueda');
        });
    });


    document.getElementById('buscador').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            btnBuscar.click();
        }
    });

    document.getElementById('selectTipo').addEventListener('change', () => {
        btnBuscar.click();
    });

    document.getElementById('selectEstado').addEventListener('change', () => {
        btnBuscar.click();
    });
</script>
    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var modalElemento = document.getElementById('modalForm');
                var modal = new bootstrap.Modal(modalElemento);
                let editarID = "{{ old('editing_id') }}"; 
                if (editarID) {
                    configurarModal('Editar Recibo', 'Actualizar', '/recibos/editar/' + editarID, editarID);
                } else {
                    configurarModal('Nuevo Recibo', 'Registrar', "{{ route('recibo.store') }}");
                }

                modal.show();
            });
        </script>
    @endif
@endsection