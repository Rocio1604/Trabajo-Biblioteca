
@extends('layout.menu')

@section('title', 'Gestión de Recibos')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Gestión de Recibos</h3>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrearRecibo">
                        <i class="fas fa-plus"></i> Nuevo Recibo
                    </button>
                </div>
                <div class="card-body border-bottom">
                    <div class="row g-3">
                        <div class="col-12 col-md-8">
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
                        <div class="col-12 col-md-4">
                            <button id="btnBuscar" class="btn btn-primary w-100">
                                <i class="bi bi-search"></i> Buscar
                            </button>
                        </div>
                    </div>
                </div>
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

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
                                            @if($recibo->tipo->nombre == 'Suscripcion' || $recibo->tipo->nombre == 'Suscripción')
                                                <span class="badge bg-primary">Suscripción</span>
                                            @else
                                                <span class="badge bg-danger">Multa</span>
                                            @endif
                                        </td>
                                        <td>{{ $recibo->socio->nombre }}</td>
                                        <td>{{ Str::limit($recibo->concepto, 40) }}</td>
                                        <td>{{ $recibo->fecha->format('Y-m-d') }}</td>
                                        <td><strong>€{{ number_format($recibo->importe, 2) }}</strong></td>
                                        <td>
                                            @if($recibo->estado->nombre == 'Pagado')
                                                <span class="badge bg-success">Pagado</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Pendiente</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($recibo->es_activo)
                                            <div class="btn-group" role="group">
                                                <!-- Botón Editar -->
                                                <button type="button" 
                                                        class="btn btn-sm" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#modalEditarRecibo{{ $recibo->id }}"
                                                        title="Editar">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>

                                                <!-- Botón Eliminar -->
                                                <form action="{{ route('recibo.destroy', $recibo->id) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('¿Estás seguro de dar de baja este recibo?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Dar de baja">
                                                        <i class="bi bi-trash3"></i>
                                                    </button>
                                                </form>
                                            </div>
                                            @endif
                                        </td>
                                    </tr>

                                    <!-- Modal Editar Recibo -->
                                    <div class="modal fade" id="modalEditarRecibo{{ $recibo->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Editar Recibo</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('recibo.update', $recibo->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Socio *</label>
                                                            <select name="socio_id" class="form-select" required>
                                                                @foreach($socios as $socio)
                                                                    <option value="{{ $socio->id }}" 
                                                                        {{ $recibo->socio_id == $socio->id ? 'selected' : '' }}>
                                                                        {{ $socio->nombre }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Tipo *</label>
                                                            <select name="tipo_id" class="form-select" required>
                                                                @foreach($tipos as $tipo)
                                                                    <option value="{{ $tipo->id }}" 
                                                                        {{ $recibo->tipo_id == $tipo->id ? 'selected' : '' }}>
                                                                        {{ $tipo->nombre }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Concepto *</label>
                                                            <input type="text" name="concepto" class="form-control" 
                                                                   value="{{ $recibo->concepto }}" required>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Importe (€) *</label>
                                                            <input type="number" step="0.01" name="importe" 
                                                                   class="form-control" value="{{ $recibo->importe }}" required>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Fecha *</label>
                                                            <input type="date" name="fecha" class="form-control" 
                                                                   value="{{ $recibo->fecha->format('Y-m-d') }}" required>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Estado *</label>
                                                            <select name="estado_id" class="form-select" required>
                                                                @foreach($estados as $estado)
                                                                    <option value="{{ $estado->id }}" 
                                                                        {{ $recibo->estado_id == $estado->id ? 'selected' : '' }}>
                                                                        {{ $estado->nombre }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                        <button type="submit" class="btn btn-primary">Actualizar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    
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
<div class="modal fade" id="modalCrearRecibo" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nuevo Recibo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('recibo.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Socio *</label>
                        <select name="socio_id" class="form-select" required>
                            <option value="">Selecciona un socio</option>
                            @foreach($socios as $socio)
                                <option value="{{ $socio->id }}">{{ $socio->nombre }} - {{ $socio->dni }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tipo *</label>
                        <select name="tipo_id" class="form-select" required>
                            <option value="">Selecciona un tipo</option>
                            @foreach($tipos as $tipo)
                                <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Concepto *</label>
                        <input type="text" name="concepto" class="form-control" 
                               placeholder="Ej: Cuota anual 2026" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Importe (€) *</label>
                        <input type="number" step="0.01" name="importe" class="form-control" 
                               placeholder="50.00" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Fecha *</label>
                        <input type="date" name="fecha" class="form-control" 
                               value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Estado *</label>
                        <select name="estado_id" class="form-select" required>
                            <option value="">Selecciona un estado</option>
                            @foreach($estados as $estado)
                                <option value="{{ $estado->id }}">{{ $estado->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Crear Recibo</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    let btnBuscar = document.getElementById('btnBuscar');

    btnBuscar.addEventListener('click', () => {
        let inputBusqueda = document.getElementById('buscador');
        let busqueda = inputBusqueda.value.trim();
        
        if (busqueda !== '') {
            fetch("{{ route('recibo.buscar') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    busqueda: busqueda
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
                    if (recibo.tipo.nombre === 'Suscripcion' || recibo.tipo.nombre === 'Suscripción') {
                        tipoBadge = '<span class="badge bg-primary">Suscripción</span>';
                    } else {
                        tipoBadge = '<span class="badge bg-danger">Multa</span>';
                    }

                    let estadoBadge = '';
                    if (recibo.estado.nombre === 'Pagado') {
                        estadoBadge = '<span class="badge bg-success">Pagado</span>';
                    } else {
                        estadoBadge = '<span class="badge bg-warning text-dark">Pendiente</span>';
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
                                        data-bs-target="#modalEditarRecibo${recibo.id}"
                                        title="Editar">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button type="button" 
                                        class="btn btn-sm btn-danger" 
                                        onclick="confirmarEliminar(${recibo.id})"
                                        title="Dar de baja">
                                    <i class="bi bi-trash3"></i>
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
        } else {
            alert('Por favor ingresa un término de búsqueda');
        }
    });

    function confirmarEliminar(id) {
        if (confirm('¿Estás seguro de dar de baja este recibo?')) {
            let form = document.createElement('form');
            form.method = 'POST';
            form.action = '/recibos/eliminar/' + id;
            form.innerHTML = `@csrf`;
            document.body.appendChild(form);
            form.submit();
        }
    }

    document.getElementById('buscador').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            btnBuscar.click();
        }
    });
</script>
@endsection