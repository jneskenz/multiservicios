<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    use HasFactory;

    protected $table = 'modulos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'icono',
        'orden',
        'estado',
    ];

    public function menus()
    {
        return $this->hasMany(Menu::class, 'modulo_id');
    }

    public function apps()
    {
        return $this->belongsTo(App::class, 'app_id');
    }

}
