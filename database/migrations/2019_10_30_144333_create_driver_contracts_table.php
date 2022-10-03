<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateDriverContractsTable
 */
class CreateDriverContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(
            'driver_contracts',
            function (Blueprint $table) {
                $table->increments('driver_contract_id')->index('index_driver_contract_id');

                $table->unsignedInteger('driver_id')->index('drivers_id');
                $table->unsignedInteger('driver_type_id')->index('driver_types_id');
                $table->unsignedInteger('driver_subtype_id')->index('driver_subtypes_id');
                $table->unsignedInteger('driver_graphic_id')->index('driver_graphics_id');
                $table->unsignedInteger('car_id')->nullable()->index('cars_id');
                $table->unsignedInteger('entity_id')->nullable()->index('entities_id');

                $table->string('scan',120)->nullable();
                $table->decimal('free_days_price')->nullable();
                $table->decimal('busy_days_price')->nullable();
                $table->decimal('amount_paid', 10, 2)->nullable();

                $table->date('signing_day')->nullable();
                $table->date('expiration_day');
                $table->date('work_start_day');
                $table->integer('duration')->nullable();
                $table->boolean('active')->default(false);
                $table->boolean('signed')->default(false);
                $table->timestamps(6);
                $table->softDeletes();
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
        Schema::dropIfExists('driver_contracts');
    }
}
