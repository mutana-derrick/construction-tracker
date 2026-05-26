<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('equipment_logs', function (Blueprint $table) {
            $table->foreignId('activity_id')
                ->nullable()
                ->after('activity')
                ->constrained('activities')
                ->nullOnDelete();
        });

        Schema::table('equipment_costs', function (Blueprint $table) {
            $table->foreignId('activity_id')
                ->nullable()
                ->after('activity')
                ->constrained('activities')
                ->nullOnDelete();
        });

        Schema::table('productivity_logs', function (Blueprint $table) {
            $table->foreignId('activity_id')
                ->nullable()
                ->after('activity')
                ->constrained('activities')
                ->nullOnDelete();
        });

        Schema::table('casual_labour_logs', function (Blueprint $table) {
            $table->foreignId('activity_id')
                ->nullable()
                ->after('activity')
                ->constrained('activities')
                ->nullOnDelete();
        });

        Schema::table('material_usage', function (Blueprint $table) {
            $table->foreignId('activity_id')
                ->nullable()
                ->after('activity')
                ->constrained('activities')
                ->nullOnDelete();
        });

        Schema::table('material_costs', function (Blueprint $table) {
            $table->foreignId('activity_id')
                ->nullable()
                ->after('activity')
                ->constrained('activities')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipment_logs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('activity_id');
        });

        Schema::table('equipment_costs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('activity_id');
        });

        Schema::table('productivity_logs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('activity_id');
        });

        Schema::table('casual_labour_logs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('activity_id');
        });

        Schema::table('material_usage', function (Blueprint $table) {
            $table->dropConstrainedForeignId('activity_id');
        });

        Schema::table('material_costs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('activity_id');
        });
    }
};
