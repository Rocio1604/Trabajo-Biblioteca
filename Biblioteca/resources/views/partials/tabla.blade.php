
<p class="mb-3">Mostrando <span class="fw-bold">{{ $ejemplares->count() }}</span> resultados</p>
@foreach($ejemplares as $ejemplar)
            <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                <div class="tarjeta bg-white p-3 border rounded-3">
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
                    <p class="fw-semibold mb-1">{{ $ejemplar->libro->titulo }}</p>
                    <p class="fs-7 mb-2 ">{{ $ejemplar->libro->autores->isEmpty() ? 'Sin autor' : $ejemplar->libro->autores->pluck('nombre')->implode(', ') }}</p>
                    <div class="mb-2">
                        <div class="d-flex flex-wrap gap-2 mb-2">
                            <i class="bi bi-geo-alt fs-8"></i>
                            <span class="fs-8">{{ $ejemplar->biblioteca->nombre }}</span>
                        </div>
                        <div class="d-flex flex-wrap gap-2 align-items-center ">
                            <span class="etiqueta rounded-1">{{ $ejemplar->libro->categoria->nombre }}</span>
                            <p class="fs-8 text-body-tertiary m-0">ISBN: <span>{{ $ejemplar->libro->isbn }}</span></p>
                        </div>
                    </div>
                    @if($ejemplar->disponibilidad->id === 1)
                        <p class="disponible fw-semibold fs-7 py-2 m-0 text-center rounded-3">Disponible</p>
                    @endif
                    <!-- color gris -->
                    @if($ejemplar->disponibilidad->id === 2)   
                        <p class="no-disponible fw-semibold fs-7 py-2 m-0 text-center rounded-3">No disponible</p>
                    @endif
                </div>
            </div>
            @endforeach