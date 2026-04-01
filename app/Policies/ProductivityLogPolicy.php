<?php

namespace App\Policies;

use App\Models\ProductivityLog;
use App\Models\User;
use Illuminate\Auth\Access\Response;

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
        // Must be Recorder role
        if ($user->role !== 'recorder') {
            return Response::deny('Only users with Recorder role can edit productivity logs.');
        }

        // Must be the user who created it
        if ($user->id !== $log->user_id) {
            return Response::deny('You can only edit your own productivity logs.');
        }

        // Check 5-minute window
        $minutesElapsed = now()->diffInMinutes($log->created_at);
        if ($minutesElapsed > 5) {
            return Response::deny("Productivity log records can only be edited within 5 minutes of creation. ({$minutesElapsed} minutes have passed)");
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can delete the productivity log.
     * 
     * ❌ BUSINESS RULE: No deletion allowed for audit compliance
     */
    public function delete(User $user, ProductivityLog $log): bool
    {
        return Response::deny('Productivity log records cannot be deleted to maintain audit compliance.');
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