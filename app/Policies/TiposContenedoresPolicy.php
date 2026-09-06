<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\TiposContenedores;
use Illuminate\Auth\Access\HandlesAuthorization;

class TiposContenedoresPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:TiposContenedores');
    }

    public function view(AuthUser $authUser, TiposContenedores $tiposContenedores): bool
    {
        return $authUser->can('View:TiposContenedores');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:TiposContenedores');
    }

    public function update(AuthUser $authUser, TiposContenedores $tiposContenedores): bool
    {
        return $authUser->can('Update:TiposContenedores');
    }

    public function delete(AuthUser $authUser, TiposContenedores $tiposContenedores): bool
    {
        return $authUser->can('Delete:TiposContenedores');
    }

    public function restore(AuthUser $authUser, TiposContenedores $tiposContenedores): bool
    {
        return $authUser->can('Restore:TiposContenedores');
    }

    public function forceDelete(AuthUser $authUser, TiposContenedores $tiposContenedores): bool
    {
        return $authUser->can('ForceDelete:TiposContenedores');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:TiposContenedores');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:TiposContenedores');
    }

    public function replicate(AuthUser $authUser, TiposContenedores $tiposContenedores): bool
    {
        return $authUser->can('Replicate:TiposContenedores');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:TiposContenedores');
    }

}