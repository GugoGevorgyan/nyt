<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddForeignKeysToOrderFeedbackCommentsTable
 */
class AddForeignKeysToOrderFeedbackCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('order_feedback_comments', function (Blueprint $table) {
            $table->foreign('feedback_id', 'order_feedback_comments_foreign_feedback_id')
                ->references('order_feedback_id')
                ->on('order_feedbacks')
                ->onUpdate('NO ACTION')
                ->onDelete('CASCADE');

            $table->foreign('commenting_id', 'order_feedback_comments_foreign_commenting_id')
                ->references('system_worker_id')
                ->on('system_workers')
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
        Schema::table('order_feedback_comments', function (Blueprint $table) {
            $table->dropForeign('order_feedback_comments_foreign_feedback_id');
            $table->dropForeign('order_feedback_comments_foreign_commenting_id');
        });
    }
}
