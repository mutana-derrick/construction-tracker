<?php

namespace App\Policies;

use App\Models\User;
use App\Models\EquipmentCost;


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
    public function update(User $user, EquipmentCost $equipmentCost): bool
    {
        if ($user->role !== 'recorder') {
            return false;
        }

        if ($user->id !== $equipmentCost->user_id) {
            return false;
        }

        return $equipmentCost->created_at->copy()->addMinutes(5)->isFuture();
    }

    /**
     * Determine whether the user can delete the model.
     * 
     * ❌ AUDIT RULE: No deletion allowed - maintain complete audit trail
     */
    public function delete(User $user, EquipmentCost $equipmentCost): bool
    {
        return false;
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