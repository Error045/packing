<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\TiposRecepciones;
use Illuminate\Auth\Access\HandlesAuthorization;

class TiposRecepcionesPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:TiposRecepciones');
    }

    public function view(AuthUser $authUser, TiposRecepciones $tiposRecepciones): bool
    {
        return $authUser->can('View:TiposRecepciones');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:TiposRecepciones');
    }

    public function update(AuthUser $authUser, TiposRecepciones $tiposRecepciones): bool
    {
        return $authUser->can('Update:TiposRecepciones');
    }

    public function delete(AuthUser $authUser, TiposRecepciones $tiposRecepciones): bool
    {
        return $authUser->can('Delete:TiposRecepciones');
    }

    public function restore(AuthUser $authUser, TiposRecepciones $tiposRecepciones): bool
    {
        return $authUser->can('Restore:TiposRecepciones');
    }

    public function forceDelete(AuthUser $authUser, TiposRecepciones $tiposRecepciones): bool
    {
        return $authUser->can('ForceDelete:TiposRecepciones');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:TiposRecepciones');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:TiposRecepciones');
    }

    public function replicate(AuthUser $authUser, TiposRecepciones $tiposRecepciones): bool
    {
        return $authUser->can('Replicate:TiposRecepciones');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:TiposRecepciones');
    }

}