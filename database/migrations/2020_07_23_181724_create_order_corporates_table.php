<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateOrderCorporatesTable
 */
class CreateOrderCorporatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('order_corporates', function (Blueprint $table) {
            $table->increments('order_corporate_id')->index('index_order_corporates_order_corporate_id');
            $table->unsignedInteger('order_id')->index('index_order_corporates_order_id');
            $table->unsignedInteger('company_id')->index('index_order_corporates_company_id');
            $table->unsignedInteger('driver_id')->nullable()->index('index_order_corporates_driver_id');
            $table->unsignedTinyInteger('slip_number')->unique()->nullable();
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
        Schema::dropIfExists('order_corporates');
    }
}
