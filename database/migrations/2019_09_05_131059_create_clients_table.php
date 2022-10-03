<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateClientsTable
 */
class CreateClientsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'clients',
            static function (Blueprint $table) {
                $table->increments('client_id')->index('index_client_id');
                $table->string('phone', 30)->unique();
                $table->string('name')->nullable();
                $table->string('surname')->nullable();
                $table->string('patronymic')->nullable();
                $table->string('email', 100)->nullable();
                $table->string('password', 250)->nullable();
                $table->char('remember_token', 100)->nullable();
                $table->string('device', 30)->nullable();
                $table->unsignedTinyInteger('mean_assessment')->default(4);
                $table->boolean('logged')->default(0);
                $table->boolean('online')->default(0);
                $table->boolean('in_order')->default(0);
                $table->boolean('only_passenger')->default(0);
                $table->softDeletes();
                $table->timestamps(6);
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
        Schema::drop('clients');
    }

}
