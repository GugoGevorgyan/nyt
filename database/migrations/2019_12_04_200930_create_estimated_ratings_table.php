<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateEstimatedRatingsTable
 */
class CreateEstimatedRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'estimated_ratings',
            static function (Blueprint $table) {
                $table->increments('estimated_rating_id')->index('estimated_ratings_index_id');

                $table->unsignedInteger('order_id')->index('estimated_ratings_index_order_id');
                $table->unsignedInteger('driver_id')->index('estimated_ratings_index_driver_id');

                $table->double('added_rating', 3, 1);
                $table->double('remove_rating', 3, 1);

                $table->json('added_patterns')->nullable();
                $table->json('remove_patterns')->nullable();
                $table->set('outcome', ['added', 'removed'])->nullable();

                $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
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
        Schema::dropIfExists('estimated_ratings');
    }
}
