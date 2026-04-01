<?php

namespace App\Policies;

use App\Models\User;
use App\Models\EquipmentCost;
use Illuminate\Auth\Access\Response;

class EquipmentCostPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, EquipmentCost $equipmentCost): bool
    {
        // User can view if they created it or are a recorder
        return $user->id === $equipmentCost->user_id || $user->role === 'recorder';
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
        // Only recorders can create equipment cost records
        return $user->role === 'recorder';
    }

    /**
     * Determine whether the user can update the model.
     * 
     * ⏱️ CRITICAL BUSINESS RULE: Only allow editing within 5 minutes of creation
     */
    public function update(User $user, EquipmentCost $equipmentCost): Response
    {
        // Only the creator can edit
        if ($user->id !== $equipmentCost->user_id) {
            return Response::deny('You can only edit your own equipment cost records.');
        }

        // Only recorders can edit
        if ($user->role !== 'recorder') {
            return Response::deny('Only recorders can edit equipment cost records.');
        }

        // 5-minute edit window enforcement
        $minutesElapsed = now()->diffInMinutes($equipmentCost->created_at);
        if ($minutesElapsed > 5) {
            return Response::deny("Equipment cost records can only be edited within 5 minutes of creation. {$minutesElapsed} minutes have elapsed.");
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can delete the model.
     * 
     * ❌ AUDIT RULE: No deletion allowed - maintain complete audit trail
     */
    public function delete(User $user, EquipmentCost $equipmentCost): Response
    {
        return Response::deny('Equipment cost records cannot be deleted to maintain audit compliance.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, EquipmentCost $equipmentCost): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, EquipmentCost $equipmentCost): bool
    {
        return false;
    }
}
