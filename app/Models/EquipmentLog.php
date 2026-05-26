<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EquipmentLog extends Model
{
    protected $fillable = [
        'project_id',
        'user_id',
        'date',
        'equipment_type',
        'equipment_id',
        'activity',
        'activity_id',
        'planned_output',
        'actual_output',
        'working_hours',
        'available_hours',
        'fuel_used',
        'comment',
    ];

    protected $casts = [
        'date' => 'date',
        'planned_output' => 'decimal:2',
        'actual_output' => 'decimal:2',
        'working_hours' => 'decimal:2',
        'available_hours' => 'decimal:2',
        'fuel_used' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the project this log belongs to
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the user who created this log
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    /**
     * Calculate productivity (actual_output / working_hours)
     * IMPORTANT: Not stored, computed dynamically
     */
    public function getProductivityAttribute(): ?float
    {
        if ($this->working_hours == 0) {
            return null;
        }
        return $this->actual_output / $this->working_hours;
    }

    /**
     * Calculate utilization ((working_hours / available_hours) * 100)
     * IMPORTANT: Not stored, computed dynamically
     */
    public function getUtilizationAttribute(): ?float
    {
        if ($this->available_hours == 0) {
            return null;
        }
        return ($this->working_hours / $this->available_hours) * 100;
    }
}
