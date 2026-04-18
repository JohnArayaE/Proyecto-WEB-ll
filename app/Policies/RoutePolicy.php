<?php

namespace App\Policies;

use App\Models\Route;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RoutePolicy
{
    public function viewAny(User $user): Response
    {
        return (int) $user->role_id === 2
            ? Response::allow()
            : Response::deny('No tiene permiso para ver el listado de rutas.');
    }

    public function view(User $user, Route $route): Response
    {
        return (int) $user->role_id === 2
            ? Response::allow()
            : Response::deny('No tiene permiso para ver esta ruta.');
    }

    public function create(User $user): Response
    {
        return (int) $user->role_id === 2
            ? Response::allow()
            : Response::deny('No tiene permiso para crear rutas.');
    }

    public function update(User $user, Route $route): Response
    {
        return (int) $user->role_id === 2
            ? Response::allow()
            : Response::deny('No tiene permiso para actualizar rutas.');
    }

    public function delete(User $user, Route $route): Response
    {
        return (int) $user->role_id === 2
            ? Response::allow()
            : Response::deny('No tiene permiso para eliminar rutas.');
    }

    public function restore(User $user, Route $route): Response
    {
        return (int) $user->role_id === 2
            ? Response::allow()
            : Response::deny('No tiene permiso para restaurar rutas.');
    }

    public function forceDelete(User $user, Route $route): Response
    {
        return (int) $user->role_id === 2
            ? Response::allow()
            : Response::deny('No tiene permiso para eliminar permanentemente rutas.');
    }
}