<?php

namespace App\Models;

use App\Models\Workspace\Empresa;
use App\Models\Workspace\Local;
use App\Models\Workspace\Sede;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Str;
use App\Models\Scopes\FiltroMultiempresaScope;

class GrupoEmpresa extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'grupo_empresas';

    protected $fillable = [
        'user_uuid',
        'nombre',
        'descripcion',
        'codigo',
        'slug',
        'pais_id',
        'telefono',
        'email',
        'sitio_web',
        'direccion_matriz',
        'estado',
        'avatar'
    ];

    protected $casts = [
        'estado' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
    

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }



    // Scope para grupos activos
    public function scopeActivos($query)
    {
        return $query->where('estado', true);
    }

    // Accessor para nombre completo
    public function getNombreCompletoAttribute()
    {
        return $this->codigo ? "({$this->codigo}) {$this->nombre}" : $this->nombre;
    }

    // Accessor para URL del avatar
    public function getAvatarUrlAttribute()
    {
        return $this->avatar ? asset('storage/' . $this->avatar) : null;
    }


    // Relación con usuarios propietarios
    public function propietarios()
    {
        return $this->belongsToMany(User::class, 'grupo_empresa_propietario')
                    ->withPivot([
                        'tipo',
                        'porcentaje_participacion',
                        'puede_modificar_plan',
                        'puede_crear_empresas',
                        'puede_asignar_usuarios',
                        'fecha_desde',
                        'fecha_hasta',
                        'activo'
                    ])
                    ->withTimestamps();
    }

    // Relación con usuarios
    public function usuarios()
    {
        return $this->hasMany(User::class, 'grupo_empresa_id');
    }

    // País de origen
    public function paises()
    {
        return $this->belongsTo(Pais::class, 'pais_id');
    }


    // ==================== MÉTODOS DE NEGOCIO ====================

    /**
     * Verificar si está activo
     */
    public function estaActivo(): bool
    {
        return $this->estado === true;
    }
    
    /**
     * Verificar si el plan ha expirado
     */
    public function planExpirado(): bool
    {
        if (!$this->fecha_vencimiento) {
            return false;
        }
        
        return now()->isAfter($this->fecha_vencimiento);
    }

    /**
     * Verificar si puede crear más empresas
     */
    public function puedeCrearEmpresas(): bool
    {
        return $this->empresas()->count() < $this->max_empresas;
    }

    /**
     * Verificar si puede crear más usuarios
     */
    public function puedeCrearUsuarios(): bool
    {
        return $this->usuarios()->count() < $this->max_usuarios;
    }

    /**
     * Verificar si un módulo está habilitado
     */
    // public function tieneModulo(string $modulo): bool
    // {
    //     if (!$this->modulos_habilitados) {
    //         return false;
    //     }
        
    //     return in_array($modulo, $this->modulos_habilitados);
    // }

    /**
     * Obtener configuración visual con fallback
     */
    public function getConfiguracionVisual(): array
    {
        $default = [
            'tema' => 'light',
            'color_primario' => '#3B82F6',
            'color_secundario' => '#10B981',
            'fuente' => 'Inter',
            'logo' => null,
        ];

        return array_merge($default, $this->configuracion_visual ?? []);
    }

    /**
     * Activar grupo
     */
    public function activar(): bool
    {
        $this->estado = 'activo';
        $this->fecha_activacion = now();
        
        return $this->save();
    }

    /**
     * Suspender grupo
     */
    public function suspender(string $motivo = null): bool
    {
        $this->estado = 'suspendido';
        
        // Registrar log
        activity()
            ->performedOn($this)
            ->withProperties(['motivo' => $motivo])
            ->log('Grupo suspendido');
        
        return $this->save();
    }

    /**
     * Obtener ruta del dashboard del grupo
     */
    public function rutaDashboard(): string
    {
        return route('grupo.dashboard', ['grupo' => $this->slug]);
    }

    // ==================== ACCESSORS ====================
    
    /**
     * URL del grupo
     */
    public function getUrlAttribute(): string
    {
        return url($this->slug);
    }

    /**
     * Cantidad de empresas
     */
    public function getCantidadEmpresasAttribute(): int
    {
        return $this->empresas()->count();
    }

    /**
     * Cantidad de usuarios
     */
    public function getCantidadUsuariosAttribute(): int
    {
        return $this->usuarios()->count();
    }


    
    // ==================== SCOPE GENERAL ====================

    protected static function booted(): void
    {
        static::addGlobalScope(new FiltroMultiempresaScope);
    }
    
    // ==================== BOOT ====================
    
    protected static function boot()
    {
        parent::boot();

        // Auto-generar slug al crear
        static::creating(function ($grupo) {
            if (empty($grupo->slug)) {
                $grupo->slug = Str::slug($grupo->nombre);
            }
            
            // Verificar unicidad del slug
            $originalSlug = $grupo->slug;
            $count = 1;
            while (static::where('slug', $grupo->slug)->exists()) {
                $grupo->slug = $originalSlug . '-' . $count;
                $count++;
            }
        });
    }

    // ==================== RELACIONES ====================
        
    // // Relación con empresas
    // public function empresas()
    // {
    //     return $this->hasMany(Empresa::class, 'grupo_empresa_id');
    // }

    /**
     * Empresas del grupo
     */
    public function empresas(): HasMany
    {
        return $this->hasMany(Empresa::class, 'grupo_empresa_id');
    }

    /**
     * Sedes del grupo
     */
    public function sedes(): HasMany
    {
        return $this->hasMany(Sede::class, 'grupo_empresa_id');
    }

    /**
     * Locales del grupo
     */
    public function locales(): HasMany
    {
        return $this->hasMany(Local::class, 'grupo_empresa_id');
    }

    /**
     * Propietarios activos
     */
    public function propietariosActivos(): BelongsToMany
    {
        return $this->propietarios()->wherePivot('activo', true);
    }

    // ==================== SCOPES ====================
    
    /**
     * Grupos activos
     */
    public function scopeActivo($query)
    {
        return $query->where('estado', 'activo');
    }

    /**
     * Grupos por estado
     */
    public function scopeEstado($query, string $estado)
    {
        return $query->where('estado', $estado);
    }

    /**
     * Buscar por slug
     */
    public function scopeBySlug($query, string $slug)
    {
        return $query->where('slug', $slug);
    }

}
