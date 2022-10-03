<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateTemporaryPayCardsTable
 */
class CreateTemporaryPayCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('temporary_pay_cards', function (Blueprint $table) {
            $table->increments('temporary_pay_card_id')->index('temporary_pay_cards_id');
            $table->unsignedInteger('owner_id')->index('temporary_pay_cards_owner_id');
            $table->string('owner_type', 30)->index('temporary_pay_cards_owner_type');
            $table->string('transaction_id', 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('temporary_pay_cards');
    }
}
