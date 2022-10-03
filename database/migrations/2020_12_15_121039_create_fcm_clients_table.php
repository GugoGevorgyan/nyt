<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateFcmClientsTable
 */
class CreateFcmClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(
            'fcm_clients',
            function (Blueprint $table) {
                $table->increments('fcm_client_id')->index('fcm_clients_index_id');
                $table->unsignedInteger('client_id')->index('fcm_clients_client_id');
                $table->string('client_type', 30)->index('fcm_clients_client_type');
                $table->string('key', 170)->index('fcm_clients_key');
                $table->timestamps();
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
        Schema::dropIfExists('fcm_clients');
    }
}
