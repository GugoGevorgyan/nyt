<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Src\Models\Driver\DriverStatus;

/**
 * Class CreateDriversTable
 */
class CreateDriversTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(
            'drivers',
            function (Blueprint $table) {
                $table->increments('driver_id')->index('index_driver_id');
                $table->unsignedInteger('driver_info_id')->nullable()->index('drivers_info_id');
                $table->unsignedInteger('entity_id')->nullable()->index('drivers_entity_id');
                $table->unsignedInteger('car_id')->nullable()->index('drivers_car_id');
                $table->unsignedInteger('current_franchise_id')->nullable()->index('drivers_current_franchise_id');
                $table->unsignedInteger('current_status_id')->default(DriverStatus::DRIVER_IS_FREE)->index(
                    'drivers_current_status_id'
                );
                $table->unsignedInteger('rating_level_id')->default(4)->index('drivers_rating_level_id');

                $table->json('selected_option')->nullable();
                $table->json('selected_class');

                $table->decimal('lat', 10, 8)->nullable();
                $table->decimal('lut', 10, 8)->nullable();
                $table->smallInteger('azimuth')->nullable();
                $table->double('mean_assessment', 2, 1)->default(0);
                $table->double('rating', 4, 1)->default(350);
                $table->string('device', 30)->nullable();
                $table->boolean('logged')->nullable()->default(0);
                $table->boolean('online')->default(0);
                $table->boolean('is_ready')->default(0);
                $table->string('nickname', 120)->nullable()->unique('drivers_nickname_uindex');
                $table->string('phone', 50)->nullable()->unique();
                $table->string('password', 250)->nullable();
                $table->softDeletes();
                $table->timestamps(6);
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
        Schema::drop('drivers');
    }
}
