<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateRegionsTable
 */
class CreateRegionsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->increments('region_id')->index('index_region_id');
            $table->integer('country_id')->unsigned()->index('regions_country_id_foreign');
            $table->string('name')->index('regions_name_foreign');
            $table->string('iso_2', 10)->index('regions_iso_2_foreign')->nullable();
            $table->json('cord')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::drop('regions');
    }

}
