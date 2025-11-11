<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menus';

    protected $fillable = [
        'nombre',
        'url',
        'icono',
        'orden',
        'estado',
        'modulo_id',
    ];


    public function modulos()
    {
        return $this->belongsTo(Modulo::class, 'modulo_id');
    }

}
