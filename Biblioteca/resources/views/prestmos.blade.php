<div class="border rounded-4 overflow-hidden shadow-sm">
    <table class="table mb-0 align-middle">
        <thead class="table-light">
            <tr>
                <th class="px-4 py-12">SOCIO</th>
                <th class="px-4 py-12">LIBRO</th>
                <th class="px-4 py-12">BIBLIOTECA</th>
                <th class="px-4 py-12">FECHA PRÉSTAMO</th>
                <th class="px-4 py-12">FECHA DEVOLUCIÓN</th>
                <th class="px-4 py-12">DÍAS RESTANTES</th>
                <th class="px-4 py-12">ESTADO</th>
                <th class="px-4 py-12">MULTA</th>
                <th class="px-4 py-12">ACCIONES</th>
            </tr>
        </thead>
        <tbody>
}
            @foreach($prestamos as $prestamo)
            <tr>

                <td class="px-4 py-3">
                    <p class="m-0 fw-bold">{{ $prestamo->socios->nombre ?? 'N/A' }}</p>
                </td>

                <td class="px-4 py-3 text-muted">
                    <p class="m-0">{{ $prestamo->libros->titulo ?? 'N/A' }}</p>
                </td>

                <td class="px-4 py-3 text-muted">
                    {{ $prestamo->socios->biblioteca->nombre ?? 'Sede Central' }}
                </td>

                <td class="px-4 py-3 text-muted">
                    {{ \Carbon\Carbon::parse($prestamo->fecha_prestamo)->format('Y-m-d') }}
                </td>

                <td class="px-4 py-3 text-muted">
                    {{ \Carbon\Carbon::parse($prestamo->fecha_limite)->format('Y-m-d') }}
                </td>

                <td class="px-4 py-3">
                    @php

                        $inicio = \Carbon\Carbon::parse($prestamo->fecha_prestamo);
                        $limite = \Carbon\Carbon::parse($prestamo->fecha_limite);             

                        $dias = $inicio->diffInDays($limite, false);
                        
                        $estaDevuelto = !empty($prestamo->feha_devolucion);
                    @endphp

                    
                    @if($estaDevuelto)
                        <span class="text-muted">-</span>
                    @else
                        
                        <span class="fw-bold {{ $dias >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ abs($dias) }} días {{ $dias < 0 ? 'tarde' : '' }}
                        </span>
                    @endif
                </td>

               
                <td class="px-4 py-3">
                    @if($estaDevuelto)
                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-2 px-3">
                             <i class="bi bi-check-lg me-1"></i> Devuelto
                        </span>
                    @else
                        
                        <span class="badge {{ $dias >= 0 ? 'bg-primary-subtle text-primary' : 'bg-danger-subtle text-danger' }} border rounded-2 px-3">
                            <i class="bi {{ $dias >= 0 ? 'bi-clock' : 'bi-exclamation-triangle' }} me-1"></i>
                            {{ $dias >= 0 ? 'Activo' : 'Vencido' }}
                        </span>
                    @endif
                </td>

                
                <td class="px-4 py-3">
                    <span class="{{ $dias < 0 && !$estaDevuelto ? 'text-danger fw-bold' : 'text-muted' }}">
                        {{-- abs($dias) convierte el número negativo en positivo para el cálculo --}}
                        €{{ ($dias < 0 && !$estaDevuelto) ? abs($dias) * 5 : 0 }}
                    </span>
                </td>

                <td class="px-4 py-3 text-center">
                    <button class="btn btn-light border btn-sm rounded-3 shadow-sm">
                        <i class="bi bi-three-dots"></i>
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>