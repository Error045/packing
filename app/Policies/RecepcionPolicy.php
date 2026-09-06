<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Recepcion;
use Illuminate\Auth\Access\HandlesAuthorization;

class RecepcionPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Recepcion');
    }

    public function view(AuthUser $authUser, Recepcion $recepcion): bool
    {
        return $authUser->can('View:Recepcion');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Recepcion');
    }

    public function update(AuthUser $authUser, Recepcion $recepcion): bool
    {
        return $authUser->can('Update:Recepcion');
    }

    public function delete(AuthUser $authUser, Recepcion $recepcion): bool
    {
        return $authUser->can('Delete:Recepcion');
    }

    public function restore(AuthUser $authUser, Recepcion $recepcion): bool
    {
        return $authUser->can('Restore:Recepcion');
    }

    public function forceDelete(AuthUser $authUser, Recepcion $recepcion): bool
    {
        return $authUser->can('ForceDelete:Recepcion');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Recepcion');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Recepcion');
    }

    public function replicate(AuthUser $authUser, Recepcion $recepcion): bool
    {
        return $authUser->can('Replicate:Recepcion');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Recepcion');
    }

}