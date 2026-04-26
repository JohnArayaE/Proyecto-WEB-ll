<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Assignment;
use Illuminate\Auth\Access\Response;

class AssignmentPolicy
{
    public function viewAny(User $user): Response
    {
        return (int) $user->role_id === 2
            ? Response::allow()
            : Response::deny('No tiene permiso para ver el módulo de asignaciones');
    }

    public function view(User $user, Assignment $assignment): Response
    {
        return (int) $user->role_id === 2
            ? Response::allow()
            : Response::deny('No tiene permiso para ver esta asignación');
    }

    public function create(User $user): Response
    {
        return (int) $user->role_id === 2
            ? Response::allow()
            : Response::deny('No tiene permiso para crear asignaciones');
    }

    public function update(User $user, Assignment $assignment): Response
    {
        return (int) $user->role_id === 2
            ? Response::allow()
            : Response::deny('No tiene permiso para actualizar esta asignación');
    }

    public function delete(User $user, Assignment $assignment): Response
    {
        return (int) $user->role_id === 2
            ? Response::allow()
            : Response::deny('No tiene permiso para eliminar esta asignación');
    }

    public function restore(User $user, Assignment $assignment): Response
    {
        return (int) $user->role_id === 2
            ? Response::allow()
            : Response::deny('No tiene permiso para restaurar esta asignación');
    }

    public function forceDelete(User $user, Assignment $assignment): Response
    {
        return (int) $user->role_id === 2
            ? Response::allow()
            : Response::deny('No tiene permiso para eliminar permanentemente esta asignación');
    }
}