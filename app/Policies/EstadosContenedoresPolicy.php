<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\EstadosContenedores;
use Illuminate\Auth\Access\HandlesAuthorization;

class EstadosContenedoresPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:EstadosContenedores');
    }

    public function view(AuthUser $authUser, EstadosContenedores $estadosContenedores): bool
    {
        return $authUser->can('View:EstadosContenedores');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:EstadosContenedores');
    }

    public function update(AuthUser $authUser, EstadosContenedores $estadosContenedores): bool
    {
        return $authUser->can('Update:EstadosContenedores');
    }

    public function delete(AuthUser $authUser, EstadosContenedores $estadosContenedores): bool
    {
        return $authUser->can('Delete:EstadosContenedores');
    }

    public function restore(AuthUser $authUser, EstadosContenedores $estadosContenedores): bool
    {
        return $authUser->can('Restore:EstadosContenedores');
    }

    public function forceDelete(AuthUser $authUser, EstadosContenedores $estadosContenedores): bool
    {
        return $authUser->can('ForceDelete:EstadosContenedores');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:EstadosContenedores');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:EstadosContenedores');
    }

    public function replicate(AuthUser $authUser, EstadosContenedores $estadosContenedores): bool
    {
        return $authUser->can('Replicate:EstadosContenedores');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:EstadosContenedores');
    }

}