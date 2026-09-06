<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\TiposUbicaciones;
use Illuminate\Auth\Access\HandlesAuthorization;

class TiposUbicacionesPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:TiposUbicaciones');
    }

    public function view(AuthUser $authUser, TiposUbicaciones $tiposUbicaciones): bool
    {
        return $authUser->can('View:TiposUbicaciones');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:TiposUbicaciones');
    }

    public function update(AuthUser $authUser, TiposUbicaciones $tiposUbicaciones): bool
    {
        return $authUser->can('Update:TiposUbicaciones');
    }

    public function delete(AuthUser $authUser, TiposUbicaciones $tiposUbicaciones): bool
    {
        return $authUser->can('Delete:TiposUbicaciones');
    }

    public function restore(AuthUser $authUser, TiposUbicaciones $tiposUbicaciones): bool
    {
        return $authUser->can('Restore:TiposUbicaciones');
    }

    public function forceDelete(AuthUser $authUser, TiposUbicaciones $tiposUbicaciones): bool
    {
        return $authUser->can('ForceDelete:TiposUbicaciones');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:TiposUbicaciones');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:TiposUbicaciones');
    }

    public function replicate(AuthUser $authUser, TiposUbicaciones $tiposUbicaciones): bool
    {
        return $authUser->can('Replicate:TiposUbicaciones');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:TiposUbicaciones');
    }

}