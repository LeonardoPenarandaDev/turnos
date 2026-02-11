<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Turno extends Model
{
    protected $fillable = [
        'codigo',
        'tipo_documento',
        'numero_documento',
        'nombre_completo',
        'prioridad',
        'tipo_tramite_id',
        'estado',
        'caja_id',
        'user_id',
        'hora_solicitud',
        'hora_llamado',
        'hora_inicio_atencion',
        'hora_fin_atencion',
        'tiempo_atencion',
        'observaciones'
    ];

    protected $casts = [
        'hora_solicitud' => 'datetime',
        'hora_llamado' => 'datetime',
        'hora_inicio_atencion' => 'datetime',
        'hora_fin_atencion' => 'datetime',
    ];

    // Relaciones
    public function tipoTramite()
    {
        return $this->belongsTo(TipoTramite::class);
    }

    public function caja()
    {
        return $this->belongsTo(Caja::class);
    }

    public function cajero()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Scopes
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente')
            ->orderByRaw("CASE prioridad WHEN 'embarazada' THEN 1 WHEN 'tercera_edad' THEN 2 ELSE 3 END")
            ->orderBy('hora_solicitud');
    }

    public function scopeEnAtencion($query)
    {
        return $query->where('estado', 'en_atencion');
    }

    public function scopeAtendidos($query)
    {
        return $query->where('estado', 'atendido');
    }

    public function scopeLlamados($query)
    {
        return $query->where('estado', 'llamado');
    }

    public function scopeHoy($query)
    {
        return $query->whereDate('created_at', Carbon::today());
    }

    // Métodos
    public static function generarCodigo()
    {
        return \DB::transaction(function () {
            // Usar lockForUpdate para evitar race conditions
            $ultimoTurno = self::whereDate('created_at', Carbon::today())
                ->orderBy('id', 'desc')
                ->lockForUpdate()
                ->first();

            if (!$ultimoTurno) {
                return 'A001';
            }

            $letra = substr($ultimoTurno->codigo, 0, 1);
            $numero = intval(substr($ultimoTurno->codigo, 1));

            if ($numero >= 999) {
                // Si llega a 999, pasar a la siguiente letra
                $letra = chr(ord($letra) + 1);
                $numero = 1;
            } else {
                $numero++;
            }

            return $letra . str_pad($numero, 3, '0', STR_PAD_LEFT);
        });
    }

    public function calcularTiempoEspera()
    {
        if (!$this->hora_solicitud) {
            return 0;
        }

        // Si ya fue llamado, calcular hasta ese momento; si no, hasta ahora
        $hasta = $this->hora_llamado ?? Carbon::now();
        return $this->hora_solicitud->diffInMinutes($hasta);
    }

    public function calcularTiempoAtencion()
    {
        if (!$this->hora_inicio_atencion) {
            return 0;
        }

        if ($this->hora_fin_atencion) {
            return $this->hora_inicio_atencion->diffInSeconds($this->hora_fin_atencion);
        }

        // Si está en atención pero no ha finalizado, calcular el tiempo parcial
        if ($this->estado === 'en_atencion') {
            return $this->hora_inicio_atencion->diffInSeconds(Carbon::now());
        }

        return 0;
    }
}
