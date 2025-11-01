<?php

namespace App\Models;

use App\Models\Workspace\Empresa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

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

    // Relación con empresas
    public function empresas()
    {
        return $this->hasMany(Empresa::class, 'grupo_empresa_id');
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
}
