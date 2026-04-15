<?php

namespace App\Policies;

use App\Models\Request;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RequestPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return (int) $user->role_id === 1 || $user->role_id === 2 || $user->role_id === 3;
            ? Response::allow()
            : Response::deny("No tiene permiso para el módulo de Solicitudes");
        
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Request $request): Response
    {
         return (int) $user->role_id === 1 || $user->role_id === 2 || $user->role_id === 3
            ? Response::allow()
            : Response::deny("No tiene permiso para el módulo de Solicitudes");
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return (int) $user->role_id === 1 || $user->role_id === 3
            ? Response::allow()
            : Response::deny("No tiene permiso para el módulo de Solicitudes");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Request $request): Response
    {
        return (int) $user->role_id === 1 || $user->role_id === 3
            ? Response::allow()
            : Response::deny("No tiene permiso para el módulo de Solicitudes");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Request $request): Response
    {
        return (int) $user->role_id === 1 || $user->role_id === 3
            ? Response::allow()
            : Response::deny("No tiene permiso para el módulo de Solicitudes");
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Request $request): Response
    {
        return (int) $user->role_id === 1 || $user->role_id === 3
            ? Response::allow()
            : Response::deny("No tiene permiso para el módulo de Solicitudes");
    }
    

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Request $request): Response
    {
        return (int) $user->role_id === 1
            ? Response::allow()
            : Response::deny("No tiene permiso para el módulo de Solitudes");
    }
}
