<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AdForeignKeysToCarCrashImagesTable
 */
class AdForeignKeysToCarCrashImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('car_crash_images', static function (Blueprint $table) {
            $table->foreign('car_crash_id', 'car_crash_images_car_crash_id_foreign')
                ->references('car_crash_id')
                ->on('car_crashes')
                ->onUpdate('NO ACTION')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('car_crash_images', static function (Blueprint $table) {
            $table->dropForeign('car_crash_images_car_crash_id_foreign');
        });
    }
}
