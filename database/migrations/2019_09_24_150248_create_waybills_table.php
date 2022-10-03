<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateWaybillsTable
 */
class CreateWaybillsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(
            'waybills',
            function (Blueprint $table) {
                $table->increments('waybill_id')->index('index_waybill_id');

                $table->unsignedInteger('terminal_id')->nullable()->index('waybills_terminal_id');
                $table->unsignedInteger('car_id')->nullable()->index('waybills_car_id');
                $table->unsignedInteger('driver_id')->nullable()->index('waybills_driver_id');
                $table->unsignedInteger('system_worker_id')->nullable()->index('waybills_system_worker_id');

                $table->string('number', 12)->unique();
                $table->string('waybill', 300)->nullable()->unique();

                $table->boolean('verified')->default(0);
                $table->boolean('signed')->default(0);

                $table->dateTime('start_time');
                $table->dateTime('end_time');
                $table->string('comment', 500)->nullable();
                $table->decimal('price')->default(0);
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
    public function down(): void
    {
        Schema::drop('waybills');
    }

}
