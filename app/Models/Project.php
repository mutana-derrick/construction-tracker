<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    protected $fillable = ['name', 'location', 'description', 'created_by'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user who created this project
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all equipment logs for this project
     */
    public function equipmentLogs(): HasMany
    {
        return $this->hasMany(EquipmentLog::class);
    }

    /**
     * Get all equipment costs for this project
     */
    public function equipmentCosts(): HasMany
    {
        return $this->hasMany(EquipmentCost::class);
    }

    /**
     * Get all productivity logs for this project
     */
    public function productivityLogs(): HasMany
    {
        return $this->hasMany(ProductivityLog::class);
    }

    /**
     * Get all casual labour logs for this project
     */
    public function casualLabourLogs(): HasMany
    {
        return $this->hasMany(CasualLabourLog::class);
    }

    /**
     * Get all material usage records for this project
     */
    public function materialUsage(): HasMany
    {
        return $this->hasMany(MaterialUsage::class);
    }

    /**
     * Get all material costs for this project
     */
    public function materialCosts(): HasMany
    {
        return $this->hasMany(MaterialCost::class);
    }
}
