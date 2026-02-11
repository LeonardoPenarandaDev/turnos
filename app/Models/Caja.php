<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    protected $fillable = [
        'numero',
        'nombre',
        'activa'
    ];

    protected $casts = [
        'activa' => 'boolean',
    ];

    public function turnos()
    {
        return $this->hasMany(Turno::class);
    }

    public function cajeros()
    {
        return $this->hasMany(User::class);
    }
}
