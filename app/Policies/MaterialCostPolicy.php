<?php

namespace App\Policies;

use App\Models\MaterialCost;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MaterialCostPolicy
{
    /**
     * Determine whether the user can view any material cost logs.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view logs
    }

    /**
     * Determine whether the user can view the material cost log.
     */
    public function view(User $user, MaterialCost $log): bool
    {
        return true; // All authenticated users can view any log
    }

    /**
     * Determine whether the user can create material cost logs.
     * 
     * ⚙️ BUSINESS RULE: Only "Recorder" role users can create logs
     */
    public function create(User $user): bool
    {
        return $user->role === 'recorder';
    }

    /**
     * Determine whether the user can update the material cost log.
     * 
     * ⏱️ CRITICAL RULE: Only within 5 minutes of creation, user who created it, Recorder role only
     */
    public function update(User $user, MaterialCost $log): bool
    {
        // Must be Recorder role
        if ($user->role !== 'recorder') {
            return Response::deny('Only users with Recorder role can edit material cost logs.');
        }

        // Must be the user who created it
        if ($user->id !== $log->user_id) {
            return Response::deny('You can only edit your own material cost logs.');
        }

        // Check 5-minute window
        $minutesElapsed = now()->diffInMinutes($log->created_at);
        if ($minutesElapsed > 5) {
            return Response::deny("Material cost log records can only be edited within 5 minutes of creation. ({$minutesElapsed} minutes have passed)");
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can delete the material cost log.
     * 
     * ❌ BUSINESS RULE: No deletion allowed for audit compliance
     */
    public function delete(User $user, MaterialCost $log): bool
    {
        return Response::deny('Material cost log records cannot be deleted to maintain audit compliance.');
    }

    /**
     * Determine whether the user can restore the material cost log.
     */
    public function restore(User $user, MaterialCost $log): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the material cost log.
     */
    public function forceDelete(User $user, MaterialCost $log): bool
    {
        return false;
    }
}
