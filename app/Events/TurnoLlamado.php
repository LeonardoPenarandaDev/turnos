<?php

namespace App\Events;

use App\Models\Turno;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TurnoLlamado
{
    use Dispatchable, SerializesModels;

    public $turno;

    /**
     * Create a new event instance.
     */
    public function __construct(Turno $turno)
    {
        $this->turno = $turno->load(['tipoTramite', 'caja']);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('turnos'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'turno.llamado';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'turno' => [
                'id' => $this->turno->id,
                'codigo' => $this->turno->codigo,
                'estado' => $this->turno->estado,
                'caja' => [
                    'numero' => $this->turno->caja->numero,
                    'nombre' => $this->turno->caja->nombre,
                ],
                'tipo_tramite' => [
                    'nombre' => $this->turno->tipoTramite->nombre,
                ]
            ]
        ];
    }
}
