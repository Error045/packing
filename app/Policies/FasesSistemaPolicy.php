<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\FasesSistema;
use Illuminate\Auth\Access\HandlesAuthorization;

class FasesSistemaPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:FasesSistema');
    }

    public function view(AuthUser $authUser, FasesSistema $fasesSistema): bool
    {
        return $authUser->can('View:FasesSistema');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:FasesSistema');
    }

    public function update(AuthUser $authUser, FasesSistema $fasesSistema): bool
    {
        return $authUser->can('Update:FasesSistema');
    }

    public function delete(AuthUser $authUser, FasesSistema $fasesSistema): bool
    {
        return $authUser->can('Delete:FasesSistema');
    }

    public function restore(AuthUser $authUser, FasesSistema $fasesSistema): bool
    {
        return $authUser->can('Restore:FasesSistema');
    }

    public function forceDelete(AuthUser $authUser, FasesSistema $fasesSistema): bool
    {
        return $authUser->can('ForceDelete:FasesSistema');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:FasesSistema');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:FasesSistema');
    }

    public function replicate(AuthUser $authUser, FasesSistema $fasesSistema): bool
    {
        return $authUser->can('Replicate:FasesSistema');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:FasesSistema');
    }

}