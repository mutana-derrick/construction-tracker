<?php

namespace App\Policies;

use App\Models\MaterialUsage;
use App\Models\User;


class MaterialUsagePolicy
{
    /**
     * Determine whether the user can view any material usage logs.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view logs
    }

    /**
     * Determine whether the user can view the material usage log.
     */
    public function view(User $user, MaterialUsage $log): bool
    {
        return true; // All authenticated users can view any log
    }

    /**
     * Determine whether the user can create material usage logs.
     * 
     * ⚙️ BUSINESS RULE: Only "Recorder" role users can create logs
     */
    public function create(User $user): bool
    {
        return $user->role === 'recorder';
    }

    /**
     * Determine whether the user can update the material usage log.
     * 
     * ⏱️ CRITICAL RULE: Only within 5 minutes of creation, user who created it, Recorder role only
     */
    public function update(User $user, MaterialUsage $log): bool
    {
        if ($user->role !== 'recorder') {
            return false;
        }

        if ($user->id !== $log->user_id) {
            return false;
        }

        return $log->created_at->copy()->addMinutes(5)->isFuture();
    }


    /**
     * Determine whether the user can delete the material usage log.
     * 
     * ❌ BUSINESS RULE: No deletion allowed for audit compliance
     */
    public function delete(User $user, MaterialUsage $log): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the material usage log.
     */
    public function restore(User $user, MaterialUsage $log): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the material usage log.
     */
    public function forceDelete(User $user, MaterialUsage $log): bool
    {
        return false;
    }
}