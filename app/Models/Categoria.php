<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $fillable = [
        'nombre',
        'tipo'
    ];

    public function movimientos() 
    {
        return $this->hasMany(Movimiento::class);
    }

    public function presupuestos() 
    {
        return $this->hasMany(Presupuesto::class);
    }
}
