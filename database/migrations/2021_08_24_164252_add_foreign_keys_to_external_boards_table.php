<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 *
 */
class AddForeignKeysToExternalBoardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('external_boards', function (Blueprint $table) {
            $table->foreign('key_id', 'external_boards_api_key_id')
                ->references('api_key_id')
                ->on('api_keys')
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
        Schema::table('external_boards', function (Blueprint $table) {
            $table->dropForeign('external_boards_api_key_id');
        });
    }
}
