<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateCarCrashesTable
 */
class CreateCarCrashesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('car_crashes', function (Blueprint $table) {
            $table->increments('car_crash_id')->index('index_car_crash_id');
            $table->unsignedInteger('car_id')->nullable()->index('crashes_car_id');
            $table->unsignedInteger('driver_id')->nullable()->index('crashes_driver_id');

            $table->dateTime('dateTime');
            $table->string('address');
            $table->text('description');
            $table->boolean('our_fault')->default(false);
            $table->text('inspector_info')->nullable();
            $table->text('participant_info')->nullable();
            $table->string('act')->nullable();
            $table->decimal('act_sum')->nullable();
            $table->timestamps(6);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('car_crashes');
    }
}
