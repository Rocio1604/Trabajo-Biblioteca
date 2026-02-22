 @foreach($ejemplares as $ejemplar)
            <tr @if($ejemplar->es_activo==0) class="table-light opacity-50" @endif>
                <td class="px-4 py-3">
                    <div>
                        <p class="m-0 fw-semibold">{{ $ejemplar->libro->titulo }}</p>
                        <p class="m-0 fs-7">{{ $ejemplar->libro->isbn }}</p>
                    </div>
                </td>
                <td class="px-4 py-3 fs-7">
                    {{ $ejemplar->libro->autores->pluck('nombre')->implode(', ') }}
                </td>
                <td class="px-4 py-3 fs-7">{{ $ejemplar->libro->categoria->nombre }}</td>
                <td class="px-4 py-3 fs-7">{{ $ejemplar->biblioteca->nombre }}</td>
                <td class="px-4 py-3 fs-7">
                    @switch($ejemplar->estado->id)
                        @case(1)
                            <div class="d-flex flex-wrap align-items-center disponible rounded-3 px-2 py-1 gap-1 fw-semibold" style="width: fit-content;">
                                <span class="fs-8">{{ $ejemplar->estado->nombre }}</span>
                            </div>
                            @break
                        @case(2)
                        <!-- color azul en letra y de fondo azul claro -->
                             <div class="d-flex flex-wrap align-items-center etiqueta-azul rounded-3 px-2 py-1 gap-1 fw-semibold" style="width: fit-content;">
                                <span class="fs-8">{{ $ejemplar->estado->nombre }}</span>
                            </div>
                            @break
                        @case(3)
                        <!-- color naranja en letra y de fondo naranja claro -->
                             <div class="d-flex flex-wrap align-items-center etiqueta-naranja rounded-3 px-2 py-1 gap-1 fw-semibold" style="width: fit-content;">
                                <span class="fs-8">{{ $ejemplar->estado->nombre }}</span>
                            </div>
                            @break
                        @case(4)
                        <!-- color naranja en letra y de fondo naranja claro -->
                             <div class="d-flex flex-wrap align-items-center no-disponible rounded-3 px-2 py-1 gap-1 fw-semibold" style="width: fit-content;">
                                <span class="fs-8">{{ $ejemplar->estado->nombre }}</span>
                            </div>
                            @break

                    @endswitch
                </td>
                <td class="px-4 py-3">
                    <!-- color verde con icono de caja -->
                    @if($ejemplar->disponibilidad->id === 1)
                        <div class="d-flex flex-wrap align-items-center disponible rounded-3 px-2 py-1 gap-1 fw-semibold" style="width: fit-content;">
                            <span class="fs-8">Disponible</span>
                        </div>
                    @endif
                    <!-- color gris -->
                    @if($ejemplar->disponibilidad->id === 2)   
                        <div class="d-flex flex-wrap align-items-center etiqueta-gris rounded-3 px-2 py-1 gap-1 fw-semibold" style="width: fit-content;">
                            <span class="fs-8">No disponible</span>
                        </div>
                    @endif
                    @if($ejemplar->disponibilidad->id === 3)   
                        <div class="d-flex flex-wrap align-items-center no-disponible rounded-3 px-2 py-1 gap-1 fw-semibold" style="width: fit-content;">
                            <span class="fs-8">Perdido</span>
                        </div>
                    @endif
                </td>
                <td class="px-4 py-3">
                    <!-- color verde con icono de caja -->
                    @if($ejemplar->es_activo)
                        Sí
                    @else
                        No
                    @endif
                </td>
                <td class="px-4 py-3">
                    <div class="d-flex wrap-flex gap-4">
                        @if($ejemplar->es_activo)
                            <button class="bg-transparent border-0" data-bs-toggle="modal" 
                                    data-bs-target="#registroModal"
                                    data-id="{{ $ejemplar->id }}"
                                    data-libro="{{ $ejemplar->libro->id }}"
                                    data-biblioteca="{{ $ejemplar->biblioteca->id }}"
                                    data-estado="{{ $ejemplar->estado->id }}">
                                <i class="bi bi-pencil-square icono-editar"></i>
                            </button>
                            <button class="bg-transparent border-0 " onclick="confirmarEliminar('{{ $ejemplar->id }}','ejemplar','ejemplares')">
                                <i class="bi bi-trash icono-eliminar"></i>
                            </button>
                        @else
                            <button class="bg-transparent border-0 " onclick="confirmarReactivar('{{ $ejemplar->id }}','ejemplar','ejemplares')">
                                <i class="bi bi-arrow-counterclockwise text-success"></i>
                            </button>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach