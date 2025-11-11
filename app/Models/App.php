<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class App extends Model
{
    use HasFactory;

    protected $table = 'apps';

    protected $fillable = [
        'nombre',
        'descripcion',
        'orden',
        'icono',
        'estado',
    ];

    public function modulos()
    {
        return $this->hasMany(Modulo::class, 'app_id');
    }

}
