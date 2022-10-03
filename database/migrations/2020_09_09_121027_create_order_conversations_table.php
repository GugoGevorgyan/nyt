<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateOrderConversationsTable
 */
class CreateOrderConversationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(
            'order_conversations',
            static function (Blueprint $table) {
                $table->increments('order_conversation_id')->index('order_conversations_id');

                $table->unsignedInteger('order_id')->index('order_conversations_order_id');
                $table->unsignedInteger('driver_id')->index('order_conversations_driver_id');

                $table->unsignedInteger('client_id')->index('order_conversations_client_id');
                $table->unsignedInteger('sender_id')->index('order_conversations_sender_id');

                $table->string('client_type', 100);
                $table->string('sender_type', 100);

                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
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
        Schema::dropIfExists('worker_order_conversation');
    }
}
