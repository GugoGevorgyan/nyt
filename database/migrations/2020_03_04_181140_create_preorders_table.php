<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreatePreordersTable
 */
class CreatePreordersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(
            'preorders',
            static function (Blueprint $table) {
                $table->increments('preorder_id')->index('orders_schedule_index_id');
                $table->unsignedInteger('order_id')->index('orders_schedule_times_index_order_id');

                $table->dateTime('create_time');
                $table->dateTime('time')->nullable();
                $table->unsignedSmallInteger('diff_minute')->nullable();

                $table->dateTime('distribution_start')->nullable();
                $table->boolean('active')->default(1);
                $table->boolean('changed')->default(0);
                $table->boolean('skip')->default(0);
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
        Schema::dropIfExists('orders_schedules');
    }
}
