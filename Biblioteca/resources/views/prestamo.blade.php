@extends('layout.menu')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Gestión de Préstamos</h3>
                    <a href="{{ route('prestamo.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nuevo Préstamo
                    </a>
                </div>
                
                <div class="card-body">
                    <!-- Alertas -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <!-- Filtros de búsqueda -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <input type="text" id="busqueda" class="form-control" placeholder="Buscar por socio, libro o número de préstamo...">
                        </div>
                        <div class="col-md-3">
                            <select id="filtroEstado" class="form-control">
                                <option value="todos">Todos los estados</option>
                                @foreach($estados as $estado)
                                    <option value="{{ $estado->id }}">{{ $estado->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button id="btnBuscar" class="btn btn-info btn-block">
                                <i class="fas fa-search"></i> Buscar
                            </button>
                        </div>
                    </div>

                    <!-- Tabla de préstamos -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="thead-dark">
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
                                        <td>€{{ number_format($prestamo->multa, 2) }}</td>
                                        <td>
                                            <span class="badge badge-{{ $prestamo->estado_id == 1 ? 'warning' : ($prestamo->estado_id == 2 ? 'success' : 'danger') }}">
                                                {{ $prestamo->estado->nombre }}
                                            </span>
                                        </td>
                                        <td>
                                        <button class="btn btn-sm btn-warning btn-editar" data-id="{{ $prestamo->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger btn-eliminar" data-id="{{ $prestamo->id }}">
                                        <i class="fas fa-trash"></i>
                                        </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No hay préstamos registrados</td>
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

<!-- Modal Editar Préstamo -->
<div class="modal fade" id="modalEditarPrestamo" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="formEditarPrestamo" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Editar Préstamo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_prestamo_id" name="prestamo_id">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Socio *</label>
                                <select name="socio_id" id="edit_socio_id" class="form-control" required>
                                    <option value="">Seleccione un socio</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Ejemplar *</label>
                                <select name="ejemplar_id" id="edit_ejemplar_id" class="form-control" required>
                                    <option value="">Seleccione un ejemplar</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Fecha Préstamo *</label>
                                <input type="date" name="fecha_prestamo" id="edit_fecha_prestamo" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Fecha Devolución *</label>
                                <input type="date" name="fecha_devolucion" id="edit_fecha_devolucion" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Multa</label>
                                <input type="number" name="multa" id="edit_multa" class="form-control" step="0.01" min="0" value="0">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Estado *</label>
                                <select name="estado_id" id="edit_estado_id" class="form-control" required>
                                    @foreach($estados as $estado)
                                        <option value="{{ $estado->id }}">{{ $estado->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Eliminar -->
<div class="modal fade" id="modalEliminarPrestamo" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="formEliminarPrestamo" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Eliminar Préstamo</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>¿Está seguro que desea eliminar este préstamo?</p>
                    <p class="text-danger"><strong>Esta acción no se puede deshacer.</strong></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Búsqueda con filtros
    $('#btnBuscar').on('click', function() {
        buscarPrestamos();
    });

    $('#busqueda').on('keyup', function(e) {
        if(e.key === 'Enter') {
            buscarPrestamos();
        }
    });

    $('#filtroEstado').on('change', function() {
        buscarPrestamos();
    });

    function buscarPrestamos() {
        const busqueda = $('#busqueda').val();
        const estado = $('#filtroEstado').val();

        $.ajax({
            url: '{{ route("prestamo.buscar") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                busqueda: busqueda,
                estado: estado
            },
            success: function(response) {
                actualizarTablaPrestamos(response);
            },
            error: function() {
                alert('Error al buscar préstamos');
            }
        });
    }

    function actualizarTablaPrestamos(prestamos) {
        let html = '';
        
        if(prestamos.length === 0) {
            html = '<tr><td colspan="8" class="text-center">No se encontraron préstamos</td></tr>';
        } else {
            prestamos.forEach(function(prestamo) {
                const fechaPrestamo = new Date(prestamo.fecha_prestamo).toLocaleDateString('es-ES');
                const fechaDevolucion = new Date(prestamo.fecha_devolucion).toLocaleDateString('es-ES');
                const badgeClass = prestamo.estado_id == 1 ? 'warning' : (prestamo.estado_id == 2 ? 'success' : 'danger');
                
                html += `
                    <tr>
                        <td>${prestamo.numero_prestamo}</td>
                        <td>${prestamo.socio.nombre}</td>
                        <td>${prestamo.ejemplar.libro.titulo}</td>
                        <td>${fechaPrestamo}</td>
                        <td>${fechaDevolucion}</td>
                        <td>€${parseFloat(prestamo.multa).toFixed(2)}</td>
                        <td><span class="badge badge-${badgeClass}">${prestamo.estado.nombre}</span></td>
                        <td>
                            <button class="btn btn-sm btn-warning" onclick="editarPrestamo(${prestamo.id})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="eliminarPrestamo(${prestamo.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
        }
        
        $('#tablaPrestamos').html(html);
    }
});

function editarPrestamo(id) {
    $.ajax({
        url: `/prestamos/editar/${id}`,
        method: 'GET',
        success: function(response) {
            // Aquí deberías cargar los datos en el modal
            // Asumiendo que tu endpoint retorna JSON con los datos
            $('#edit_prestamo_id').val(response.prestamo.id);
            $('#edit_socio_id').val(response.prestamo.socio_id);
            $('#edit_ejemplar_id').val(response.prestamo.ejemplar_id);
            $('#edit_fecha_prestamo').val(response.prestamo.fecha_prestamo);
            $('#edit_fecha_devolucion').val(response.prestamo.fecha_devolucion);
            $('#edit_multa').val(response.prestamo.multa);
            $('#edit_estado_id').val(response.prestamo.estado_id);
            
            // Cargar socios y ejemplares
            cargarSocios(response.socios);
            cargarEjemplares(response.ejemplares);
            
            $('#formEditarPrestamo').attr('action', `/prestamos/editar/${id}`);
            $('#modalEditarPrestamo').modal('show');
        },
        error: function() {
            alert('Error al cargar el préstamo');
        }
    });
}

function cargarSocios(socios) {
    let html = '<option value="">Seleccione un socio</option>';
    socios.forEach(function(socio) {
        html += `<option value="${socio.id}">${socio.nombre}</option>`;
    });
    $('#edit_socio_id').html(html);
}

function cargarEjemplares(ejemplares) {
    let html = '<option value="">Seleccione un ejemplar</option>';
    ejemplares.forEach(function(ejemplar) {
        html += `<option value="${ejemplar.id}">${ejemplar.libro.titulo} - ISBN: ${ejemplar.libro.isbn}</option>`;
    });
    $('#edit_ejemplar_id').html(html);
}

function eliminarPrestamo(id) {
    $('#formEliminarPrestamo').attr('action', `/prestamos/eliminar/${id}`);
    $('#modalEliminarPrestamo').modal('show');
}
</script>
@endsection