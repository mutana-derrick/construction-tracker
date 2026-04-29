<?php

namespace App\Policies;

use App\Models\CasualLabourLog;
use App\Models\User;


class CasualLabourLogPolicy
{
    /**
     * Determine whether the user can view any casual labour logs.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view logs
    }

    /**
     * Determine whether the user can view the casual labour log.
     */
    public function view(User $user, CasualLabourLog $log): bool
    {
        return true; // All authenticated users can view any log
    }

    /**
     * Determine whether the user can create casual labour logs.
     * 
     * ⚙️ BUSINESS RULE: Only "Recorder" role users can create logs
     */
    public function create(User $user): bool
    {
        return $user->role === 'recorder';
    }

    /**
     * Determine whether the user can update the casual labour log.
     * 
     * ⏱️ CRITICAL RULE: Only within 5 minutes of creation, user who created it, Recorder role only
     */
    public function update(User $user, CasualLabourLog $log): bool
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
     * Determine whether the user can delete the casual labour log.
     * 
     * ❌ BUSINESS RULE: No deletion allowed for audit compliance
     */
    public function delete(User $user, CasualLabourLog $log): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the casual labour log.
     */
    public function restore(User $user, CasualLabourLog $log): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the casual labour log.
     */
    public function forceDelete(User $user, CasualLabourLog $log): bool
    {
        return false;
    }
}