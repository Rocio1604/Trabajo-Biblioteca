@foreach($usuarios as $usuario)
            <tr @if($usuario->es_activo==0) class="table-light opacity-50" @endif>
                <td class="px-4 py-3">
                    <div>
                        <p class="m-0 fw-semibold">{{ $usuario->nombre }}</p>
                        <p class="m-0 fs-7">{{ $usuario->correo }}</p>
                    </div>
                </td>
                <td class="px-4 py-3 fs-7 text-center">{{ $usuario->telefono }}</td>
                <td class="px-4 py-3 fs-7 text-center">{{ $usuario->rol->nombre }}</td>
                <td class="px-4 py-3 fs-7 text-center">{{ $usuario->biblioteca?->nombre ?? 'Administrador' }}</td>
                <td class="px-4 py-3 fs-7 text-center">{{ $usuario->created_at->format('d/m/Y') }}</td>
                <td class="px-4 py-3" >
                    <!-- color verde con icono de caja -->
                    @if($usuario->es_activo === 1)
                        <div class="d-flex flex-wrap align-items-center disponible rounded-3 px-2 py-1 gap-1 fw-semibold mx-auto" style="width: fit-content;">
                            <i class="bi bi-check2-circle fs-8"></i>
                            <span class="fs-8">Activo</span>
                        </div>
                    @endif
                    <!-- color gris -->
                    @if($usuario->es_activo === 0)   
                        <div class="d-flex flex-wrap align-items-center no-disponible rounded-3 px-2 py-1 gap-1 fw-semibold mx-auto" style="width: fit-content;">
                            <i class="bi bi-x-circle fs-8"></i>
                            <span class="fs-8">Inactivo</span>
                        </div>
                    @endif
                </td>
                <td class="px-4 py-3 ">
                    <div class="d-flex wrap-flex gap-4 justify-content-center">
                        @if($usuario->es_activo)
                            @if($usuario->id==1)
                            <button class="bg-transparent border-0" onclick="abrirModalPassword('{{ $usuario->id }}', '{{ $usuario->nombre }}', '{{ $usuario->biblioteca?->nombre ?? 'Acceso Total' }}')">
                                <i class="bi bi-key text-warning"></i>
                            </button>
                            @else
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
                            <button class="bg-transparent border-0" onclick="abrirModalPassword('{{ $usuario->id }}', '{{ $usuario->nombre }}', '{{ $usuario->biblioteca?->nombre ?? 'Acceso Total' }}')">
                                <i class="bi bi-key text-warning"></i>
                            </button>
                            <button class="bg-transparent border-0 " onclick="confirmarEliminar('{{ $usuario->id }}','usuario','usuarios')">
                                <i class="bi bi-trash icono-eliminar"></i>
                            </button>
                            @endif
                        @else
                            <button class="bg-transparent border-0 " onclick="confirmarReactivar('{{ $usuario->id }}','usuario','usuarios')">
                                <i class="bi bi-arrow-counterclockwise text-success"></i>
                            </button>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach