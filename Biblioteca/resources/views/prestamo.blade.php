@extends('layout.menu')

@section('title', 'Gestión de Préstamos')

@section('content')
<div class="container-fluid">

    {{-- Alertas --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-arrow-left-right me-2"></i>Gestión de Préstamos
            </h5>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalCrearPrestamo">
                <i class="bi bi-plus-lg me-1"></i>Nuevo Préstamo
            </button>
        </div>

        <div class="card-body">

            {{-- Filtros --}}
            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <input type="text" id="busqueda" class="form-control" placeholder="Buscar por socio, libro o número de préstamo...">
                </div>
                <div class="col-md-4">
                    <select id="filtroEstado" class="form-select">
                        <option value="todos">Todos los estados</option>
                        @foreach($estados as $estado)
                            <option value="{{ $estado->id }}">{{ $estado->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button id="btnBuscar" class="btn btn-info w-100">
                        <i class="bi bi-search me-1"></i>Buscar
                    </button>
                </div>
            </div>

            {{-- Tabla --}}
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle">
                    <thead class="bg-white">
                        <tr>
                            <th>Nº Préstamo</th>
                            <th>Socio</th>
                            <th>Libro</th>
                            <th>Fecha Préstamo</th>
                            <th>Fecha Devolución</th>
                            <th>Multa</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tablaPrestamos">
                        @forelse($prestamos as $prestamo)
                            <tr>
                                <td>{{ $prestamo->numero_prestamo }}</td>
                                <td>{{ $prestamo->socio->nombre }}</td>
                                <td>{{ $prestamo->ejemplar->libro->titulo }}</td>
                                <td>{{ $prestamo->fecha_prestamo->format('d/m/Y') }}</td>
                                <td>{{ $prestamo->fecha_devolucion->format('d/m/Y') }}</td>
                                @php
                                    $hoy = \Carbon\Carbon::today();
                                    $vencimiento = \Carbon\Carbon::parse($prestamo->fecha_devolucion);
                                    $multaMostrada = $prestamo->multa;
                                    if ($prestamo->estado_id != 2 && $hoy->gt($vencimiento)) {
                                        $dias = $vencimiento->diffInDays($hoy);
                                        $multaMostrada = floor($dias / 30) * 5;
                                    }
                                @endphp
                                <td>€{{ number_format($multaMostrada, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $prestamo->estado_id == 1 ? 'warning' : ($prestamo->estado_id == 2 ? 'success' : 'danger') }}">
                                        {{ $prestamo->estado->nombre }}
                                    </span>
                                </td>
                                <td>
                                    <button class="border-0 bg-transparent btn-editar" data-id="{{ $prestamo->id }}" style="cursor: pointer;">
                                        <i class="bi bi-pencil-square fs-5"></i>
                                    </button>
                                    <button class="border-0 bg-transparent btn-eliminar" data-id="{{ $prestamo->id }}" style="cursor: pointer;">
                                        <i class="bi bi-trash fs-5 text-danger"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">No hay préstamos registrados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ===================== MODAL CREAR ===================== --}}
<div class="modal fade" id="modalCrearPrestamo" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('prestamo.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-semibold">
                        <i class="bi bi-plus-circle me-2"></i>Nuevo Préstamo
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Socio <span class="text-danger">*</span></label>
                            <select name="socio_id" class="form-select" required>
                                <option value="">Seleccione un socio</option>
                                @foreach($socios ?? [] as $socio)
                                    <option value="{{ $socio->id }}">{{ $socio->nombre }} — {{ $socio->dni }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Ejemplar <span class="text-danger">*</span></label>
                            <select name="ejemplar_id" class="form-select" required>
                                <option value="">Seleccione un ejemplar</option>
                                @foreach($ejemplares ?? [] as $ejemplar)
                                    <option value="{{ $ejemplar->id }}">{{ $ejemplar->libro->titulo }} — ISBN: {{ $ejemplar->libro->isbn }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Fecha Préstamo <span class="text-danger">*</span></label>
                            <input type="date" name="fecha_prestamo" id="fecha_prestamo_crear" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Fecha Devolución <span class="text-danger">*</span></label>
                            <input type="date" name="fecha_devolucion" id="fecha_devolucion_crear" class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Estado <span class="text-danger">*</span></label>
                            <select name="estado_id" class="form-select" required>
                                <option value="">Estado</option>
                                @foreach($estados as $estado)
                                    <option value="{{ $estado->id }}">{{ $estado->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Guardar Préstamo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===================== MODAL EDITAR ===================== --}}
<div class="modal fade" id="modalEditarPrestamo" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formEditarPrestamo" method="POST">
                @csrf
                @method('POST')
                <div class="modal-header">
                    <h5 class="modal-title fw-semibold">
                        <i class="bi bi-pencil me-2"></i>Editar Préstamo
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Socio <span class="text-danger">*</span></label>
                            <select name="socio_id" id="edit_socio_id" class="form-select" required>
                                <option value="">Seleccione un socio</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Ejemplar <span class="text-danger">*</span></label>
                            <select name="ejemplar_id" id="edit_ejemplar_id" class="form-select" required>
                                <option value="">Seleccione un ejemplar</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Fecha Préstamo <span class="text-danger">*</span></label>
                            <input type="date" name="fecha_prestamo" id="edit_fecha_prestamo" class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Fecha Devolución <span class="text-danger">*</span></label>
                            <input type="date" name="fecha_devolucion" id="edit_fecha_devolucion" class="form-control" required>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Multa (€)</label>
                            <input type="number" name="multa" id="edit_multa" class="form-control" step="0.01" min="0">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Estado <span class="text-danger">*</span></label>
                            <select name="estado_id" id="edit_estado_id" class="form-select" required>
                                @foreach($estados as $estado)
                                    <option value="{{ $estado->id }}">{{ $estado->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Validación de fechas en modal crear
    const fechaPrestamoCrear = document.getElementById('fecha_prestamo_crear');
    const fechaDevolucionCrear = document.getElementById('fecha_devolucion_crear');

    if (fechaDevolucionCrear && fechaPrestamoCrear) {
        // Cuando cambia la fecha de préstamo, la devolución debe ser posterior
        fechaPrestamoCrear.addEventListener('change', function() {
            fechaDevolucionCrear.setAttribute('min', this.value);
            // Si la devolución ya está puesta y es menor, resetearla
            if (fechaDevolucionCrear.value && fechaDevolucionCrear.value < this.value) {
                fechaDevolucionCrear.value = '';
            }
        });
        
        // Setear el min inicial
        if (fechaPrestamoCrear.value) {
            fechaDevolucionCrear.setAttribute('min', fechaPrestamoCrear.value);
        }
    }


    // Búsqueda
    document.getElementById('btnBuscar').addEventListener('click', buscarPrestamos);
    document.getElementById('busqueda').addEventListener('keyup', function(e) {
        if (e.key === 'Enter') buscarPrestamos();
    });
    document.getElementById('filtroEstado').addEventListener('change', buscarPrestamos);

    function buscarPrestamos() {
        const busqueda = document.getElementById('busqueda').value;
        const estado = document.getElementById('filtroEstado').value;

        fetch('{{ route("prestamo.buscar") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ busqueda, estado })
        })
        .then(r => r.json())
        .then(data => actualizarTabla(data))
        .catch(() => alert('Error al buscar préstamos'));
    }

    function actualizarTabla(prestamos) {
        const tbody = document.getElementById('tablaPrestamos');
        if (prestamos.length === 0) {
            tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted py-4">No se encontraron préstamos</td></tr>';
            return;
        }
        tbody.innerHTML = prestamos.map(p => {
            const fechaP = new Date(p.fecha_prestamo).toLocaleDateString('es-ES');
            const fechaD = new Date(p.fecha_devolucion).toLocaleDateString('es-ES');
            const badge = p.estado_id == 1 ? 'warning' : (p.estado_id == 2 ? 'success' : 'danger');
            return `
                <tr>
                    <td>${p.numero_prestamo}</td>
                    <td>${p.socio.nombre}</td>
                    <td>${p.ejemplar.libro.titulo}</td>
                    <td>${fechaP}</td>
                    <td>${fechaD}</td>
                    <td>€${parseFloat(p.multa).toFixed(2)}</td>
                    <td><span class="badge bg-${badge}">${p.estado.nombre}</span></td>
                    <td>
                        <button class="btn btn-sm btn-warning" onclick="editarPrestamo(${p.id})"><i class="bi bi-pencil"></i></button>
                        <button class="btn btn-sm btn-danger" onclick="eliminarPrestamo(${p.id})"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>`;
        }).join('');
    }

    // Botones editar/eliminar de la tabla inicial
    document.querySelectorAll('.btn-editar').forEach(btn => {
        btn.addEventListener('click', () => editarPrestamo(btn.dataset.id));
    });
    document.querySelectorAll('.btn-eliminar').forEach(btn => {
        btn.addEventListener('click', () => eliminarPrestamo(btn.dataset.id));
    });
});

function editarPrestamo(id) {
    fetch(`/prestamos/editar/${id}`, { method: 'GET' })
    .then(r => r.json())
    .then(data => {
        document.getElementById('edit_socio_id').innerHTML =
            '<option value="">Seleccione un socio</option>' +
            data.socios.map(s => `<option value="${s.id}" ${s.id == data.prestamo.socio_id ? 'selected' : ''}>${s.nombre}</option>`).join('');

        document.getElementById('edit_ejemplar_id').innerHTML =
            '<option value="">Seleccione un ejemplar</option>' +
            data.ejemplares.map(e => `<option value="${e.id}" ${e.id == data.prestamo.ejemplar_id ? 'selected' : ''}>${e.libro.titulo} — ISBN: ${e.libro.isbn}</option>`).join('');

        document.getElementById('edit_fecha_prestamo').value = data.prestamo.fecha_prestamo;
        document.getElementById('edit_fecha_devolucion').value = data.prestamo.fecha_devolucion;
        document.getElementById('edit_multa').value = data.prestamo.multa;
        document.getElementById('edit_estado_id').value = data.prestamo.estado_id;

        document.getElementById('formEditarPrestamo').action = `/prestamos/editar/${id}`;
        new bootstrap.Modal(document.getElementById('modalEditarPrestamo')).show();
    })
    .catch(() => alert('Error al cargar el préstamo'));
}

function eliminarPrestamo(id) {
    // Primero obtener los datos del préstamo desde la tabla
    const fila = event.target.closest('tr');
    const numeroPrestamo = fila.cells[0].textContent;
    const socio = fila.cells[1].textContent;
    const libro = fila.cells[2].textContent;
    const fechaPrestamo = fila.cells[3].textContent;
    const fechaDevolucion = fila.cells[4].textContent;
    const multa = fila.cells[5].textContent;
    const estado = fila.cells[6].textContent.trim();

    Swal.fire({
        icon: 'warning',
        title: '¿Desactivar préstamo?',
        showCancelButton: true,
        confirmButtonText: 'Sí, desactivar',
        confirmButtonColor: '#ff6600',
        cancelButtonText: 'Cancelar',
        cancelButtonColor: '#dc3545',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Enviar petición AJAX para eliminar
            fetch(`/prestamos/eliminar/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => {
                if (response.ok) {
                    // Mostrar datos del préstamo eliminado
                    Swal.fire({
                        icon: 'success',
                        title: 'Préstamo eliminado',
                        html: `
                            <div style="text-align: left; padding: 10px;">
                                <p><strong>${socio}</strong></p>
                                <p style="margin: 5px 0;">${libro}</p>
                                <p style="margin: 5px 0; color: #666;">Alta: ${fechaPrestamo}</p>
                                <div style="display: flex; justify-content: space-between; margin-top: 10px;">
                                    <span>${numeroPrestamo}</span>
                                    <span>${fechaDevolucion}</span>
                                    <span>${multa}</span>
                                    <span class="badge bg-secondary">${estado}</span>
                                </div>
                            </div>
                        `,
                        showConfirmButton: false,
                        timer: 3000
                    }).then(() => {
                        // Recargar página
                        window.location.reload();
                    });
                } else {
                    Swal.fire('Error', 'No se pudo eliminar el préstamo', 'error');
                }
            })
            .catch(() => {
                Swal.fire('Error', 'Error de conexión', 'error');
            });
        }
    });
}
</script>
@endsection