<?php

namespace App\Providers;

use App\Models\Project;
use App\Models\EquipmentLog;
use App\Models\EquipmentCost;
use App\Models\ProductivityLog;
use App\Models\CasualLabourLog;
use App\Models\MaterialUsage;
use App\Models\MaterialCost;
use App\Policies\ProjectPolicy;
use App\Policies\EquipmentLogPolicy;
use App\Policies\EquipmentCostPolicy;
use App\Policies\ProductivityLogPolicy;
use App\Policies\CasualLabourLogPolicy;
use App\Policies\MaterialUsagePolicy;
use App\Policies\MaterialCostPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Project::class => ProjectPolicy::class,
        EquipmentLog::class => EquipmentLogPolicy::class,
        EquipmentCost::class => EquipmentCostPolicy::class,
        ProductivityLog::class => ProductivityLogPolicy::class,
        CasualLabourLog::class => CasualLabourLogPolicy::class,
        MaterialUsage::class => MaterialUsagePolicy::class,
        MaterialCost::class => MaterialCostPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
