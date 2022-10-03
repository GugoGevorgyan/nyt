<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 *
 */
class CreateExternalBoardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('external_boards', function (Blueprint $table) {
            $table->increments('external_board_id')->index('external_board_id');
            $table->unsignedInteger('key_id')->index('external_board_key_id');
            $table->string('name',100);
            $table->unsignedBigInteger('passed_count')->default(0);
            $table->unsignedBigInteger('completed_count')->default(0);
            $table->json('oauth_payload');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('external_boards');
    }
}
