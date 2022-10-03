<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateFranchiseRegionTable
 */
class CreateFranchiseRegionTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('franchise_region', static function (Blueprint $table) {
            $table->increments('franchise_region_id')->index('index_franchise_region_id');
            $table->unsignedInteger('franchise_id')->nullable()->index('franchise_region_franchise_id_foreign');
            $table->unsignedInteger('region_id')->nullable()->index('franchise_region_region_id_foreign');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('franchise_region');
    }

}
