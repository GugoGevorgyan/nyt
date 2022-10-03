<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('pay_cards', function (Blueprint $table) {
            $table->increments('pay_card_id')->index('pay_cards_id');
            $table->unsignedInteger('temporary_pay_card_id')->index('pay_cards_temporary_pay_card_id');
            $table->unsignedInteger('owner_id')->index('pay_cards_owner_id');
            $table->string('owner_type', 30)->index('pay_cards_owner_type');
            $table->string('card_number', 19)->nullable();
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
        Schema::dropIfExists('pay_cards');
    }
}
