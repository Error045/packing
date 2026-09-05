<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\TiposOperaciones;
use Illuminate\Auth\Access\HandlesAuthorization;

class TiposOperacionesPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:TiposOperaciones');
    }

    public function view(AuthUser $authUser, TiposOperaciones $tiposOperaciones): bool
    {
        return $authUser->can('View:TiposOperaciones');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:TiposOperaciones');
    }

    public function update(AuthUser $authUser, TiposOperaciones $tiposOperaciones): bool
    {
        return $authUser->can('Update:TiposOperaciones');
    }

    public function delete(AuthUser $authUser, TiposOperaciones $tiposOperaciones): bool
    {
        return $authUser->can('Delete:TiposOperaciones');
    }

    public function restore(AuthUser $authUser, TiposOperaciones $tiposOperaciones): bool
    {
        return $authUser->can('Restore:TiposOperaciones');
    }

    public function forceDelete(AuthUser $authUser, TiposOperaciones $tiposOperaciones): bool
    {
        return $authUser->can('ForceDelete:TiposOperaciones');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:TiposOperaciones');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:TiposOperaciones');
    }

    public function replicate(AuthUser $authUser, TiposOperaciones $tiposOperaciones): bool
    {
        return $authUser->can('Replicate:TiposOperaciones');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:TiposOperaciones');
    }

}