<?php

namespace App\Policies;

use App\Models\ProductivityLog;
use App\Models\User;


class ProductivityLogPolicy
{
    /**
     * Determine whether the user can view any productivity logs.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view logs
    }

    /**
     * Determine whether the user can view the productivity log.
     */
    public function view(User $user, ProductivityLog $log): bool
    {
        return true; // All authenticated users can view any log
    }

    /**
     * Determine whether the user can create productivity logs.
     * 
     * ⚙️ BUSINESS RULE: Only "Recorder" role users can create logs
     */
    public function create(User $user): bool
    {
        return $user->role === 'recorder';
    }

    /**
     * Determine whether the user can update the productivity log.
     * 
     * ⏱️ CRITICAL RULE: Only within 5 minutes of creation, user who created it, Recorder role only
     */
    public function update(User $user, ProductivityLog $log): bool
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
     * Determine whether the user can delete the productivity log.
     * 
     * ❌ BUSINESS RULE: No deletion allowed for audit compliance
     */
    public function delete(User $user, ProductivityLog $log): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the productivity log.
     */
    public function restore(User $user, ProductivityLog $log): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the productivity log.
     */
    public function forceDelete(User $user, ProductivityLog $log): bool
    {
        return false;
    }
}