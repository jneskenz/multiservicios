@extends('layouts.app-adm')

@section('title', 'Admin. Empresarial')

@php
    $breadcrumbs = [
        'title' => 'Admin. Empresarial',
        'description' => 'Formulario para crear un nuevo grupo empresarial',
        'icon' => 'ti tabler-building-bank',
        'items' => [
            ['name' => 'Configuración del Sistema', 'url' => 'javascript:void(0)'],
            ['name' => 'Grupos Empresariales', 'url' => route('admin.grupo-empresas.index')],
            ['name' => 'Crear', 'url' => 'javascript:void(0)', 'active' => true],
        ],
    ];
@endphp

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <x.breadcrumbs :items="$breadcrumbs">
            <x-slot:extra>
                @can('grupo_empresarial.view')
                    <a href="{{ route('admin.grupo-empresas.index') }}" class="btn btn-label-dark waves-effect">
                        <i class="ti tabler-arrow-left me-2"></i>
                        Regresar
                    </a>
                @endcan
            </x-slot:extra>
        </x.breadcrumbs>

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ti tabler-plus me-2"></i>
                            Información del Grupo Empresarial
                        </h5>
                    </div>
                    <div class="card-body">
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="ti tabler-x me-2"></i>
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('admin.grupo-empresas.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nombre" class="form-label">Nombre <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                        id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                                    @error('nombre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="codigo" class="form-label">Código <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('codigo') is-invalid @enderror"
                                        id="codigo" name="codigo" value="{{ old('codigo') }}" required
                                        style="text-transform: uppercase" maxlength="20">
                                    @error('codigo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Código único del grupo (ej: GE001)</div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion"
                                    rows="3">{{ old('descripcion') }}</textarea>
                                @error('descripcion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="max_empresas" class="form-label">Máximo de Empresas</label>
                                    <input type="number" class="form-control @error('max_empresas') is-invalid @enderror"
                                        id="max_empresas" name="max_empresas" value="{{ old('max_empresas') }}" min="1">
                                    @error('max_empresas')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                {{-- <div class="col-md-6 mb-3">
                                    <label for="cantidad_empleados" class="form-label">Cantidad de Empleados</label>
                                    <input type="number" class="form-control @error('cantidad_empleados') is-invalid @enderror"
                                        id="cantidad_empleados" name="cantidad_empleados" value="{{ old('cantidad_empleados') }}" min="1">
                                    @error('cantidad_empleados')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div> --}}
                                <div class="col-md-6 mb-3">
                                    {{-- plan select --}}
                                    <label for="plan_actual" class="form-label">Plan Actual</label>
                                    <select class="form-select @error('plan_actual') is-invalid @enderror" id="plan_actual" name="plan_actual">
                                        <option value="">Seleccione un plan</option>
                                        @foreach ($planes as $plan)
                                            <option value="{{ $plan }}" {{ old('plan_actual') == $plan ? 'selected' : '' }}>{{ ucfirst($plan) }}</option>
                                        @endforeach
                                    </select>
                                    @error('plan_actual')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    {{-- pais select --}}
                                    <label for="pais_id" class="form-label">País de Origen</label>
                                    <select class="form-select @error('pais_id') is-invalid @enderror" id="pais_id" name="pais_id">
                                        <option value="">Seleccione un país</option>
                                        @foreach ($paises as $pais)
                                            <option value="{{ $pais->id }}" {{ old('pais_id') == $pais->id ? 'selected' : '' }}>{{ $pais->descripcion }}</option>
                                        @endforeach
                                    </select>
                                    @error('pais_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="telefono" class="form-label">Teléfono</label>
                                    <input type="text" class="form-control @error('telefono') is-invalid @enderror"
                                        id="telefono" name="telefono" value="{{ old('telefono') }}">
                                    @error('telefono')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email') }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="sitio_web" class="form-label">Sitio Web</label>
                                    <input type="url" class="form-control @error('sitio_web') is-invalid @enderror"
                                        id="sitio_web" name="sitio_web" value="{{ old('sitio_web') }}"
                                        placeholder="https://www.ejemplo.com">
                                    @error('sitio_web')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="direccion_matriz" class="form-label">Dirección Matriz</label>
                                <textarea class="form-control @error('direccion_matriz') is-invalid @enderror" id="direccion_matriz"
                                    name="direccion_matriz" rows="3">{{ old('direccion_matriz') }}</textarea>
                                @error('direccion_matriz')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="estado" name="estado"
                                        value="1" {{ old('estado', true) ? 'checked' : '' }}>
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
                                    Crear Grupo Empresarial
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ti tabler-info-circle me-2"></i>
                            Información
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <h6 class="alert-heading">
                                <i class="ti tabler-lightbulb me-1"></i>
                                Consejos
                            </h6>
                            <ul class="mb-0 ps-3">
                                <li>El código debe ser único en el sistema</li>
                                <li>Utilice códigos descriptivos (ej: GE001, CORP01)</li>
                                <li>Los campos marcados con (*) son obligatorios</li>
                                <li>Puede agregar empresas después de crear el grupo</li>
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

