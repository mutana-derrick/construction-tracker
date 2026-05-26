<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Activity extends Model
{
    protected $fillable = [
        'name',
        'created_by',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function equipmentLogs(): HasMany
    {
        return $this->hasMany(EquipmentLog::class);
    }

    public function equipmentCosts(): HasMany
    {
        return $this->hasMany(EquipmentCost::class);
    }

    public function productivityLogs(): HasMany
    {
        return $this->hasMany(ProductivityLog::class);
    }

    public function casualLabourLogs(): HasMany
    {
        return $this->hasMany(CasualLabourLog::class);
    }

    public function materialUsage(): HasMany
    {
        return $this->hasMany(MaterialUsage::class);
    }

    public function materialCosts(): HasMany
    {
        return $this->hasMany(MaterialCost::class);
    }
}
