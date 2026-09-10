<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\EstadosProcesos;
use Illuminate\Auth\Access\HandlesAuthorization;

class EstadosProcesosPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:EstadosProcesos');
    }

    public function view(AuthUser $authUser, EstadosProcesos $estadosProcesos): bool
    {
        return $authUser->can('View:EstadosProcesos');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:EstadosProcesos');
    }

    public function update(AuthUser $authUser, EstadosProcesos $estadosProcesos): bool
    {
        return $authUser->can('Update:EstadosProcesos');
    }

    public function delete(AuthUser $authUser, EstadosProcesos $estadosProcesos): bool
    {
        return $authUser->can('Delete:EstadosProcesos');
    }

    public function restore(AuthUser $authUser, EstadosProcesos $estadosProcesos): bool
    {
        return $authUser->can('Restore:EstadosProcesos');
    }

    public function forceDelete(AuthUser $authUser, EstadosProcesos $estadosProcesos): bool
    {
        return $authUser->can('ForceDelete:EstadosProcesos');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:EstadosProcesos');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:EstadosProcesos');
    }

    public function replicate(AuthUser $authUser, EstadosProcesos $estadosProcesos): bool
    {
        return $authUser->can('Replicate:EstadosProcesos');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:EstadosProcesos');
    }

}