<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 *
 */
class CreateTariffRentAltTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('tariff_rent_alt', function (Blueprint $table) {
            $table->increments('tariff_rent_alt_id')->index('tariff_rent_alt_id');
            $table->unsignedInteger('rent_id')->index('tariff_rent_alt_rent_id');
            $table->unsignedInteger('alt_id')->index('tariff_rent_alt_alt_id');
            $table->char('alt_type', 32)->index('tariff_rent_alt_rent_type');
            $table->integer('in_area')->index('tariff_rent_alt_in_area');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('tariff_rent_alt');
    }
}
