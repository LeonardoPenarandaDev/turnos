<?php

namespace App\Policies;

use App\Models\Turno;
use App\Models\User;

class TurnoPolicy
{
    /**
     * Determine if the user can view any turnos.
     */
    public function viewAny(User $user): bool
    {
        return $user->rol === 'admin' || $user->rol === 'cajero';
    }

    /**
     * Determine if the user can view the turno.
     */
    public function view(User $user, Turno $turno): bool
    {
        // Admin puede ver todos, cajero solo los de su caja
        if ($user->rol === 'admin') {
            return true;
        }

        if ($user->rol === 'cajero') {
            return $turno->caja_id == $user->caja_id || $turno->user_id == $user->id;
        }

        return false;
    }

    /**
     * Determine if the user can call the turno.
     */
    public function call(User $user, Turno $turno): bool
    {
        // Solo cajeros pueden llamar turnos y deben estar asignados a una caja
        return ($user->rol === 'cajero' || $user->rol === 'admin') && $user->caja_id !== null;
    }

    /**
     * Determine if the user can attend the turno.
     */
    public function attend(User $user, Turno $turno): bool
    {
        // Solo puede atender el cajero que lo llamó o un admin
        if ($user->rol === 'admin') {
            return true;
        }

        return $user->rol === 'cajero' && $turno->user_id == $user->id;
    }

    /**
     * Determine if the user can cancel the turno.
     */
    public function cancel(User $user, Turno $turno): bool
    {
        // Admin puede cancelar cualquier turno
        if ($user->rol === 'admin') {
            return true;
        }

        // Cajero solo puede cancelar turnos que él mismo está atendiendo
        if ($user->rol === 'cajero') {
            return $turno->user_id == $user->id &&
                   in_array($turno->estado, ['llamado', 'en_atencion']);
        }

        return false;
    }

    /**
     * Determine if the user can transfer the turno.
     */
    public function transfer(User $user, Turno $turno): bool
    {
        // Solo admin puede transferir turnos
        if ($user->rol === 'admin') {
            return true;
        }

        // Cajero puede transferir si es su turno y está en estado apropiado
        if ($user->rol === 'cajero') {
            return $turno->user_id == $user->id &&
                   in_array($turno->estado, ['llamado', 'en_atencion']);
        }

        return false;
    }

    /**
     * Determine if the user can finish attending the turno.
     */
    public function finish(User $user, Turno $turno): bool
    {
        // Solo puede finalizar el cajero que lo está atendiendo
        return ($user->rol === 'cajero' || $user->rol === 'admin') &&
               $turno->user_id == $user->id &&
               $turno->estado === 'en_atencion';
    }
}
