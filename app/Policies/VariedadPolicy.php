<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Variedad;
use Illuminate\Auth\Access\HandlesAuthorization;

class VariedadPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Variedad');
    }

    public function view(AuthUser $authUser, Variedad $variedad): bool
    {
        return $authUser->can('View:Variedad');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Variedad');
    }

    public function update(AuthUser $authUser, Variedad $variedad): bool
    {
        return $authUser->can('Update:Variedad');
    }

    public function delete(AuthUser $authUser, Variedad $variedad): bool
    {
        return $authUser->can('Delete:Variedad');
    }

    public function restore(AuthUser $authUser, Variedad $variedad): bool
    {
        return $authUser->can('Restore:Variedad');
    }

    public function forceDelete(AuthUser $authUser, Variedad $variedad): bool
    {
        return $authUser->can('ForceDelete:Variedad');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Variedad');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Variedad');
    }

    public function replicate(AuthUser $authUser, Variedad $variedad): bool
    {
        return $authUser->can('Replicate:Variedad');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Variedad');
    }

}