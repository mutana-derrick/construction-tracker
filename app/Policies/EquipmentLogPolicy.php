<?php

namespace App\Policies;

use App\Models\User;
use App\Models\EquipmentLog;
use Illuminate\Auth\Access\Response;

class EquipmentLogPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, EquipmentLog $equipmentLog): bool
    {
        // User can view if they created it or are a recorder
        return $user->id === $equipmentLog->user_id || $user->role === 'recorder';
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Anyone can view the list
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only recorders can create equipment logs
        return $user->role === 'recorder';
    }

    /**
     * Determine whether the user can update the model.
     * 
     * ⏱️ CRITICAL BUSINESS RULE: Only allow editing within 5 minutes of creation
     */
    public function update(User $user, EquipmentLog $equipmentLog): Response
    {
        // Only the creator can edit
        if ($user->id !== $equipmentLog->user_id) {
            return Response::deny('You can only edit your own equipment logs.');
        }

        // Only recorders can edit
        if ($user->role !== 'recorder') {
            return Response::deny('Only recorders can edit equipment logs.');
        }

        // 5-minute edit window enforcement
        $minutesElapsed = now()->diffInMinutes($equipmentLog->created_at);
        if ($minutesElapsed > 5) {
            return Response::deny("Equipment logs can only be edited within 5 minutes of creation. {$minutesElapsed} minutes have elapsed.");
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can delete the model.
     * 
     * ❌ AUDIT RULE: No deletion allowed - maintain complete audit trail
     */
    public function delete(User $user, EquipmentLog $equipmentLog): Response
    {
        return Response::deny('Equipment logs cannot be deleted to maintain audit compliance.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, EquipmentLog $equipmentLog): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, EquipmentLog $equipmentLog): bool
    {
        return false;
    }
}