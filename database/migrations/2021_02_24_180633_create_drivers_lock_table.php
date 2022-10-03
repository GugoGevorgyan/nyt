<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateDriversLockTable
 */
class CreateDriversLockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('drivers_lock', function (Blueprint $table) {
            $table->increments('driver_lock_id')->index('drivers_lock_id');
            $table->unsignedInteger('driver_id')->index('drivers_lock_driver_id');
            $table->boolean('locked')->default(0);
            $table->unsignedSmallInteger('lock_count')->default(0);
            $table->dateTime('start')->nullable();
            $table->dateTime('end')->nullable();
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
        Schema::dropIfExists('drivers_lock');
    }
}
