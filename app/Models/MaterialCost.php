<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialCost extends Model
{
    protected $fillable = [
        'project_id',
        'user_id',
        'date',
        'material_name',
        'used_qty',
        'cost_per_item',
        'total',
    ];

    protected $casts = [
        'date' => 'date',
        'used_qty' => 'decimal:2',
        'cost_per_item' => 'decimal:2',
        'total' => 'decimal:2',
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
}
