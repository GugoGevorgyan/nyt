<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateOrderFeedbackCommentsTable
 */
class CreateOrderFeedbackCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('order_feedback_comments', function (Blueprint $table) {
            $table->increments('order_feedback_comment_id')->index('order_feedback_comments_id');
            $table->unsignedInteger('feedback_id')->index('order_feedback_comments_feedback_id');
            $table->unsignedInteger('commenting_id')->index('order_feedback_comments_commenting_id');
            $table->unsignedTinyInteger('new_status');
            $table->unsignedTinyInteger('old_status');
            $table->string('comment', 1000);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('order_feedback_comments');
    }
}
