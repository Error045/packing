<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Calibre;
use Illuminate\Auth\Access\HandlesAuthorization;

class CalibrePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Calibre');
    }

    public function view(AuthUser $authUser, Calibre $calibre): bool
    {
        return $authUser->can('View:Calibre');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Calibre');
    }

    public function update(AuthUser $authUser, Calibre $calibre): bool
    {
        return $authUser->can('Update:Calibre');
    }

    public function delete(AuthUser $authUser, Calibre $calibre): bool
    {
        return $authUser->can('Delete:Calibre');
    }

    public function restore(AuthUser $authUser, Calibre $calibre): bool
    {
        return $authUser->can('Restore:Calibre');
    }

    public function forceDelete(AuthUser $authUser, Calibre $calibre): bool
    {
        return $authUser->can('ForceDelete:Calibre');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Calibre');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Calibre');
    }

    public function replicate(AuthUser $authUser, Calibre $calibre): bool
    {
        return $authUser->can('Replicate:Calibre');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Calibre');
    }

}