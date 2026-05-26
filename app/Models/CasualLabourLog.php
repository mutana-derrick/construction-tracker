<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CasualLabourLog extends Model
{
    protected $table = 'casual_labour_logs';

    protected $fillable = [
        'project_id',
        'user_id',
        'date',
        'activity',
        'activity_id',
        'labour_classification',
        'number_of_workers',
        'wage',
        'total_cost',
    ];

    protected $casts = [
        'date' => 'date',
        'wage' => 'decimal:2',
        'total_cost' => 'decimal:2',
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
}