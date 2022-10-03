<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateOrderConversationTalksTable
 */
class CreateOrderConversationTalksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(
            'order_conversation_talks',
            static function (Blueprint $table) {
                $table->increments('order_conversation_talk_id')->index('order_conversation_talks_id');
                $table->unsignedInteger('order_conversation_id')->index('order_conversation_talks_conversation_id');
                $table->unsignedInteger('sender_id')->index('order_conversation_talks_sender_id');
                $table->string('sender_type');
                $table->string('message', 1000);
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
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
        Schema::dropIfExists('order_conversations_talk');
    }
}
