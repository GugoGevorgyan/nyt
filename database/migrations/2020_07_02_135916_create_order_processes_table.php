<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateOrderProcessesTable
 */
class CreateOrderProcessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(
            'order_processes',
            static function (Blueprint $table) {
                $table->increments('order_process_id')->index('order_processes_index_id');
                $table->unsignedInteger('order_shipped_id')->index('order_processes_index_order_shipped_id');

                $table->decimal('price')->nullable()->default(0.0);
                $table->decimal('total_price')->nullable()->default(0.0);
                $table->decimal('calculate_price')->nullable()->default(0.0);
                $table->decimal('increment_price')->nullable()->default(0.0);
                $table->decimal('options_price')->nullable()->default(0.0);
                $table->decimal('pause_price')->nullable()->default(0.0);
                $table->decimal('sitting_price')->nullable()->default(0.0);
                $table->decimal('cancel_price')->nullable()->default(0.0);
                $table->decimal('waiting_price')->nullable()->default(0.0);

                $table->unsignedMediumInteger('distance_traveled')->nullable()->default(0)->comment('METERS');
                $table->unsignedMediumInteger('travel_time')->nullable()->default(0)->comment('SECONDS');

                $table->unsignedSmallInteger('waiting_time')->nullable()->default(0);
                $table->unsignedSmallInteger('pause_time')->nullable()->default(0);

                $table->unsignedMediumInteger('speed')->nullable();

                $table->dateTime('cord_updated')->nullable();
                $table->dateTime('price_passed')->nullable();
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
        Schema::dropIfExists('order_processes');
    }
}
