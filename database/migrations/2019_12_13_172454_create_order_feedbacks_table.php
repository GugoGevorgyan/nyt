<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateOrderFeedbacksTable
 */
class CreateOrderFeedbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(
            'order_feedbacks',
            static function (Blueprint $table) {
                $table->increments('order_feedback_id')->index('order_feedbacks_index_id');
                $table->unsignedInteger('feedback_option_id')->nullable()->index('order_feedbacks_index_feedback_option_id');
                $table->unsignedInteger('order_id')->index('order_feedbacks_order_id');
                $table->unsignedInteger('orderable_id')->index('order_feedbacks_orderable_id')->nullable();
                $table->string('orderable_type', 25)->index('order_feedbacks_orderable_type')->nullable();
                $table->unsignedInteger('writable_id')->index('order_feedbacks_writable_id');
                $table->string('writable_type', 25)->index('order_feedbacks_writable_type');
                $table->unsignedInteger('readable_id')->index('order_feedbacks_readable_id')->nullable();
                $table->string('readable_type',25)->index('order_feedbacks_readable_type')->nullable();
                $table->string('text', 1000)->nullable();
                $table->unsignedTinyInteger('assessment')->default(0);
                $table->timestamps();
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
        Schema::dropIfExists('order_feedbacks');
    }
}
