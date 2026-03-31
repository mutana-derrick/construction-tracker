<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductivityLog extends Model
{
    protected $fillable = [
        'project_id',
        'user_id',
        'date',
        'activity',
        'equipment_name',
        'workers',
        'output',
        'comment',
    ];

    protected $casts = [
        'date' => 'date',
        'output' => 'decimal:2',
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
     * Calculate labour productivity (output / workers)
     * IMPORTANT: Not stored, computed dynamically
     */
    public function getLabourProductivityAttribute(): ?float
    {
        if ($this->workers == 0) {
            return null;
        }
        return $this->output / $this->workers;
    }
}