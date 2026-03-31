<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get all projects created by this user
     */
    public function createdProjects(): HasMany
    {
        return $this->hasMany(Project::class, 'created_by');
    }

    /**
     * Get all equipment logs created by this user
     */
    public function equipmentLogs(): HasMany
    {
        return $this->hasMany(EquipmentLog::class);
    }

    /**
     * Get all equipment costs created by this user
     */
    public function equipmentCosts(): HasMany
    {
        return $this->hasMany(EquipmentCost::class);
    }

    /**
     * Get all productivity logs created by this user
     */
    public function productivityLogs(): HasMany
    {
        return $this->hasMany(ProductivityLog::class);
    }

    /**
     * Get all casual labour logs created by this user
     */
    public function casualLabourLogs(): HasMany
    {
        return $this->hasMany(CasualLabourLog::class);
    }

    /**
     * Get all material usage records created by this user
     */
    public function materialUsage(): HasMany
    {
        return $this->hasMany(MaterialUsage::class);
    }

    /**
     * Get all material costs created by this user
     */
    public function materialCosts(): HasMany
    {
        return $this->hasMany(MaterialCost::class);
    }
}