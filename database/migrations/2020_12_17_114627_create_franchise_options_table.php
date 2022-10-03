<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Src\Models\Driver\DriverStatus;

/**
 * Class CreateFranchiseOptionsTable
 */
class CreateFranchiseOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(
            'franchise_options',
            function (Blueprint $table) {
                $table->increments('franchise_option_id')->index('franchise_options_index_id');
                $table->unsignedInteger('franchise_id')->index('franchise_options_index_franchise_id');
                $table->unsignedSmallInteger('order_pending_time')->default(60)->comment('SECONDS');
                $table->json('default_assessment')->nullable();
                $table->json('default_rating')->nullable();
                $table->json('waybill_max_days')->nullable();
                $table->unsignedTinyInteger('dispatching_minute')->default(30);
                $table->enum(
                    'order_cancel_before',
                    [
                        DriverStatus::DRIVER_ON_ACCEPT,
                        DriverStatus::DRIVER_ON_WAY,
                        DriverStatus::DRIVER_IN_PLACE,
                    ]
                )->default(DriverStatus::DRIVER_ON_ACCEPT)->comment('Разрешить отменять заказ до');
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('franchise_options');
    }
}
