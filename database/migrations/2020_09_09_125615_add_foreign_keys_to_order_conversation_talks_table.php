<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddForeignKeysToOrderConversationTalksTable
 */
class AddForeignKeysToOrderConversationTalksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('order_conversation_talks', static function (Blueprint $table) {
            $table->foreign('order_conversation_id', 'order_conversations_foreign_conversation_id')
                ->references('order_conversation_id')
                ->on('order_conversations')
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
        Schema::table('order_conversations_talk', function (Blueprint $table) {
            $table->dropForeign('order_conversations_foreign_conversation_id');
        });
    }
}
