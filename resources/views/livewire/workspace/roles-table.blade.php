<div>
    {{-- Filtros y controles --}}
    <div class="row g-3 mb-4">
        <div class="col-md-1">
            <label for="perPage" class="form-label">Mostrar</label>
            <select class="form-select" id="perPage" wire:model.live="perPage">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>
        <div class="col-md-4">
            <label for="search" class="form-label">Buscar</label>
            <input type="text" class="form-control" id="search" wire:model.live.debounce.300ms="search"
                placeholder="Buscar por descripción, código, dirección...">
        </div>
        <div class="col-md-2">
            <label for="estadoFilter" class="form-label">Estado</label>
            <select class="form-select" id="estadoFilter" wire:model.live="estadoFilter">
                <option value="">Todos</option>
                <option value="1">Activo</option>
                <option value="0">Inactivo</option>
            </select>
        </div>
        
        <div class="col-md-2">
            <label for="estadoFilter" class="form-label w-100">Acciones</label>
            <div class="d-flex gap-1  text-center align-content-between">
                <button class="btn btn-outline-primary waves-effect" wire:click="clearFilters" data-bs-toggle="tooltip" title="Limpiar filtros">
                    <i class="ti tabler-filter-x fs-5"></i>
                </button>
                <button type="button" class="btn btn-outline-primary waves-effect" wire:click="$refresh" data-bs-toggle="tooltip" title="Actualizar">
                    <i class="ti tabler-refresh fs-5"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- Tabla de roles --}}
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Acciones</th>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Permisos</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @foreach ($roles as $role)
                    <tr>
                        <td>
                            {{-- <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="ti tabler-pencil"></i> Editar                                    
                            </a> --}}
                            {{-- @canany(['editar_roles', 'eliminar_roles']) --}}
                                    <div class="btn-group">
                                        <button type="button"
                                            class="btn btn-label-primary btn-icon btn-sm rounded dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ti tabler-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            @can('ver_roles')
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('grupo.roles.show', ['grupo' => $grupoActual->slug ?? request()->route('grupo'), 'role' => $role->id]) }}">
                                                        <i class="ti tabler-list-search me-2"></i> Ver detalles
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('editar_roles')
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('grupo.roles.edit', ['grupo' => $grupoActual->slug ?? request()->route('grupo'), 'role' => $role->id]) }}">
                                                        <i class="ti tabler-edit me-2"></i> Editar
                                                    </a>
                                                </li>
                                            @endcan
                                            <li>
                                                <hr class="dropdown-divider" />
                                            </li>
                                            @can('eliminar_roles')
                                                <li>
                                                    <a class="dropdown-item text-warning" href="{{ route('grupo.roles.update', ['grupo' => $grupoActual->slug ?? request()->route('grupo'), 'empresa' => $empresa->id]) }}">
                                                        <i class="ti tabler-alert-square-rounded me-2"></i> Desactivar
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('eliminar_roles')
                                                <li>
                                                    <form action="{{ route('grupo.roles.destroy', ['grupo' => $grupoActual->slug ?? request()->route('grupo'), 'empresa' => $empresa->id]) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('¿Estás seguro de eliminar esta empresa?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="ti tabler-trash me-1"></i> Eliminar
                                                        </button>
                                                    </form>
                                                </li>
                                            @endcan
                                        </ul>
                                    </div>
                            {{-- @endcanany --}}
                        </td>
                        <td><span class="badge bg-label-light me-1 mb-1">{{ $loop->iteration }}</span></td>
                        <td>{{ $role->name }}</td>
                        <td>
                            @foreach ($role->permissions as $permission)
                                <span class="badge bg-label-info me-1 mb-1">{{ $permission->name }}</span>
                                @if($loop->iteration == 10)
                                    <span class="badge bg-label-secondary me-1 mb-1 cursor-pointer">Ver más...</span>
                                    @break
                                @endif                                
                            @endforeach
                        </td>
                        
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>

    {{-- Información y Paginación mejorada --}}
    <div class="row mt-4 align-items-center">
        <div class="col-sm-12 col-md-6 d-flex align-items-center mb-3 mb-md-0">
            <div class="text-muted">
                @if ($roles->total() > 0)
                    <i class="ti tabler-filter-check me-1"></i>
                    Mostrando <strong class="text-primary">{{ $roles->firstItem() }}</strong> a
                    <strong class="text-primary">{{ $roles->lastItem() }}</strong> de
                    <strong class="text-primary">{{ $roles->total() }}</strong>
                    {{ Str::plural('resultado', $roles->total()) }}

                    @if ($search || $estadoFilter !== '')
                        <span class="badge bg-label-info ms-2">
                            <i class="ti tabler-filter me-1"></i>
                            Filtrado
                        </span>
                    @endif
                @else
                    <span class="text-muted">
                        <i class="ti tabler-info-circle me-1"></i>
                        No se encontraron resultados
                    </span>
                @endif
            </div>
        </div>
        <div class="col-sm-12 col-md-6">
            @if ($roles->hasPages())
                <div class="d-flex justify-content-end">
                    {{ $roles->links('custom-pagination') }}
                </div>
            @endif
        </div>
    </div>

    {{-- Modal de confirmación de eliminación --}}
    @if ($showDeleteModal)
        <div class="modal show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmar Eliminación</h5>
                        <button type="button" class="btn-close" wire:click="cancelDelete"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center">
                            <i class="bx bx-error-circle display-1 text-warning"></i>
                            <h5 class="mt-3">¿Estás seguro?</h5>
                            <p class="text-muted">
                                Vas a eliminar el local: <br>
                                <strong>{{ $localToDelete->descripcion ?? '' }}</strong>
                            </p>
                            <p class="text-danger">
                                <small>Esta acción no se puede deshacer.</small>
                            </p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="cancelDelete">
                            Cancelar
                        </button>
                        <button type="button" class="btn btn-danger" wire:click="deleteLocal">
                            <i class="bx bx-trash me-1"></i>
                            Eliminar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Loading indicator --}}
    <div wire:loading class="position-fixed top-50 start-50 translate-middle">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Cargando...</span>
        </div>
    </div>

</div>
