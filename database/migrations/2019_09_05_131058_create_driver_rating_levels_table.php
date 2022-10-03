<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateDriverRatingLevelsTable
 */
class CreateDriverRatingLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(
            'driver_rating_levels',
            static function (Blueprint $table) {
                $table->increments('driver_rating_level_id')->index('driver_rating_level_index_id');
                $table->char('level', 25);
                $table->smallInteger('from');
                $table->smallInteger('before');
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
        Schema::dropIfExists('driver_rating_levels');
    }
}
