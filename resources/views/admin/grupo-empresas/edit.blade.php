@extends('layouts.app-adm')

@section('title', 'Editar Grupo Empresarial')

@php
    $breadcrumbs = [
        'title' => 'Editar Grupo Empresarial',
        'description' => 'Modificar información del grupo empresarial',
        'icon' => 'ti tabler-building-bank',
        'items' => [
            ['name' => 'Configuración del Sistema', 'url' => 'javascript:void(0)'],
            ['name' => 'Grupos Empresariales', 'url' => route('admin.grupo-empresas.index')],
            ['name' => $grupo_empresa->nombre, 'url' => route('admin.grupo-empresas.show', $grupo_empresa)],
            ['name' => 'Editar', 'url' => 'javascript:void(0)'],
        ],
        // 'actions' => [
        //     [
        //         'name' => 'Regresar',
        //         'url' => route('admin.grupo-empresas.index'),
        //         'typeButton' => 'btn-label-dark',
        //         'icon' => 'ti tabler-arrow-left',
        //     ],
        // ],
    ];
@endphp

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <x-breadcrumbs :items="$breadcrumbs">
        <x-slot:acciones>
            @can('grupo-empresarial.view')
            <a href="{{ route('admin.grupo-empresas.index') }}" class="btn btn-label-dark waves-effect">
                <i class="ti tabler-arrow-left me-2"></i>
                Regresar
            </a>
            @endcan
            @can('grupo-empresarial.show')
            <a href="{{ route('admin.grupo-empresas.show', $grupo_empresa) }}" class="btn btn-info waves-effect">
                <i class="ti tabler-list-search me-2"></i>
                Ver detalles
            </a>
            @endcan
        </x-slot:acciones>
    </x-breadcrumbs>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ti tabler-edit me-2"></i>
                        Editando: {{ $grupo_empresa->nombre }}
                    </h5>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="ti tabler-x me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.grupo-empresas.update', $grupo_empresa) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                                       id="nombre" name="nombre" value="{{ old('nombre', $grupo_empresa->nombre) }}" required>
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="codigo" class="form-label">Código <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('codigo') is-invalid @enderror" 
                                       id="codigo" name="codigo" value="{{ old('codigo', $grupo_empresa->codigo) }}" required
                                       style="text-transform: uppercase" maxlength="20" readonly="true">
                                @error('codigo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Código único del grupo</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                      id="descripcion" name="descripcion" rows="3">{{ old('descripcion', $grupo_empresa->descripcion) }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="pais_origen" class="form-label">País de Origen</label>
                                {{-- <input type="text" class="form-control @error('pais_origen') is-invalid @enderror" id="pais_origen" name="pais_origen" value="{{ old('pais_origen', $grupo_empresa->pais_origen) }}"> --}}
                                <select class="form-select @error('pais_origen') is-invalid @enderror" id="pais_origen" name="pais_origen">
                                    <option value="">Seleccione un país</option>
                                    @foreach($paises as $pais)
                                        <option value="{{ $pais->id }}" 
                                            {{ old('pais_origen', $grupo_empresa->pais_id) == $pais->id ? 'selected' : '' }}>
                                            {{ $pais->descripcion }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('pais_origen')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control @error('telefono') is-invalid @enderror" 
                                       id="telefono" name="telefono" value="{{ old('telefono', $grupo_empresa->telefono) }}">
                                @error('telefono')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $grupo_empresa->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="sitio_web" class="form-label">Sitio Web</label>
                                <input type="url" class="form-control @error('sitio_web') is-invalid @enderror" 
                                       id="sitio_web" name="sitio_web" value="{{ old('sitio_web', $grupo_empresa->sitio_web) }}"
                                       placeholder="https://www.ejemplo.com">
                                @error('sitio_web')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="direccion_matriz" class="form-label">Dirección Matriz</label>
                            <textarea class="form-control @error('direccion_matriz') is-invalid @enderror" 
                                      id="direccion_matriz" name="direccion_matriz" rows="3">{{ old('direccion_matriz', $grupo_empresa->direccion_matriz) }}</textarea>
                            @error('direccion_matriz')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="estado" name="estado" value="1" 
                                       {{ old('estado', $grupo_empresa->estado) ? 'checked' : '' }}>
                                <label class="form-check-label" for="estado">
                                    Grupo activo
                                </label>
                            </div>
                        </div> --}}

                        <div class="d-flex justify-content-between gap-2">
                            <a href="{{ route('admin.grupo-empresas.index') }}" class="btn btn-secondary">
                                <i class="ti tabler-x me-1"></i>
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti tabler-check me-1"></i>
                                Actualizar Grupo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ti tabler-info-circle me-2"></i>
                        Información del Grupo
                    </h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td class="fw-bold">ID:</td>
                            <td>{{ $grupo_empresa->id }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Creado:</td>
                            <td>{{ $grupo_empresa->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Actualizado:</td>
                            <td>{{ $grupo_empresa->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Estado:</td>
                            <td>
                                <span class="badge bg-{{ $grupo_empresa->estado ? 'success' : 'danger' }}">
                                    {{ $grupo_empresa->estado ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ti tabler-help-circle me-2"></i>
                        Ayuda
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <h6 class="alert-heading">
                            <i class="ti tabler-alert-triangle me-1"></i>
                            Importante
                        </h6>
                        <ul class="mb-0 ps-3">
                            <li>Los cambios se aplicarán inmediatamente</li>
                            <li>El código debe ser único en el sistema</li>
                            <li>Si desactiva el grupo, afectará a las empresas asociadas</li>
                            <li>El historial de cambios se registra automáticamente</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>
    // Convertir código a mayúsculas
    document.getElementById('codigo').addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });
</script>
@endsection