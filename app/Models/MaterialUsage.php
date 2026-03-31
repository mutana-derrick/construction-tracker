<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialUsage extends Model
{
    protected $table = 'material_usage';

    protected $fillable = [
        'project_id',
        'user_id',
        'date',
        'material_name',
        'activity',
        'planned_qty',
        'used_qty',
    ];

    protected $casts = [
        'date' => 'date',
        'planned_qty' => 'decimal:2',
        'used_qty' => 'decimal:2',
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

    /**
     * Calculate difference (planned_qty - used_qty)
     * IMPORTANT: Not stored, computed dynamically
     */
    public function getDifferenceAttribute(): float
    {
        return $this->planned_qty - $this->used_qty;
    }
}
