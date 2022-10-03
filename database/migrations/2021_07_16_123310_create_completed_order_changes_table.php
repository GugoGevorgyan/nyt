<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateCompletedOrderChangesTable
 */
class CreateCompletedOrderChangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('completed_order_changes', function (Blueprint $table) {
            $table->increments('completed_order_change_id')->index('completed_order_changes_id');
            $table->unsignedInteger('completed_id')->index('completed_order_changes_completed_id');
            $table->unsignedInteger('changer_id')->index('completed_order_changes_changer_id')->comment('system_workers');

            $table->decimal('old_price');
            $table->decimal('new_price');
            $table->unsignedFloat('old_distance',5,1)->nullable();
            $table->unsignedFloat('new_distance',5,1)->nullable();
            $table->unsignedSmallInteger('old_duration')->nullable();
            $table->unsignedSmallInteger('new_duration')->nullable();
            $table->json('old_trajectory')->nullable();
            $table->json('new_trajectory')->nullable();

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        DB::statement("ALTER TABLE `completed_order_changes` comment 'logs for completed orders data changes'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('completed_order_changes');
    }
}
