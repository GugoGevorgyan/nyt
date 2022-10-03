<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateOauthAccessTokensTable
 */
class CreateOauthAccessTokensTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'oauth_access_tokens',
            static function (Blueprint $table) {
                $table->string('id', 100)->primary();
                $table->integer('user_id')->nullable()->index();
                $table->integer('client_id')->unsigned();
                $table->string('name')->nullable();
                $table->text('scopes')->nullable();
                $table->boolean('revoked');
                $table->timestamps(6);
                $table->dateTime('expires_at')->nullable();
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
        Schema::drop('oauth_access_tokens');
    }

}
