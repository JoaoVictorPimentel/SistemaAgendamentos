<?php

namespace App\Policies;

use App\Models\Agendamento;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AgendamentoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->admin === 1;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Agendamento $agendamento): bool
    {
        if ($user->admin === 1) {
            return true;
        }

        return $user->id === $agendamento->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Agendamento $agendamento): bool
    {
        if ($user->admin === 1) {
            return true;
        }

        return $user->id === $agendamento->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Agendamento $agendamento): bool
    {
        if ($user->admin === 1) {
            return true;
        }

        return $user->id === $agendamento->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Agendamento $agendamento): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Agendamento $agendamento): bool
    {
        return false;
    }
}
