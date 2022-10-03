<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddForeignKeysToOrderFeedbacksTable
 */
class AddForeignKeysToOrderFeedbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table(
            'order_feedbacks',
            static function (Blueprint $table) {
                $table->foreign('feedback_option_id', 'order_feedbacks_feedback_option_id')
                    ->references('order_feedback_option_id')
                    ->on('order_feedback_options')
                    ->onUpdate('NO ACTION')
                    ->onDelete('CASCADE');
                $table->foreign('order_id', 'order_feedbacks_order_id')
                    ->references('order_id')
                    ->on('orders')
                    ->onUpdate('NO ACTION')
                    ->onDelete('CASCADE');
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
        Schema::table(
            'order_feedbacks',
            static function (Blueprint $table) {
                $table->dropForeign('order_feedbacks_feedback_option_id');
                $table->dropForeign('order_feedbacks_order_id');
            }
        );
    }
}
