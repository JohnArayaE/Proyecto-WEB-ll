<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Auth\Access\Response;

class VehiclePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return (int) $user->role_id === 1 || $user->role_id === 2
            ? Response::allow()
            : Response::deny("No tiene permiso para el módulo de vehiculo");
        
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Vehicle $vehicle): Response
    {
        return (int) $user->role_id === 1 || $user->role_id === 2
            ? Response::allow()
            : Response::deny("No tiene permiso para el módulo de vehiculo");
        
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return (int) $user->role_id === 1 || $user->role_id === 2
            ? Response::allow()
            : Response::deny("No tiene permiso para el módulo de vehiculo");
        
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Vehicle $vehicle): Response
    {
        return (int) $user->role_id === 1 || $user->role_id === 2
            ? Response::allow()
            : Response::deny("No tiene permiso para el módulo de vehiculo");
        
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Vehicle $vehicle): Response
    {
        return (int) $user->role_id === 1 || $user->role_id === 2
            ? Response::allow()
            : Response::deny("No tiene permiso para el módulo de vehiculo");
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Vehicle $vehicle): Response
    {
        return (int) $user->role_id === 1 || $user->role_id === 2
            ? Response::allow()
            : Response::deny("No tiene permiso para el módulo de vehiculo");
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Vehicle $vehicle): Response
    {
        return (int) $user->role_id === 1
            ? Response::allow()
            : Response::deny("No tiene permiso para el módulo de vehiculo");
    }
}
