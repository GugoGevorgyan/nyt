<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateBeforeAuthClientsTable
 */
class CreateBeforeAuthClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('before_auth_clients', static function (Blueprint $table) {
            $table->increments('before_auth_client_id')->index('before_auth_client_id');
            $table->unsignedInteger('client_id')->index('before_auth_client_client_id')->nullable()->default(null);
            $table->string('hash_name', 64)->unique();
            $table->string('hash', 128)->unique();
            $table->rememberToken();
            $table->timestamps(6);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('before_auth_clients');
    }
}
