<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateFranchiseCityTable
 */
class CreateFranchiseCityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(
            'franchise_city',
            function (Blueprint $table) {
                $table->increments('franchise_city_id')->index('franchise_city_id');
                $table->unsignedInteger('franchise_region_id')->index('franchise_city_region_id');
                $table->unsignedInteger('franchise_id')->index('franchise_city_franchise_id');
                $table->unsignedInteger('city_id')->index('franchise_city_city_id');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('franchise_city');
    }
}
