<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPayCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('pay_cards', function (Blueprint $table) {
            $table
                ->foreign('temporary_pay_card_id', 'pay_cards_foreign_temporary_pay_card_id')
                ->references('temporary_pay_card_id')
                ->on('temporary_pay_cards')
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
        Schema::table('pay_cards', function (Blueprint $table) {
            $table->dropForeign('pay_cards_foreign_temporary_card_id');
        });
    }
}
